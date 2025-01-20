<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use ToneflixCode\DbConfig\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function loadEnv()
{
    // Load the .env file
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/..');
    $dotenv->safeLoad();
}

if (! function_exists('json_validate')) {
    function json_validate($json, $depth = 512, $flags = 0)
    {
        return json_validate($json, $depth, $flags);

        if (! is_string($json)) {
            return false;
        }

        try {
            json_decode($json, false, $depth, $flags | JSON_THROW_ON_ERROR);

            return true;
        } catch (\JsonException $e) {
            return false;
        }
    }
}

function present(callable $method)
{
    try {
        return $method();
    } catch (\Throwable $th) {
        return $th->getMessage().' on line '.$th->getLine();
    }
}
