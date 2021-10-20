<?php

namespace Tests\Feature;

use App\Models\Critique;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testRegistration()
    {
        // Critique Critique
        $critiqueData = [
            'email' => 'sample@sample.com',
            'password' => 'password',
            'name' => $this->faker->name,
            'username' => $this->faker->unique()->word
        ];

        // Response
        $this->postJson(route('register', $critiqueData))
            ->assertStatus(200);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());
    }
}
