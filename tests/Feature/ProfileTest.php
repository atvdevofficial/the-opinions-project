<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testAdminShowProfileList() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Critique Profiles
        \App\Models\Profile::factory()->count(3)->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();

        // Response
        $this->actingAs($administrator, 'api')
        ->getJson(route('profiles.index'))
        ->assertStatus(200)
        ->assertJsonStructure([[
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]]);
    }

    public function testAdminCreateProfile() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Critique Profile
        $profileData = [
            'email' => 'sample@sample.com',
            'password' => 'password',
            'name' => $this->faker->name
        ];

        // Response
        $this->actingAs($administrator, 'api')
        ->postJson(route('profiles.store', $profileData))
        ->assertStatus(201)
        ->assertJsonStructure([
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]);
    }

    public function testAdminShowProfile() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Critique Profile
        $profile = \App\Models\Profile::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();

        // Response
        $this->actingAs($administrator, 'api')
        ->getJson(route('profiles.show', ['profile' => $profile->id]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]);
    }

    public function testAdminUpdateProfile() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Critique Profile
        $profile = \App\Models\Profile::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();

        // Updated Critique Profile
        $profileData = [
            'profile' => $profile->id,

            'email' => 'sample@sample.com',
            'password' => 'password',
            'name' => $this->faker->name
        ];

        // Response
        $this->actingAs($administrator, 'api')
        ->putJson(route('profiles.update', $profileData))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]);
    }

    public function testAdminDeleteProfile() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Critique Profile
        $profile = \App\Models\Profile::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();

        // Response
        $this->actingAs($administrator, 'api')
        ->getJson(route('profiles.destroy', ['profile' => $profile->id]))
        ->assertStatus(200);
    }

    public function testCritiqueShowProfile() {
        // Critique User & Profile
        $critique = \App\Models\User::factory()->role('CRITIQUE')->create();
        $profile = \App\Models\Profile::factory()->state(['user_id' => $critique->id])->create();

        // Response
        $this->actingAs($critique, 'api')
        ->getJson(route('profiles.show', ['profile' => $profile->id]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]);
    }

    public function testCritiqueUpdateProfile() {
        // Critique User & Profile
        $critique = \App\Models\User::factory()->role('CRITIQUE')->create();
        $profile = \App\Models\Profile::factory()->state(['user_id' => $critique->id])->create();

        // Updated Critique Profile
        $profileData = [
            'profile' => $profile->id,

            'email' => 'sample@sample.com',
            'password' => 'password',
            'name' => $this->faker->name
        ];

        // Response
        $this->actingAs($critique, 'api')
        ->putJson(route('profiles.update', $profileData))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]);
    }
}
