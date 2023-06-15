<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => ucfirst(fake()->unique()->words(3, true)),
            'description' => fake()->paragraph(),
            'price'       => fake()->randomFloat(2, 199, 449),
            'image'       => 'https://fakeimg.pl/500x500/cccccc/909090?text=YouCan',
        ];
    }
}
