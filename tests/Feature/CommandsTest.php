<?php

namespace Tests\Feature;

use ToneflixCode\DbConfig\Tests\TestCase;

class CommandsTest extends TestCase
{
    public function test_can_set_config_option_with_artisan(): void
    {
        $this->artisan('app:config-create foo "Foo" bar string -f')->execute();

        $this->artisan('app:config-set')
            ->expectsQuestion('What do you want to configure?', 'foo')
            ->expectsQuestion('What do you want to set as the value for foo?', 'baz')
            ->assertExitCode(0);
    }

    public function test_can_create_config_option_with_artisan(): void
    {
        $this->artisan('app:config-create')
            ->expectsQuestion('What type of value should this config expect?', 'string')
            ->expectsQuestion('What should be the key for this config (snake_cased or whatever you like)?', 'foo')
            ->expectsQuestion('What should be the title for this config?', 'Foo')
            ->expectsQuestion('What should be the default value for this config?', 'bar')
            ->expectsQuestion('Add an optional hint?', 'This is a hint')
            ->expectsConfirmation('Are you sure you want to continue creating the foo config option?', 'yes')
            ->assertExitCode(0);
    }
}