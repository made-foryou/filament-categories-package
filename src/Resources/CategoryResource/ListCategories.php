<?php

namespace MadeForYou\Categories\Resources\CategoryResource;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use MadeForYou\Categories\Resources\CategoryResource;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
