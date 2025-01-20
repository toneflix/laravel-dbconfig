<?php

namespace ToneflixCode\DbConfig\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

// @codeCoverageIgnoreStart
class ConfigType implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (request()->isMethod('POST') || request()->isMethod('PUT')) {
            return match (true) {
                $value === 'textarea' => 'string',
                $value === 'text' => 'string',
                $value === 'bool' => 'boolean',
                $value === 'json' => 'array',
                $value === 'files' => 'file',
                in_array($value, ['number', 'integer', 'float', 'int']) => 'numeric',
                default => $value,
            };
        }

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
// @codeCoverageIgnoreEnd
