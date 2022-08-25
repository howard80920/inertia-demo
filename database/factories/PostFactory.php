<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realText(10),
            'content' => $this->faker->realText(1000),
            'visits' => $this->faker->numberBetween(10, 1000),
            'published' => $this->faker->boolean(80),
        ];
    }
}
