<?php

//
return [
    'file_name_pattern' => '000000000_000000000',
    'collections' => [
        'default' => [
            'path' => 'media/default/',
            'default' => 'default.png',
        ],
    ],
    'image_sizes' => [
        'xs' => '431',
        'sm' => '431',
        'md' => '694',
        'lg' => '720',
        'xl' => '1080',
        'xs-square' => '431x431',
        'sm-square' => '431x431',
        'md-square' => '694x694',
        'lg-square' => '720x720',
        'xl-square' => '1080x1080',
    ],
    'file_route_secure_middleware' => 'window_auth',
    'responsive_image_route' => 'media/images/responsive/{file}/{size}',
    'file_route_secure' => 'secure/media/files/{file}',
    'file_route_open' => 'media/files/{file}',
    'symlinks' => [
        public_path('avatars') => storage_path('app/public/avatars'),
        public_path('media') => storage_path('app/public/media'),
    ],
];
