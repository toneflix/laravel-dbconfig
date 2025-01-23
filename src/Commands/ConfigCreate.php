<?php

namespace ToneflixCode\DbConfig\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;
use ToneflixCode\DbConfig\Models\Configuration;

#[AsCommand(name: 'dbconfig:create')]
class ConfigCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        dbconfig:create
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
    protected $description = 'Creates a new configuration option.';

    /**
     * A list of all valid config types
     *
     * @var array<int,string>
     */
    protected $valid_types = [
        'textarea',
        'string',
        'file',
        'files',
        'json',
        'bool',
        'text',
        'number',
        'integer',
        'float',
        'int',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Parse the options
        [
            'max' => $max,
            'cols' => $cols,
            'force' => $force,
            'group' => $group,
            'secret' => $secret,
        ] = $this->options();

        // Get or request for the config type
        $type = $this->getConfigType();

        // Get or request for the config key
        $key = $this->getConfigKey();

        // Get or request for the config title
        $title = $this->argument('title') ?:
            $this->ask('What should be the title for this config?');

        // Get or request for the config default value then cast it to the correct type
        if (! in_array($type, ['file', 'files', 'json'])) {
            $value = $this->argument('value') ?:
                $this->ask('What should be the default value for this config?');

            // Cast the default value to the correct type
            if ($type === 'bool') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif (in_array($type, ['number', 'integer'])) {
                $value = (int) $value;
            } elseif ($type === 'float') {
                $value = (float) $value;
            }
        } else {
            $value = $type === 'json' ? '{}' : null;
        }

        // Get or request for the config hint
        $hint = $this->argument('hint') ?: (! $force
            ? $this->ask('Add an optional hint?')
            : null
        );

        Model::unguard();

        // Fail the request of either of $key or $title is empty
        if (! $key || ! $title) {
            $this->error('We could not save your config, please provide a valid key and title.');

            return 1;
        }

        // Confirm if the user still wants to contine
        if (! $force && ! $this->confirm("Are you sure you want to continue creating the {$key} config option?")) {
            return 0;
        }

        // Store the configuration option
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

    public function getConfigKey(?string $question = null): string
    {
        $question ??= 'What should be the key for this config (snake_cased or whatever you like)?';

        $key = $this->argument('key') ?: $this->ask($question);

        if (Configuration::where('key', $key)->exists()) {
            return $this->getConfigKey('This configuration key already exists and cannot be used, please enter another key.');
        }

        return $key;
    }

    public function getConfigType($default = true, ?string $question = null): string
    {
        $question ??= 'What type of value should this config expect?';

        if ($default) {
            $type = $this->argument('type') ?: $this->choice($question, $this->valid_types);
        } else {
            $type = $this->choice($question, $this->valid_types);
        }

        if (!in_array($type, $this->valid_types)) {
            return $this->getConfigType(false, "The type you provided `{$type}` is not supported, choose from the list.");
        }

        return $type;
    }
}
