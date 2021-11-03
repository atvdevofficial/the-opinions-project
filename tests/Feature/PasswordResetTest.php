<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testPasswordResetRequest() {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        $passwordResetData = [
            'email' => $administrator->email,
        ];

        // Sanctum
        Sanctum::actingAs($administrator);

        // Response
        $this->postJson(route('resetPassword', $passwordResetData))
            ->assertStatus(200);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of password resets
        $this->assertCount(1, DB::table('password_resets')->get());
    }

    public function testPasswordReset() {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();
        DB::table('password_resets')->insert([
            'email' => $administrator->email,
            'token' => 'sampletoken'
        ]);

        $passwordResetData = [
            'token' => 'sampletoken',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        // Sanctum
        Sanctum::actingAs($administrator);

        // Response
        $this->putJson(route('resetPassword.reset', $passwordResetData))
            ->assertStatus(200);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of password resets
        $this->assertCount(0, DB::table('password_resets')->get());

        // Check hash
        Hash::check('12345678', User::find(1)->password);
    }
}
