<?php

namespace Tests\Feature;

use ToneflixCode\DbConfig\Tests\TestCase;

class CommandsTest extends TestCase
{
    public function test_can_set_config_option_with_artisan(): void
    {
        $this->artisan('dbconfig:create foo "Foo" bar string -f')->execute();

        $this->artisan('dbconfig:set')
            ->expectsQuestion('What do you want to configure?', 'foo')
            ->expectsQuestion('What do you want to set as the value for foo?', 'baz')
            ->assertExitCode(0);
    }

    public function test_can_create_config_option_with_artisan(): void
    {
        $this->artisan('dbconfig:create')
            ->expectsQuestion('What type of value should this config expect?', 'string')
            ->expectsQuestion('What should be the key for this config (snake_cased or whatever you like)?', 'foo')
            ->expectsQuestion('What should be the title for this config?', 'Foo')
            ->expectsQuestion('What should be the default value for this config?', 'bar')
            ->expectsQuestion('Add an optional hint?', 'This is a hint')
            ->expectsConfirmation('Are you sure you want to continue creating the foo config option?', 'yes')
            ->assertExitCode(0);
    }

    public function test_can_show_default_type_list_if_user_manually_a_non_suported_type(): void
    {
        $this->artisan('dbconfig:create foo "Foo" bar stringr')
            ->expectsQuestion('The type you provided `stringr` is not supported, choose from the list.', 'string')
            ->expectsQuestion('Add an optional hint?', 'This is a fix')
            ->expectsConfirmation('Are you sure you want to continue creating the foo config option?', 'yes')
            ->assertExitCode(0);
    }

    public function test_will_ask_for_a_new_key_if_provided_key_is_taken_when_creating_config_with_artisan(): void
    {
        $this->artisan('dbconfig:create foo "Foo" bar string -f')->execute();

        $this->artisan('dbconfig:create')
            ->expectsQuestion('What type of value should this config expect?', 'string')
            ->expectsQuestion('What should be the key for this config (snake_cased or whatever you like)?', 'foo')
            ->expectsQuestion('This configuration key already exists and cannot be used, please enter another key.', 'foobar')
            ->expectsQuestion('What should be the title for this config?', 'Foo')
            ->expectsQuestion('What should be the default value for this config?', 'bar')
            ->expectsQuestion('Add an optional hint?', 'This is a hint')
            ->expectsConfirmation('Are you sure you want to continue creating the foobar config option?', 'yes')
            ->assertExitCode(0);
    }

    public function test_can_delete_a_config_option_with_artisan(): void
    {
        $this->artisan('dbconfig:create foo "Foo" bar string -f')->execute();

        $this->assertEquals(dbconfig('foo'), 'bar');

        $this->artisan('dbconfig:purge foo')
            ->expectsConfirmation('Are you sure you want to delete the `foo` config option?', 'yes')
            ->assertExitCode(0);

        $this->assertNull(dbconfig('foo'));
    }

    public function test_can_cancel_deleting_a_config_option_with_artisan(): void
    {
        $this->artisan('dbconfig:create foo "Foo" bar string -f')->execute();

        $this->assertEquals(dbconfig('foo'), 'bar');

        $this->artisan('dbconfig:purge foo')
            ->expectsConfirmation('Are you sure you want to delete the `foo` config option?', 'no')
            ->assertExitCode(0);

        $this->assertEquals(dbconfig('foo'), 'bar');
    }

    public function test_can_purge_configuration_table_with_artisan(): void
    {
        $this->artisan('dbconfig:create foo "Foo" bar string -f')->execute();

        $this->assertEquals(dbconfig('foo'), 'bar');

        $this->artisan('dbconfig:purge')
            ->expectsConfirmation('You have not provided any configuration option key, do you want to purge the entire `configurations` table?', 'yes')
            ->assertExitCode(0);

        $this->assertEmpty(dbconfig()->toArray());
    }
}
