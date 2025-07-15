<?php

namespace ToneflixCode\DbConfig\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'dbconfig:sync')]
class ConfigSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        dbconfig:sync
            {class? : The seeder class to use, defaults to `ConfigurationSeeder`).}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'An alias for `artisan db:seed --class=ConfigurationSeeder`, optionally you can pass the required seeder class an argument.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $class = $this->argument('class') ?: 'ConfigurationSeeder';

        if (File::exists(database_path("seeders/{$class}.php"))) {
            $this->callSilently('db:seed', ['--class' => $class]);

            $this->components->success('Syncing Complete.');
        } else {
            $this->components->error("The {$class} seeder was not found.");
        }
    }
}
