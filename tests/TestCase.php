<?php

namespace CafeHistoire\CafeBlog\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use CafeHistoire\CafeBlog\CafeBlogServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'CafeHistoire\\CafeBlog\\Database\\Factories\\' .
                class_basename($modelName) . 'Factory';
        });
    }

    protected function getPackageProviders($app): array
    {
        return [
            CafeBlogServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Load migrations
        $migration = include __DIR__ . '/../database/migrations/2024_01_01_000001_create_cafe_blog_tables.php';
        $migration->up();
    }
}
