<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole() && $this->app->environment('testing')) {
            // Collect migration filenames already managed by this admin project
            $adminMigrationFiles = collect(File::files(database_path('migrations')))
                ->map(fn ($file) => $this->extractMigrationTable($file->getFilename()))
                ->filter()
                ->values()
                ->all();

            // Load only the public migrations that are NOT already handled by the admin
            $publicMigrationsPath = base_path('../digital_St_Michel-public/database/migrations');
            $publicMigrations = collect(File::files($publicMigrationsPath))
                ->filter(function ($file) use ($adminMigrationFiles) {
                    $table = $this->extractMigrationTable($file->getFilename());
                    // Skip if this migration creates a table already defined in admin migrations
                    return ! $table || ! in_array($table, $adminMigrationFiles);
                })
                ->map(fn ($file) => $file->getPathname())
                ->values()
                ->all();

            if (! empty($publicMigrations)) {
                $this->loadMigrationsFrom($publicMigrations);
            }
        }
    }

    /**
     * Extract the table name from a migration filename using naming conventions.
     * e.g. "2026_06_14_000000_create_site_settings_table.php" => "site_settings"
     */
    private function extractMigrationTable(string $filename): ?string
    {
        // Match "create_<table>_table" pattern
        if (preg_match('/create_(\w+)_table/', $filename, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
