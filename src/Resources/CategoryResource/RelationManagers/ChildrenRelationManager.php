<?php

namespace MadeForYou\Categories\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use MadeForYou\Categories\Models\Category;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Actions\DissociateBulkAction;

class ChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make(name: 'name')
                    ->label(label: 'Naam')
                    ->required()
                    ->maxLength(length: 255),

                Textarea::make(name: 'description')
                    ->label(label: 'Omschrijving'),

                Select::make(name: 'parent_id')
                    ->label(label: 'Hoofdcategorie')
                    ->relationship(name: 'parent', titleAttribute: 'name')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(heading: 'Sub categorieën')
            ->description(description: 'De categorieën die onder deze categorie hangen.')
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label(label: 'Naam')
                    ->description(fn (Category $category): ?string => $category->description),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                DissociateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    DissociateBulkAction::make(),
                ]),
            ]);
    }
}
