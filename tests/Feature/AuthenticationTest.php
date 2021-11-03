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

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());
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

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());
    }

    public function testChangePassword()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        $changePasswordData = [
            'old_password' => 'password',
            'new_password' => '12345678',
            'new_password_confirmation' => '12345678',
        ];

        // Sanctum
        Sanctum::actingAs($administrator);

        // Response
        $this->putJson(route('changePassword', $changePasswordData))
            ->assertStatus(200);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());
    }
}
