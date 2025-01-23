<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use ToneflixCode\DbConfig\Helpers\Configure;
use ToneflixCode\DbConfig\Tests\TestCase;

class ConfigTest extends TestCase
{
    public function test_can_get_config_value_with_helper(): void
    {
        $this->artisan('dbconfig:create foo "Foo" bar string -f')->execute();

        $this->assertEquals(dbconfig('foo'), 'bar');
    }

    public function test_can_get_config_value_with_configure(): void
    {
        $this->artisan('dbconfig:create foo "Foo" bar string -f')->execute();

        $this->assertEquals(Configure::config('foo'), 'bar');
    }

    public function test_can_set_config_value_with_configure(): void
    {
        $this->artisan('dbconfig:create foo "Foo" bar string -f')->execute();

        Configure::config(['foo' => 'baz']);
        $this->assertEquals(Configure::config('foo'), 'baz');
    }

    public function test_can_get_all_config_as_array_with_configure(): void
    {
        $this->artisan('dbconfig:create foo "Foo" bar string -f')->execute();

        $this->assertIsArray(Configure::config()->toArray());
        $this->assertArrayHasKey('foo', Configure::config());
    }

    public function test_can_get_all_config_as_array_with_helper(): void
    {
        $this->artisan('dbconfig:create foo "Foo" bar string -f')->execute();

        $this->assertIsArray(dbconfig()->toArray());
        $this->assertArrayHasKey('foo', dbconfig());
    }

    public function test_can_set_file_as_a_config_value(): void
    {
        $file = UploadedFile::fake()->image('avatar.jpg');
        $config = Configure::config(['app_logo' => $file]);

        if (str($config['app_logo'])->contains('[1]')) {
            $this->assertStringContainsString('[1]', $config['app_logo']);
        } else {
            $this->assertStringEndsWith('.jpg', $config['app_logo']);
            $this->assertStringContainsString('http://localhost/storage/', $config['app_logo']);
        }
    }
}