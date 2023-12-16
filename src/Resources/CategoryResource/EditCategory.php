<?php

namespace MadeForYou\Categories\Resources\CategoryResource;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use MadeForYou\Categories\Resources\CategoryResource;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
