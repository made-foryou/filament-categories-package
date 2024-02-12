<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use MadeForYou\Categories\Models\Category;
use MadeForYou\News\Models\Post;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;

it('has a name', function () {
    $category = Category::factory()->createOne();

    expect($category->name)->toBeString();
});

it('can have a description', function () {
    $category = Category::factory()->createOne();

    expect($category->description)->toBeString();

    $category->description = null;
    $category->save();

    expect($category->description)->toBeNull();
});

it('can have a parent category', function () {
    $category = Category::factory()->createOne();
    $parent = Category::factory()->createOne();

    $category->parent()->associate($parent);

    expect($category->parent)
        ->toBeInstanceOf(Category::class)
        ->and($category->parent->id)
        ->toBe($parent->id);
});

it('can have children', function () {
    $parent = Category::factory()->createOne();

    Category::factory()
        ->count(6)
        ->create([
            'parent_id' => $parent->id,
        ]);

    $parent->refresh();

    expect($parent->children)
        ->toBeInstanceOf(Collection::class)
        ->and($parent->children->count())
        ->toBe(6);
});

it('can have posts', function () {
    $category = Category::factory()->createOne();

    Post::factory()->count(20)->create([
        'category_id' => $category->id,
    ]);

    expect($category->posts)
        ->toBeInstanceOf(Collection::class)
        ->and($category->posts->count())
        ->toBe(20);
});

it('can generate a url', function () {
    $category = Category::factory()
        ->createOne();

    expect($category->getUrl())
        ->toBe(Str::slug($category->name));

    $parent = Category::factory()
        ->createOne();

    $category->parent()->associate($parent);

    $segments = collect([]);
    $segments->push(Str::slug($parent->name));
    $segments->push(Str::slug($category->name));

    expect($category->getUrl())
        ->toBe($segments->join(DIRECTORY_SEPARATOR));
});

it('has a route name', function () {
    $category = Category::factory()
        ->createOne();

    expect($category->getRouteName())
        ->toBe('category.' . $category->id);
});

it('registers the media collections', function () {
    $category = Category::factory()
        ->createOne();

    $category->registerMediaCollections();

    expect((count($category->mediaCollections) === 0))
        ->toBeFalse()
        ->and($category->getMediaCollection('poster'))
        ->toBeInstanceOf(MediaCollection::class)
        ->and($category->getMediaCollection('poster')->name)
        ->toBe('poster');
});
