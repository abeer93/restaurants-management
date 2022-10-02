<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'                      => $this->faker->name(),
            'weight_in_grams'           => $this->faker->numberBetween(10, 25),
            'available_weight_in_grams' => $this->faker->numberBetween(1, 10),
        ];
    }
}
