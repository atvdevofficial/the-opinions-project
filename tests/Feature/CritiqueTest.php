<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CritiqueTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testAdminShowCritiqueList() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Critique Critiques
        \App\Models\Critique::factory()->count(3)->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();

        // Response
        $this->actingAs($administrator, 'api')
        ->getJson(route('critiques.index'))
        ->assertStatus(200)
        ->assertJsonStructure([[
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]]);
    }

    public function testAdminCreateCritique() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Critique Critique
        $critiqueData = [
            'email' => 'sample@sample.com',
            'password' => 'password',
            'name' => $this->faker->name
        ];

        // Response
        $this->actingAs($administrator, 'api')
        ->postJson(route('critiques.store', $critiqueData))
        ->assertStatus(201)
        ->assertJsonStructure([
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]);
    }

    public function testAdminShowCritique() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Critique Critique
        $critique = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();

        // Response
        $this->actingAs($administrator, 'api')
        ->getJson(route('critiques.show', ['critique' => $critique->id]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]);
    }

    public function testAdminUpdateCritique() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Critique Critique
        $critique = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();

        // Updated Critique Critique
        $critiqueData = [
            'critique' => $critique->id,

            'email' => 'sample@sample.com',
            'password' => 'password',
            'name' => $this->faker->name
        ];

        // Response
        $this->actingAs($administrator, 'api')
        ->putJson(route('critiques.update', $critiqueData))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]);
    }

    public function testAdminDeleteCritique() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Critique Critique
        $critique = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();

        // Response
        $this->actingAs($administrator, 'api')
        ->getJson(route('critiques.destroy', ['critique' => $critique->id]))
        ->assertStatus(200);
    }

    public function testCritiqueShowCritique() {
        // Critique User & Critique
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->getJson(route('critiques.show', ['critique' => $critique->id]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]);
    }

    public function testCritiqueUpdateCritique() {
        // Critique User & Critique
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Updated Critique Critique
        $critiqueData = [
            'critique' => $critique->id,

            'email' => 'sample@sample.com',
            'password' => 'password',
            'name' => $this->faker->name
        ];

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->putJson(route('critiques.update', $critiqueData))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
        ]);
    }
}
