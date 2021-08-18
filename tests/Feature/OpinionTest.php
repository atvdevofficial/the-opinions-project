<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OpinionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCritiqueShowOpinionList() {
        // Critique User, Profile, and Opinions
        $critique = \App\Models\User::factory()->role('CRITIQUE')->create();
        $profile = \App\Models\Profile::factory()->state(['user_id' => $critique->id])->create();
        \App\Models\Opinion::factory()->count(5)->state([
            'profile_id' => $profile->id
        ])->create();

        // Response
        $this->actingAs($critique, 'api')
        ->getJson(route('profiles.opinions.index', ['profile' => $profile->id]))
        ->assertStatus(200)
        ->assertJsonStructure([[
            'id', 'text', 'is_public',
            'created_at', 'updated_at',
        ]]);
    }

    public function testCritiqueCreateOpinion() {
        // Critique User & Profile
        $critique = \App\Models\User::factory()->role('CRITIQUE')->create();
        $profile = \App\Models\Profile::factory()->state(['user_id' => $critique->id])->create();

        // Opinion data
        $opinionData = [
            'profile' => $profile->id,
            'text' => $this->faker->paragraph(),
            'is_public' => true
        ];

        // Response
        $this->actingAs($critique, 'api')
        ->postJson(route('profiles.opinions.store', $opinionData))
        ->assertStatus(201)
        ->assertJsonStructure([
            'id', 'text', 'is_public',
            'created_at', 'updated_at',
        ]);
    }

    public function testCritiqueShowOpinion() {
        // Critique User, Profile, and Opinion
        $critique = \App\Models\User::factory()->role('CRITIQUE')->create();
        $profile = \App\Models\Profile::factory()->state(['user_id' => $critique->id])->create();
        $opinion = \App\Models\Opinion::factory()->state(['profile_id' => $profile->id])->create();

        // Response
        $this->actingAs($critique, 'api')
        ->getJson(route('opinions.show', ['opinion' => $opinion->id]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'text', 'is_public',
            'created_at', 'updated_at',
        ]);
    }

    public function testCritiqueUpdateOpinion() {
        // Critique User, Profile, and Opinion
        $critique = \App\Models\User::factory()->role('CRITIQUE')->create();
        $profile = \App\Models\Profile::factory()->state(['user_id' => $critique->id])->create();
        $opinion = \App\Models\Opinion::factory()->state(['profile_id' => $profile->id])->create();

        // Opinion data
        $opinionData = [
            'opinion' => $opinion->id,
            'text' => $this->faker->paragraph(),
            'is_public' => true
        ];

        // Response
        $this->actingAs($critique, 'api')
        ->putJson(route('opinions.update', $opinionData))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'text', 'is_public',
            'created_at', 'updated_at',
        ]);
    }

    public function  testCritiqueDeleteOpinion() {
        // Critique User, Profile, and Opinion
        $critique = \App\Models\User::factory()->role('CRITIQUE')->create();
        $profile = \App\Models\Profile::factory()->state(['user_id' => $critique->id])->create();
        $opinion = \App\Models\Opinion::factory()->state(['profile_id' => $profile->id])->create();

        // Response
        $this->actingAs($critique, 'api')
        ->deleteJson(route('opinions.destroy', ['opinion' => $opinion->id]))
        ->assertStatus(200);
    }
}
