<?php

namespace MadeForYou\Categories\Resources;

use Exception;
use Filament\Forms\Components\Section as ComponentsSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MadeForYou\Categories\Models\Category;
use MadeForYou\Categories\Resources\CategoryResource\CreateCategory;
use MadeForYou\Categories\Resources\CategoryResource\EditCategory;
use MadeForYou\Categories\Resources\CategoryResource\ListCategories;
use MadeForYou\Categories\Resources\CategoryResource\RelationManagers\ChildrenRelationManager;
use MadeForYou\Categories\Resources\CategoryResource\ViewCategory;

/**
 * Class CategoryResource
 *
 * This class represents a resource for managing categories.
 */
class CategoryResource extends Resource
{
    /**
     * @var class-string<Category>|null
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
            ->schema(components: [
                ComponentsSection::make(heading: 'Categorie')
                    ->description(description: 'Algemene informatie van de categorie')
                    ->aside()
                    ->columns(columns: [
                        'sm' => 1,
                    ])
                    ->schema(components: [
                        TextInput::make(name: 'name')
                            ->label(label: 'Naam')
                            ->required()
                            ->maxLength(length: 255)
                            ->string(),

                        SpatieMediaLibraryFileUpload::make(name: 'poster')
                            ->collection(collection: 'poster')
                            ->label(label: 'Poster afbeelding')
                            ->responsiveImages(),

                        Textarea::make(name: 'description')
                            ->label(label: 'Omschrijving'),

                        Select::make(name: 'parent_id')
                            ->label(label: 'Bovenliggende categorie')
                            ->helperText(text: 'Categorie waaronder deze categorie hangt.')
                            ->relationship(name: 'parent', titleAttribute: 'name'),
                    ]),
            ]);
    }

    /**
     * Generate a table with specified columns, filters, actions, and bulk actions.
     *
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns(components: [
                SpatieMediaLibraryImageColumn::make(name: 'poster')
                    ->collection(collection: 'poster')
                    ->conversion(conversion: 'preview'),

                TextColumn::make(name: 'name')
                    ->label(label: 'Naam')
                    ->description(description: fn (Category $category): ?string => $category->description),

                TextColumn::make(name: 'parent.name')
                    ->label(label: 'Hoofdcategorie'),
            ])
            ->filters(filters: [
                TrashedFilter::make(),

                SelectFilter::make(name: 'parent')
                    ->label(label: 'Bovenliggende categorie')
                    ->options(options: Category::all()->pluck(value: 'name', key: 'id'))
                    ->query(callback: function (Builder $query, array $data) {
                        if (blank($data['value'])) {
                            return $query;
                        }

                        return $query->where(column: 'parent_id', operator: '=', value: $data['value']);
                    }),
            ])
            ->actions(actions: [
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions(actions: [
                BulkActionGroup::make(actions: [
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

                                        TextEntry::make('description')
                                            ->label('Omschrijving'),
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

    /**
     * Get the array of relation managers for the model.
     *
     * @return array The array of relation managers.
     */
    public static function getRelations(): array
    {
        return [
            ChildrenRelationManager::class,
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
