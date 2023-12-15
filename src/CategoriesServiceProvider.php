<?php

namespace MadeForYou\Categories;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CategoriesServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-categories';

    public function configurePackage(Package $package): void
    {
        $package->name(self::$name)
            ->hasConfigFile()
            ->hasMigrations([
                'create_categories_table',
                'create_categorizables_table',
            ])
            ->runsMigrations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->startWith(function (InstallCommand $command) {
                    $command->info('Let\'s install the package');
                })
                    ->publishConfigFile();
            });
    }
}
