<?php

namespace ToneflixCode\DbConfig\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use ToneflixCode\DbConfig\Models\Configuration;

#[AsCommand(name: 'app:config-create')]
class ConfigCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        app:config-create
            {key? : The key of the config option to create.}
            {title? : The title of the config option to create.}
            {value? : The default value for the config option}
            {type? : The expected type of the config option}
            {hint? : Add a hint for the config option}
            {choices?* : If the config option expect predefined values, provide those options here}
            {--g|group=main : Set the default group for the config option}
            {--s|secret : Determines if the config option is a secret}
            {--f|force : No confirmation will be required.}
            {--c|cols=6 : The number of columns this config option is to take (when looping in the UI)}
            {--m|max= : If the config option is of type int,float,number,integer, set a max value}
            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new configuration option';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        [
            'max' => $max,
            'cols' => $cols,
            'force' => $force,
            'group' => $group,
            'secret' => $secret,
        ] = $this->options();

        $key = $this->argument('key') ?:
            $this->ask('What should be the key for this config (snake_cased or whatever you like)?');

        $title = $this->argument('title') ?:
            $this->ask('What should be the title for this config?');

        $value = $this->argument('value') ?:
            $this->ask('What should be the default value for this config?');

        $hint = $this->argument('hint') ?: (! $force
            ? $this->ask('Add an optional hint?')
            : null
        );

        $type = $this->argument('type') ?: $this->choice('What type of value should this config expect?', ['textarea', 'string', 'file', 'files', 'json', 'bool', 'text', 'number', 'integer', 'float', 'int']);

        Model::unguard();

        if (! $key || ! $title) {
            $this->info('We could not save your config, please provide a valid key and title.');

            return 1;
        }

        if (! $force && ! $this->confirm("Are you sure you want to continue creating the {$key} config option?")) {
            return 0;
        }

        Configuration::updateOrCreate(['key' => $key], [
            'key' => $key,
            'title' => $title,
            'value' => $value,
            'type' => $type ?? 'string',
            'count' => null,
            'max' => $max,
            'col' => $cols,
            'autogrow' => false,
            'hint' => $hint ?? '',
            'secret' => $secret,
            'group' => $group,
            'choices' => json_encode([]),
        ]);

        $this->info("Your configuration option \"{$key}\" has been created successfully.");

        return 0;
    }
}
