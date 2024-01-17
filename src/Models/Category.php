<?php

namespace MadeForYou\Categories\Models;

use Spatie\Image\Enums\Fit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
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
class Category extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

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
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories associated with the current category.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(
            related: Category::class,
            foreignKey: 'parent_id'
        );
    }

    /**
     * Register the media collections for the model.
     *
     * @return void
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
     *
     * @return void
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable(): string
    {
        $prefix = config('filament-categories.database.prefix');

        return $prefix . '_categories';
    }
}
