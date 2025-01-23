<?php

namespace ToneflixCode\DbConfig\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use ToneflixCode\DbConfig\Helpers\Configure;
use ToneflixCode\DbConfig\Models\Configuration;

#[AsCommand(name: 'dbconfig:purge')]
class ConfigPurge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        dbconfig:purge
            {key? : The key of a config option to delete (leave empty to purge the entire configuration table).}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge or remove configuration options from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = $this->argument('key');
        $table = config('laravel-dbconfig.tables.configurations', 'configurations');

        $message = $key
            ? "Are you sure you want to delete the `{$key}` config option?"
            : "You have not provided any configuration option key, do you want to purge the entire `{$table}` table?";

        // Confirm if the user still wants to contine
        if (! $this->confirm($message)) {
            $this->info("Action Cancelled.");

            return 0;
        }

        if ($key) {
            if (! Configuration::where('key', $key)->exists()) {
                $this->error("The `{$key}` configuration option does not exist.");

                return 1;
            }

            Configuration::where('key', $key)->delete();

            $this->info("The `{$key}` configuration option has been removed.");
        } else {
            Configuration::truncate();
            $this->info("The entire `{$table}` table has been purged, you now have a clean slate! Chiiii!!!");
        }

        return 0;
    }
}