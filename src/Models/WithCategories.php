<?php

namespace MadeForYou\Categories\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * This trait provides methods for working with categories in a model.
 */
trait WithCategories
{
    /**
     * Get the category associated with the model.
     *
     * @return MorphToMany
     */
    public function category(): MorphToMany
    {
        return $this->morphToMany(
            Category::class,
            'categorizable',
            config('filament-categories.database.prefix') . '_categorizables',
        )->limit(1);
    }

    /**
     * Get the categories associated with the model.
     *
     * @return MorphToMany
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(
            Category::class,
            'categorizable',
            config('filament-categories.database.prefix') . '_categorizables',
        );
    }
}
