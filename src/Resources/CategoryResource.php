<?php

namespace MadeForYou\Categories\Resources;

use Filament\Forms\Components\Section as ComponentsSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MadeForYou\Categories\Models\Category;
use MadeForYou\Categories\Resources\CategoryResource\CreateCategory;
use MadeForYou\Categories\Resources\CategoryResource\EditCategory;
use MadeForYou\Categories\Resources\CategoryResource\ListCategories;
use MadeForYou\Categories\Resources\CategoryResource\ViewCategory;

class CategoryResource extends Resource
{
    /**
     * @var class-string<User>
     */
    protected static ?string $model = Category::class;

    /**
     * The navigation icon for the class.
     */
    protected static ?string $navigationIcon = 'heroicon-o-folder';

    /**
     * Create a form using the given Form object.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ComponentsSection::make('Categorie')
                    ->description('Algemene informatie van de categorie')
                    ->aside()
                    ->columns([
                        'sm' => 1,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Naam')
                            ->required()
                            ->maxLength(255)
                            ->string(),

                        Textarea::make('description')
                            ->label('Omschrijving'),

                        Select::make('parent_id')
                            ->label('Bovenliggende categorie')
                            ->helperText('Categorie waaronder deze categorie hangt.')
                            ->relationship(name: 'parent', titleAttribute: 'name'),
                    ]),
            ]);
    }

    /**
     * Generate a table with specified columns, filters, actions, and bulk actions.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('parent.name')
                    ->label('Hoofdcategorie'),

                TextColumn::make('name')
                    ->label('Naam')
                    ->description(fn (Category $category): ?string => $category->description),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Generate an infolist based on the given Infolist object.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make([
                    'sm' => 1,
                ])
                    ->schema([
                        Section::make('Categorie')
                            ->description('Algemene informatie van de categorie')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Naam'),

                                TextEntry::make('description')
                                    ->label('Omschrijving'),

                                Fieldset::make('Bovenliggende categorie')
                                    ->relationship('parent')
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Naam'),
                                    ]),
                            ])
                            ->aside(),

                        Section::make('Administratie')
                            ->description('Belangrijke gegevens voor de ontwikkelaars van de categorie.')
                            ->aside()
                            ->schema([
                                TextEntry::make('id')
                                    ->label('ID')
                                    ->numeric(),

                                TextEntry::make('created_at')
                                    ->label('Aangemaakt op')
                                    ->dateTime(),

                                TextEntry::make('updated_at')
                                    ->label('Laatst gewijzigd op')
                                    ->since(),

                                TextEntry::make('deleted_at')
                                    ->label('Verwijderd op')
                                    ->dateTime(),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Get an array of pages with their corresponding routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
            'view' => ViewCategory::route('/{record}'),
        ];
    }

    /**
     * Get the Eloquent query builder instance for the model.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * Get the navigation label for the category.
     */
    public static function getNavigationLabel(): string
    {
        return 'Categorieën';
    }

    /**
     * Get the label for the model.
     */
    public static function getModelLabel(): string
    {
        return 'Categorie';
    }

    /**
     * Get the plural label for the model.
     */
    public static function getPluralModelLabel(): string
    {
        return 'Categorieën';
    }

    /**
     * Get the navigation group for the current page.
     */
    public static function getNavigationGroup(): ?string
    {
        return 'Categorisering';
    }
}
