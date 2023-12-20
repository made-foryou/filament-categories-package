<?php

namespace MadeForYou\Categories\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 */
class Category extends Model
{
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
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the children categories of this category.
     */
    public function children(): HasMany
    {
        return $this->hasMany(
            related: Category::class,
            foreignKey: 'parent_id'
        );
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        $prefix = config('filament-categories.database.prefix');

        return $prefix . '_categories';
    }
}
