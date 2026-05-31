<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'category_id' => \App\Models\Category::factory(),
            'image' => $this->faker->imageUrl(640, 480, 'food', true),
            'stock' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
