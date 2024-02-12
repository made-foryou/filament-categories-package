<?php

namespace MadeForYou\Categories\Database\Factories;

use MadeForYou\Categories\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'content' => '[]',
        ];
    }
}
