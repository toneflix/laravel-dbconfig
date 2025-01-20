<?php

namespace ToneflixCode\DbConfig\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use ToneflixCode\DbConfig\Models\Configuration;
use ToneflixCode\LaravelFileable\Facades\Media;

class ConfigValue implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $this->build($value, $attributes['type'], $model);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Configuration  $model
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $type = $attributes['type'];

        return match (true) {
            ($model->secret ?? false) && $value === '***********' => $model->value ?: '',
            is_array($value) => json_encode($value, JSON_FORCE_OBJECT),
            is_bool($value) => $value ? 0 : 1,
            in_array(mb_strtolower($type), ['number', 'integer', 'float', 'int']) => (int) $value,
            default => (string) $value,
        };
    }

    /**
     * Undocumented function
     *
     * @param  Configuration  $model
     */
    protected function build(mixed $value, string $type, Model $model): mixed
    {
        $default = Media::getDefaultMedia('default');

        return match (true) {
            $model->secret && request()->boolean('hide-secret') => '***********',
            $type === 'file' => $model->files->map(fn($f) => $f->files['file'])->first(null, $default),
            $type === 'files' => $model->files->map(fn($f) => $f->files['file']),
            in_array(mb_strtolower($type), ['bool', 'boolean']) => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            in_array(mb_strtolower($type), ['json', 'array']) => collect(json_decode($value, true)),
            in_array(mb_strtolower($type), ['number', 'integer', 'float', 'int']) => (int) $value,
            default => $value,
        };
    }
}
