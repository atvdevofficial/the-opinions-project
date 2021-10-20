<?php

namespace Database\Factories;

use App\Models\Critique;
use Illuminate\Database\Eloquent\Factories\Factory;

class CritiqueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Critique::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'username' => $this->faker->unique()->word(),
        ];
    }
}
