<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Upload Collection
    |--------------------------------------------------------------------------
    |
    | The library uses `toneflix-code/laravel-fileable` for storing files, you
    | can configure the upload path and the options by modifying
    | the `upload_collection` property or add a `dbconfig` collection to
    | the `toneflix-fileable` configuration.
    |
    */

    'upload_collection' => [
        'path' => 'storage/',
        'default' => 'default.png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tables
    |--------------------------------------------------------------------------
    |
    | This controls the table names that will be used to resolve your
    | configuraion options, this must be set before running migrations and
    | should never be changed afterwards.
    |
    */

    'tables' => [
        'configurations' => 'configurations',
        'fileables' => 'fileables',
    ],

    /*
    |--------------------------------------------------------------------------
    | Disable Cache
    |--------------------------------------------------------------------------
    |
    | By default, the configurations are cached, the cache is and only updated
    | when a value changes.
    | To disable configuration caching, set this value to false
    |
    */

    'disable_cache' => false,
];
