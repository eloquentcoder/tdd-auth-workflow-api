<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->word(),
            'description' => fake()->paragraph(),
            'price' => fake()->randomNumber(),
            'featured_image' => fake()->imageUrl(),
            'is_featured' => fake()->boolean(),
            'product_id' => 'kfjdfjdfdfkjd',
            'tags' => fake()->paragraph(),
            'other_images' => fake()->paragraph()
        ];
    }

    public function featured()
    {
        return $this->state(function(array $arributes) {
            return [
                'is_featured' => 'true'
            ];
        });
    }
}
