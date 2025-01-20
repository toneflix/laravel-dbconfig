<?php

namespace ToneflixCode\DbConfig\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Cache;
use ToneflixCode\LaravelFileable\Traits\Fileable as FileableTrait;

class Fileable extends Model
{
    use FileableTrait;

    protected $fillable = [
        'model',
        'meta',
        'file',
        'fileable_collection',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'meta' => '{}',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'file_url',
        'shared_url',
    ];

    public ?int $fileIndex = null;

    /**
     * Map fileable to a collection
     *
     * @var array<class-string, string>
     *
     * @example $mediaPaths [User::class => 'avatar', Configuration::class => 'dbconfig']
     */
    public $mediaPaths = [
        Configuration::class => 'dbconfig',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return $this
     */
    public function getTable()
    {
        return config('laravel-dbconfig.tables.fileables', 'fileables');
    }

    public function registerFileable()
    {
        $this->fileableLoader(
            file_field: 'file',
            collection: $this->fileable_collection ?? $this->mediaPaths[$this->fileable_type] ?? 'dbconfig',
            applyDefault: false,
        );
    }

    /**
     * Get the parent fileable model (album or vision board).
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    public function progress(): Attribute
    {
        return new Attribute(
            get: function () {
                $diskName = 'streamable_media';

                $meta_type = isset($this->meta['type']) ? ".{$this->meta['type']}" : '';

                $mediaPath = $this->mediaPaths[$this->fileable_type.$meta_type] ?? 'dbconfig';

                if ($mediaPath === 'private.music') {
                    $diskName = 'gpaf_media';
                }

                $progress = 0;

                if ($this->fileable->processed_at) {
                    return 100;
                } elseif ($diskName === 'gpaf_media') {
                    $progress = Cache::get('media.segment.'.str($this->file.'.'.$this->id)->toString(), 0);
                }

                return $progress;
            },
        );
    }

    public function progressComplete(): Attribute
    {
        return new Attribute(
            get: fn () => $this->progress >= 100 ? '100% completed!' : "{$this->progress}% processing...",
        );
    }

    /**
     * Get a shared/public URL of the image.
     *
     * @return string
     */
    protected function sharedUrl(): Attribute
    {
        $link = $this->get_files['file']['secureLink'];
        if (($this->mediaPaths[$this->fileable_type] ?? 'dbconfig') === 'dbconfig') {
            $link = $this->get_files['file']['dynamicLink'];
        }

        return Attribute::make(get: fn () => $link);
    }
}
