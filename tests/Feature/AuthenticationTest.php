<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testLogin()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        $administratorCredentials = [
            'email' => $administrator->email,
            'password' => 'password' // default password
        ];

        // Response
        $this->postJson(route('login', $administratorCredentials))
            ->assertStatus(200);
    }

    public function testLogout()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        // Sanctum
        Sanctum::actingAs($administrator);

        // Response
        $this->postJson(route('logout'))
            ->assertStatus(200);
    }
}
