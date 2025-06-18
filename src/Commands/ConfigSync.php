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
    protected $signature = 'dbconfig:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'An alias for `artisan db:seed --class=ConfigurationSeeder`';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (File::exists(database_path('seeders/ConfigurationSeeder.php'))) {
            $this->call('db:seed', ['--class' => 'ConfigurationSeeder']);
        } else {
            $this->error('The ConfigurationSeeder class is not found.');
        }
    }
}
