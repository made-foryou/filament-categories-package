<?php

namespace MadeForYou\Categories\Models;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MadeForYou\Helpers\Enums\FilamentPackage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use MadeForYou\Categories\Database\Factories\CategoryFactory;
use MadeForYou\Helpers\Enums\FilamentPackage;
use MadeForYou\Helpers\Facades\Packages;
use MadeForYou\News\Models\Post;
use MadeForYou\Routes\Contracts\HasRoute;
use MadeForYou\Routes\Models\WithRoute;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * ## Category model
 * ----
 *
 * @property-read int $id
 * @property string $name
 * @property string $description
 * @property array $content
 * @property-read null|int $parent_id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read ?Carbon $deleted_at
 * @property-read ?Category $parent
 * @property-read Collection<Category> $children
 */
class Category extends Model implements HasMedia, HasRoute
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;
    use WithRoute;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'array',
        'parent_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'content',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'content' => '{}',
    ];

    /**
     * Get the parent category of the current category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories associated with the current category.
     */
    public function children(): HasMany
    {
        return $this->hasMany(
            related: Category::class,
            foreignKey: 'parent_id'
        );
    }

    /**
     * Retrieve the posts related to this category.
     *
     * @throws Exception If the news package is not being used within the project.
     * @return HasMany
     */
    public function posts(): HasMany
    {
        if (! Packages::uses(FilamentPackage::News)) {
            throw new Exception('The news package is not being used within the project.');
        }

        return $this->hasMany(related: Post::class, foreignKey: 'category_id');
    }

    /**
     * Register the media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('poster')
            ->singleFile()
            ->withResponsiveImages();
    }

    /**
     * Register media conversions for the given media.
     *
     * @param  Media|null  $media  The media to register conversions for. Default is null.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->queued();
    }

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        $prefix = config('filament-categories.database.prefix');

        return $prefix . '_categories';
    }

    #[\Override]
    public function getUrl(): string
    {
        $segments = collect([]);

        if ($this->parent !== null) {
            $segments->push($this->parent->getUrl());
        }

        $segments->push(Str::slug($this->name));

        return $segments->join(DIRECTORY_SEPARATOR);
    }

    #[\Override]
    public function getRouteName(): string
    {
        return 'category.' . $this->id;
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
