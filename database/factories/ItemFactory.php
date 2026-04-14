<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'category_id' => $this->faker->numberBetween(1, 2),
            'price' => $this->faker->randomFloat(2, 1000, 100000),
            'description' => $this->faker->text(),
            'img' => $this->faker->randomElement([
                'https://images.unsplash.com/photo-1579871494447-9811cf80d66c', // Sushi Nigiri
                'https://images.unsplash.com/photo-1553621042-f6e147245754', // Sushi Roll
                'https://images.unsplash.com/photo-1569718212165-3a8278d5f624', // Ramen Bowl
                'https://images.unsplash.com/photo-1591814447921-7cf7a5c6adbc', // Miso Ramen
                'https://images.unsplash.com/photo-1582450871972-ab5ca641643d', // Gyoza
                'https://images.unsplash.com/photo-1558985250-27a406d64cb3', // Takoyaki
            ]),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
