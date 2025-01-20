<?php

namespace ToneflixCode\DbConfig\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use ToneflixCode\DbConfig\Models\Configuration;

class Configure
{
    // @codeCoverageIgnoreStart
    /**
     * Validates a JSON string.
     *
     * @param  string  $json  The JSON string to validate.
     * @param  int  $depth  Maximum depth. Must be greater than zero.
     * @param  int  $flags  Bitmask of JSON decode options.
     * @return bool Returns true if the string is a valid JSON, otherwise false.
     */
    public static function jsonValidate($json, $depth = 512, $flags = 0)
    {
        if (function_exists('json_validate')) {
            return json_validate($json, $depth, $flags);
        }

        if (! is_string($json)) {
            return false;
        }

        try {
            json_decode($json, false, $depth, $flags | JSON_THROW_ON_ERROR);

            return true;
        } catch (\JsonException $e) {
            return false;
        }
    }
    // @codeCoverageIgnoreEnd

    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array<string, mixed>|string|null  $key
     * @return ($key is null|array ? Collection : ($key is string ? mixed : null))
     */
    public static function config(
        string|array|null $key = null,
        mixed $default = null,
        bool $loadSecret = false
    ): Collection|string|int|float|array|null {
        $config = Configuration::build($loadSecret);

        if (is_array($key)) {
            return Configuration::setConfig($key);
        }

        if (is_null($key)) {
            return $config;
        }

        return Arr::get($config, $key, $default) ?? $default;
    }

    /**
     * Checks if a config value is a file
     */
    public static function savable(mixed $value): bool
    {
        if (is_array($value) && isset($value[0])) {
            foreach ($value as $val) {
                if ($val instanceof UploadedFile || $val instanceof \Illuminate\Http\Testing\File) {
                    return true;
                }
            }
        }

        if ($value instanceof UploadedFile || $value instanceof \Illuminate\Http\Testing\File) {
            return true;
        }

        return false;
    }
}
