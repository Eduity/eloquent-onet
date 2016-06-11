<?php

namespace Eduity\EloquentOnet\Tests\Cases;

use Illuminate\Filesystem\ClassFinder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../../vendor/laravel/laravel/bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        // Register our package's service provider
        $app->register(\Eduity\EloquentOnet\EloquentOnetServiceProvider::class);

        return $app;
    }

    /**
     * Setup DB before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default', 'sqlite');
        // $this->app['config']->set('database.connections.sqlite.database', ':memory:');

        if(!File::exists(database_path('database.sqlite'))) {
            File::put(database_path('database.sqlite'), '');

            $this->migrate();
        }
    }

    /**
     * Run package database migrations.
     * Thanks http://stackoverflow.com/questions/27759301/setting-up-integration-tests-in-a-laravel-package
     *
     * @return void
     */
    public function migrate()
    {
        $fileSystem = new Filesystem;
        $classFinder = new ClassFinder;

        $packageMigrations = $fileSystem->files(__DIR__ . "/../../migrations");
        // $laravelMigrations = $fileSystem->files(__DIR__ . "/../../vendor/laravel/laravel/database/migrations");

        // $migrationFiles = array_merge($laravelMigrations, $packageMigrations);

        foreach ($packageMigrations as $file) {
            $fileSystem->requireOnce($file);
            $migrationClass = $classFinder->findClass($file);

            (new $migrationClass)->up();
        }
    }
}
