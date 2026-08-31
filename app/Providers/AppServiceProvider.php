<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Self-healing: ensure the app can boot without install.sh
        if (!File::exists(database_path('database.sqlite'))) {
            File::ensureDirectoryExists(database_path(), 0755);
            File::put(database_path('database.sqlite'), '');
        }

        // Generate APP_KEY if missing (defensive — install.sh does this too)
        if (empty(env('APP_KEY'))) {
            Artisan::call('key:generate', ['--force' => true]);
        }

        // Run migrations if needed
        try {
            $migrator = app('migrator');
            if (!$migrator->repositoryExists()) {
                Artisan::call('migrate', ['--force' => true]);
                // Seed after first migration
                if (!\App\Models\Client::exists()) {
                    Artisan::call('db:seed', ['--force' => true]);
                }
            } else {
                // Check if we need to run pending migrations
                $files = $migrator->getMigrationFiles(database_path('migrations'));
                $ran = $migrator->getRepository()->getRan();
                if (count($files) > count($ran)) {
                    Artisan::call('migrate', ['--force' => true]);
                }
                // Seed if DB is empty
                if (!\App\Models\Client::exists()) {
                    Artisan::call('db:seed', ['--force' => true]);
                }
            }
        } catch (\Throwable $e) {
            // If the table doesn't exist yet, run migrations
            if (str_contains($e->getMessage(), 'no such table')) {
                Artisan::call('migrate', ['--force' => true]);
                if (!\App\Models\Client::exists()) {
                    Artisan::call('db:seed', ['--force' => true]);
                }
            }
        }
    }
}
