<?php

namespace MadeForYou\Categories\Tests;

use MadeForYou\News\NewsPlugin;
use Filament\FilamentServiceProvider;
use MadeForYou\News\NewsServiceProvider;
use MadeForYou\Helpers\HelpersServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use MadeForYou\Categories\FilamentCategoriesServiceProvider;
use MadeForYou\Routes\RoutesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected array $migrations = [
        '0101012024_create_categories_table',
        '0201012024_create_categorizables_table',
    ];

    protected array $vendorMigrations = [
        'create_routes_table',
        '0201012024_create_posts_table',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'MadeForYou\\News\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            HelpersServiceProvider::class,
            RoutesServiceProvider::class,
            FilamentCategoriesServiceProvider::class,
            NewsServiceProvider::class,
        ];
    }

    protected function migrate(): void
    {
        foreach ($this->migrations as $migration) {

            if (! file_exists($this->getMigrationPath($migration))) {
                continue;
            }

            $migration = include $this->getMigrationPath($migration);

            // Run the migration
            $migration->up();

        }

        foreach ($this->vendorMigrations as $vendorMigration) {

            $vendorMigrationFilePath = __DIR__ . '/migrations/'
                . $vendorMigration . '.php.stub';

            if (! file_exists($vendorMigrationFilePath)) {
                continue;
            }

            $migration = include $vendorMigrationFilePath;

            $migration->up();

        }
    }

    protected function getMigrationPath(string $migrationFile): string
    {
        return __DIR__ . '/../database/migrations/' . $migrationFile
            . '.php.stub';
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        $this->migrate();
    }
}
