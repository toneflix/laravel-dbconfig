<?php

namespace ToneflixCode\DbConfig;

use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use ToneflixCode\DbConfig\Commands\ConfigCreate;
use ToneflixCode\DbConfig\Commands\ConfigSet;
use ToneflixCode\DbConfig\Commands\{ConfigShow, ConfigPurge};
use ToneflixCode\DbConfig\Models\Configuration;
use ToneflixCode\LaravelFileable\Media;

class DbConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-dbconfig.php'),
            ], 'dbconfig');

            $this->publishes([
                __DIR__ . '/../database/factories/ConfigurationFactory.php' => database_path('factories/ConfigurationFactory.php'),
                __DIR__ . '/../database/seeders/ConfigurationSeeder.php' => database_path('seeders/ConfigurationSeeder.php'),
            ], 'dbconfig-data');

            $migrations = [];
            if ($this->unmigrated('create_files_table')) {
                $migrations[__DIR__ . '/../database/migrations/2022_06_30_045718_create_files_table.php'] =
                    database_path('migrations/' . date('Y_m_d_His', time()) . '_create_files_table.php');
            }

            if ($this->unmigrated('create_configurations_table')) {
                $migrations[__DIR__ . '/../database/migrations/2022_11_01_225856_create_configurations_table.php'] =
                    database_path('migrations/' . date('Y_m_d_His', time()) . '_create_configurations_table.php');
            }

            if (count($migrations) > 0) {
                $this->publishes($migrations, 'dbconfig-migrations');
            }

            $this->commands([
                ConfigSet::class,
                ConfigShow::class,
                ConfigPurge::class,
                ConfigCreate::class,
            ]);

            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            $formatYesOrNo = fn($value) => $value ? '<fg=green;options=bold>YES</>' : '<fg=yellow;options=bold>NO</>';

            AboutCommand::add('Laravel DbConfig', static fn() => array_filter([
                'Version' => InstalledVersions::getPrettyVersion('toneflix-code/laravel-dbconfig'),
                'Cached' => AboutCommand::format(Cache::has('laravel-dbconfig.configurations::build'), $formatYesOrNo),
                'Configs Created' => Configuration::count('id'),
            ]));

            config([
                'toneflix-fileable.collections.dbconfig' => config('laravel-dbconfig.upload_collection'),
            ]);
        }
    }

    protected function unmigrated(string $table)
    {
        return empty(array_filter(
            File::files(base_path('database/migrations')),
            function (\SplFileInfo $file) use ($table) {
                return str($file->getBasename())->contains($table);
            }
        ));
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-dbconfig');

        if ($this->app->runningUnitTests()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/toneflix-fileable.php', 'toneflix-fileable');
            $this->app->singleton('laravel-fileable', function () {
                return new Media;
            });
        }
    }
}