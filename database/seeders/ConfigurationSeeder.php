<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use ToneflixCode\DbConfig\Models\Configuration;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::forget('laravel-dbconfig.configurations::build');

        Configuration::upsert([
            [
                'key' => 'app_logo',
                'title' => 'App Logo',
                'value' => null,
                'type' => 'file',
                'count' => null,
                'max' => null,
                'col' => 6,
                'autogrow' => false,
                'hint' => '',
                'secret' => false,
                'group' => 'main',
                'choices' => json_encode([]),
            ],
        ], ['key'], ['title', 'value', 'hint', 'group', 'max', 'col', 'autogrow', 'secret', 'choices']);
    }
}
