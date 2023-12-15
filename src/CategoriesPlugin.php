<?php

namespace MadeForYou\Categories;

use Filament\Contracts\Plugin;
use Filament\Panel;

class Categorieslugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return CategoriesServiceProvider::$name;
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
