# Laravel DbConfig (Database Configuration)

[![Test & Lint](https://github.com/toneflix/laravel-dbconfig/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/toneflix/laravel-dbconfig/actions/workflows/run-tests.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/toneflix-code/laravel-dbconfig.svg)](https://packagist.org/packages/toneflix-code/laravel-dbconfig)
[![Total Downloads](https://img.shields.io/packagist/dt/toneflix-code/laravel-dbconfig.svg)](https://packagist.org/packages/toneflix-code/laravel-dbconfig)
[![License](https://img.shields.io/packagist/l/toneflix-code/laravel-dbconfig.svg)](https://packagist.org/packages/toneflix-code/laravel-dbconfig)
[![PHP Version Require](https://img.shields.io/packagist/dependency-v/toneflix-code/laravel-dbconfig/php)](https://packagist.org/packages/toneflix-code/laravel-dbconfig)
[![codecov](https://codecov.io/gh/toneflix/laravel-dbconfig/graph/badge.svg?token=2O7aFulQ9P)](https://codecov.io/gh/toneflix/laravel-dbconfig)

<!-- ![GitHub Actions](https://github.com/toneflix/laravel-dbconfig/actions/workflows/run-tests.yml/badge.svg) -->

A Laravel package that allows you to configure your app using database entries with support for Arrays and files.

## Features

-   Support for single and multiple file uploads.
-   Has `dbconfig()` helper function.
-   Support for custom tables.
-   Create configurations with `php artisan dbconfig:create` command.
-   Support for Arrays.
-   Configurations can be dbconfig:d.
-   Update config with `php artisan dbconfig:set` command.

## Installation

You can install the package via composer:

```bash
composer require toneflix-code/laravel-dbconfig
```

### Package Discovery

Laravel automatically discovers and publishes service providers but optionally after you have installed Laravel Fileable, open your Laravel config file if you use Laravel below 11, `config/app.php` and add the following lines.

In the $providers array add the service providers for this package.

```php
ToneflixCode\DbConfig\DbConfigServiceProvider::class
```

If you use Laravel >= 11, open your `bootstrap/providers.php` and the above line to the array.

```php
return [
    ToneflixCode\DbConfig\DbConfigServiceProvider::class,
];
```

### Asset Publishing

To customize migrations, publish the migration files with:

```bash
php artisan vendor:publish --tag="dbconfig-migrations"
```

To customize configuration options, publish the config file with:

```bash
php artisan vendor:publish --tag="dbconfig"
```

### Custom Tables

If you need to customize the names of the tables used, edit the `table` property of the `laravel-dbconfig` configuration file, setting the table names according to your requirement.

### Migration

Once you're doing with initial setup, run `php artisan migrate` to migrate your database, when you have done this, then the library is ready for use.

## Usage

### Creating Configuration Options

#### Artisan Command

To create configuration options, you can use the [dbconfig:create Artisan command](#dbconfig-create)

#### Database seeding

If you have multple options and do not want to add them one after the other, you can publish the `factory` and `seeder` then modify the seeder, adding in all the option you require in the array and seeding your database as usual.

1. Publish `factory` and `seeder` by running `php artisan vendor:publish --tag dbconfig-data`.
2. Modify seeder.
3. Seed your database by running `php artisan db:seed ConfigurationSeeder`.

### Accessing configuration

To access your saved configuration, you can call the `config` helper method on the `Configure` class.

```php
use ToneflixCode\DbConfig\Helpers\Configure;

dd(Configure::config(key: 'app_name'));
```

Or use the flat helper function.

```php
dd(dbconfig(key: 'app_name'));
```

### Accessing all configuration option

If you do not provide a key when calling `Configure::config()` or `dbconfig()` an instance of `Illuminate\Support\Collection` will be returned with all available configuration options as [key => value] pair collection.

```php
use ToneflixCode\DbConfig\Helpers\Configure;

dd(Configure::config());
```

Or

```php
dd(dbconfig());
```

### Updating Configurations

Passing an array to the `dbconfig` function or the `config` method will imply that you want to update the configuration option.

```php
use ToneflixCode\DbConfig\Helpers\Configure;

Configure::config(key: ['app_name' => 'Toneflix']);
```

Or

```php
dbconfig(key: ['app_name' => 'Toneflix']);
```

In either case, the calls will return an instance of `\Illuminate\Support\Collection` with the current state of the config.

## Supported Data types

When creating configuration options, you will be required to provide a value for the `type` attribute, which is used to determine what kind of data to expect from the config `value`.

This library supports the following data types as values:

1. `string`
2. `bool`
3. `int`
4. `float`
5. `json`
6. `file`

For convinience and in order to provide flexibility, we will also map the following `type` config values to their applicable data types:

1. \[`textarea`, `text`\]: `string`
2. \[`number`, `integer`\]: `int`
3. \[`boolean`\]: `bool`
4. \[`file`\]: array of `file`
5. The `json` data type will automatically be wrapped with an `\Illuminate\Support\Collection` instance.

## Secret Data

When creating your options, if you set the `secret` attribute to `true`, the value will automatically be masked whenever it is accessed.

To allow secret values to be visible, when you call the `Configure::config()` or `dbconfig()` methods, set the `loadSecret` argument to true, this will ensure that, even secret values are visible.

```php
use ToneflixCode\DbConfig\Helpers\Configure;

Configure::config(key: ['app_name' => 'Toneflix'], loadSecret: true);
```

Or

```php
dbconfig(key: ['app_name' => 'Toneflix'], loadSecret: true);
```

## Files

Configuration options of `type` `file` will be uploaded to your configured storage driver and a direct link to the file will be generated and passed as the configuration `value`.

Whe the configuration type is `files`, the files will be uploaded and you will get an `\Illuminate\Support\Collection` of direct link urls to all the uploaded files.

### Storage path

The library uses `toneflix-code/laravel-fileable` for storing files, you can configure the upload path and othe options by modifying the `upload_collection` property of the `laravel-dbconfig` configuration file or add a `dbconfig` collection to the `toneflix-fileable` configuration.

### Saving configuration with file values

To save a configuration with a file value, simply pass an instance of `Illuminate\Http\UploadedFile` as a value when setting the configuration, the lirary takes care of the rest.

```php
use ToneflixCode\DbConfig\Helpers\Configure;

Configure::config(key: ['logo' => request()->file('logo')]);
```

Or

```php
dbconfig(key: ['logo' => request()->file('logo')]);
```

## Artisan Commands

The library ships with 4 artisan commands provided to to make working with the library a little bit less straining, maybe, but let's get to it.

### `dbconfig:create`

The `dbconfig:create` command allows you to create new configuration options, to use, run it like in the examples below.

```bash
php artisan dbconfig:create
```

You will be presented with interactive questions to help you create your config option, alternatively, you can also pass the option definition directly to the command to create without prompts:

```bash
php artisan dbconfig:create [options] [--] [<key> [<title> [<value> [<type> [<hint> [<choices>...]]]]]] [--group [--secret [--cols [--max [--force]]]]]
```

When you run the `dbconfig:create` passing all options directly, you will still be required to confirm, where you do not want to to be required to confirm, you can pass the `--force` option, which will run the command without prompts.

### `dbconfig:purge`

The `dbconfig:purge` command allows you to purge or remove configuration options from the database.

```bash
php artisan dbconfig:create [<key>]
```

Running the command without arguments will cause the entire configuration table to be truncated but if you wish to only remove a particular key from the table, simply pass the key argument.

### `dbconfig:set`

The `dbconfig:set` command allows you to set or update the value of a configuration option.

```bash
php artisan dbconfig:set
```

You will be presented with interactive questions to help you update the value of your config option, alternatively, you can also pass the key and value arguments directly to the command to update without the prompts:

```bash
php artisan dbconfig:set [<option> [<value>]]
```

### `dbconfig:show`

The `dbconfig:show` command will display all of the values for the database configurations.

```bash
php artisan dbconfig:show
```

And that's it folks, happy coding...

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## TODO

-   [x] Add dbconfig: status to about command.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email code@toneflix.com.ng instead of using the issue tracker.

## Credits

-   [Toneflix Code](https://github.com/toneflix)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
