<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nameEn' => ucfirst($this->faker->unique()->word),
            'nameAr' => ucfirst($this->faker->unique()->word),
        ];
    }

    public function withProducts(int $count = 10): static
    {
        return $this->has(Product::factory()->count($count), 'products');
    }
}
