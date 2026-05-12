<?php

namespace Toneflix\DbConfig\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use Toneflix\DbConfig\Database\Factories\ConfigurationFactory;
use Toneflix\DbConfig\DbConfigServiceProvider;
use Toneflix\DbConfig\Tests\Database\Seeders\ConfigurationSeeder;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected $factories = [
        ConfigurationFactory::class,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        if (stripos($this->name(), 'Skip')) {
            $this->markTestSkipped('Temporarily Skipped!');
        }

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Toneflix\\DbConfig\\Database\\Factories\\'.
                class_basename(
                    $modelName
                ).'Factory'
        );
    }

    protected function defineEnvironment($app)
    {
        tap($app['config'], function (Repository $config) {
            $config->set('app.faker_locale', 'en_NG');
            $config->set('cache.default', 'file');
        });
    }

    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    // public function refreshDatabase()
    // {
    //     if (config('database.default')) {
    //         $this->beforeRefreshingDatabase();

    //         if ($this->usingInMemoryDatabase()) {
    //             $this->restoreInMemoryDatabase();
    //         }

    //         $this->refreshTestDatabase();

    //         $this->afterRefreshingDatabase();
    //     }
    // }

    protected function getPackageProviders($app)
    {
        return [
            DbConfigServiceProvider::class,
        ];
    }

    /**
     * Perform any work that should take place once the database has finished refreshing.
     *
     * @return void
     */
    protected function afterRefreshingDatabase()
    {
        $this->seed(ConfigurationSeeder::class);
    }
}
