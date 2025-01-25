<?php

namespace ToneflixCode\DbConfig\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\ConfigShowCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Attribute\AsCommand;
use ToneflixCode\DbConfig\Helpers\Configure;

// @codeCoverageIgnoreStart
#[AsCommand(name: 'dbconfig:show')]
class ConfigShow extends ConfigShowCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'dbconfig:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display all of the values for the database configurations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $table = config('laravel-dbconfig.tables.configurations', 'configurations');

        if (! Schema::hasTable($table)) {
            $this->components->error("The database configuration table `{$table}` does not exist exist.");

            return Command::FAILURE;
        }

        $this->newLine();
        $this->render($table);
        $this->newLine();

        return Command::SUCCESS;
    }

    /**
     * Render the configuration values.
     *
     * @param  string  $table
     * @return void
     */
    public function render($table)
    {
        $data = Configure::config(loadSecret: true)->toArray();

        if (! is_array($data)) {
            $this->title($table, $this->formatValue($data));

            return;
        }

        $this->title($table);

        foreach (Arr::dot($data) as $key => $value) {
            $this->components->twoColumnDetail(
                $this->formatKey($key),
                $this->formatValue($value)
            );
        }
    }
}
// @codeCoverageIgnoreEnd