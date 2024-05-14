<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isFood = $this->faker->boolean;
        $foodNames = ['Pizza', 'Burger', 'Pasta', 'Salad', 'Sushi']; // Sample food names
        $beverageNames = ['Coffee', 'Tea', 'Smoothie', 'Juice', 'Soda']; // Sample beverage names
        return [
            'name' => $this->faker->randomElement($isFood ? $foodNames : $beverageNames),
            'description' => $this->faker->sentence(mt_rand(10, 20)),
            'price' => $this->faker->numberBetween(4000, 10000) * 5,
            'category_id' => $isFood ? 1 : mt_rand(2, 4)
        ];
    }
}
