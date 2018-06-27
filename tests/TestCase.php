<?php

namespace Cammac\LaravelSms\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \Cammac\LaravelSms\LaravelSmsServiceProvider::class,
        ];
    }
}
