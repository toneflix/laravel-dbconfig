<?php

namespace Toneflix\DbConfig\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Toneflix\DbConfig\Casts\ConfigType;
use Toneflix\DbConfig\Casts\ConfigValue;
use Toneflix\DbConfig\Helpers\Configure;
use Toneflix\LaravelFileable\Facades\Media;

/**
 * @property \Illuminate\Database\Eloquent\Collection<Fileable> $files
 * @property bool $multiple
 *
 * @method static Model<Configuration> notSecret($secret = false)
 */
class Configuration extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes to be appended
     *
     * @var array
     */
    protected $appends = [
        'multiple',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'value',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'col' => 12,
        'max' => null,
        'hint' => '',
        'type' => 'string',
        'count' => null,
        'group' => 'main',
        'secret' => false,
        'choices' => '[]',
        'autogrow' => false,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => ConfigType::class,
        'value' => ConfigValue::class,
        'secret' => 'boolean',
        'autogrow' => 'boolean',
        'choices' => AsCollection::class,
    ];

    /**
     * Get the table associated with the model.
     *
     * @return $this
     */
    public function getTable()
    {
        return config('laravel-dbconfig.tables.configurations', 'configurations');
    }

    public static function boot(): void
    {
        parent::boot();

        self::saved(function () {
            Cache::forget('laravel-dbconfig.configurations::build');
        });
    }

    /**
     * Set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array<string, mixed>|string|null  $key
     * @return Collection
     */
    public static function setConfig(
        string|array|null $key = null,
        mixed $value = null,
        bool $loadSecret = false
    ) {
        if (is_array($key)) {
            foreach ($key as $key => $value) {
                static::persistConfig($key, $value);
            }
        } else {
            if ($value !== '***********') {
                static::persistConfig($key, $value);
            }
        }

        Cache::forget('laravel-dbconfig.configurations::build');

        return Configuration::build($loadSecret);
    }

    /**
     * Actually persist the configuration to storage
     */
    protected static function persistConfig(string $key, mixed $value): void
    {
        /** @var self */
        $config = static::where('key', $key)->first();

        $saveable = Configure::savable($value);

        if ($saveable) {
            $value = $config->doUpload($value);
        }
        $config->value = $value;
        $config->save();
    }

    public static function build($loadSecret = false)
    {
        if (config('laravel-dbconfig.disable_cache', false) || $loadSecret) {
            return static::buildConfig($loadSecret);
        }

        if (app()->runningUnitTests()) {
            Cache::flush();
        }

        return Cache::remember(
            'laravel-dbconfig.configurations::build',
            null,
            fn () => static::buildConfig()
        );
    }

    /**
     * Build the configuration collection
     *
     * @return Collection<TMapWithKeysKey, TMapWithKeysValue>
     */
    public static function buildConfig(bool $loadSecret = false): Collection
    {
        return static::query()
            ->when(! $loadSecret, fn ($query) => $query->where('secret', false))
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->key => $item->value];
            });
    }

    public function files()
    {
        return $this->morphMany(Fileable::class, 'fileable');
    }

    public function scopeNotSecret(Builder $query, $secret = false): void
    {
        $query->whereSecret($secret);
    }

    public function multiple(): Attribute
    {
        return new Attribute(
            get: fn () => (count($this->choices ?? []) && $this->autogrow) || ($this->type === 'array' && $this->count),
            set: fn ($value) => [
                'autogrow' => $value,
            ],
        );
    }

    /**
     * Upload a file as configuration value
     *
     * @param  UploadedFile|UploadedFile[]  $files
     * @return string
     */
    public function doUpload(UploadedFile|array $files)
    {
        $value = DB::transaction(function () use ($files) {
            $value = [];
            try {
                if (is_array($files)) {
                    $value = collect($files)->map(function (UploadedFile $item, int $i) {
                        $file = $this->files()->firstOrNew();
                        $file->meta = ['type' => 'configuration', 'key' => $this->key ?? ''];
                        $file->file = Media::save('dbconfig', $item, $this->files[$i]->file ?? null);
                        $file->saveQuietly();

                        return $file->id;
                    })->toArray();
                } else {
                    $file = $this->files()->firstOrNew();
                    $file->meta = ['type' => 'configuration', 'key' => $this->key ?? ''];
                    $file->file = Media::save('dbconfig', $files, $this->files[0]->file ?? null);
                    $file->saveQuietly();
                    $value = [$file->id];

                    return $value;
                }
            } catch (\Throwable $th) {
                throw ValidationException::withMessages([
                    $this->key ?? 'value' => $th->getMessage(),
                ]);
            }
        });

        return json_encode($value);
    }
}
