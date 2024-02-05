<?php

namespace MadeForYou\Categories;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCategoriesServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-categories';

    public function configurePackage(Package $package): void
    {
        $package->name(self::$name)
            ->hasConfigFile()
            ->hasMigrations([
                '0101012024_create_categories_table',
                '0201012024_create_categorizables_table',
            ])
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->startWith(function (InstallCommand $command) {
                    $command->info('Let\'s install the package');
                })
                    ->publishMigrations()
                    ->publishConfigFile();
            });
    }
}
