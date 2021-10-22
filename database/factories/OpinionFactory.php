<?php

namespace Database\Factories;

use App\Models\Opinion;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpinionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Opinion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dateTime = $this->faker->dateTime;

        return [
            'text' => $this->faker->paragraph(),
            'is_public' => true,
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ];
    }
}
