<?php

use Illuminate\Support\Collection;
use ToneflixCode\DbConfig\Helpers\Configure;

if (! function_exists('dbconfig')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array<string, mixed>|string|null  $key
     * @return ($key is null ? Collection : ($key is string ? mixed : null))
     */
    function dbconfig(
        string|array|null $key = null,
        mixed $default = null,
        bool $loadSecret = false
    ): Collection|string|int|float|array|null {
        return Configure::config($key, $default, $loadSecret);
    }
}
