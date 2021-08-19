<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OpinionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCritiqueShowOpinionList() {
        // Critique User, Critique, and Opinions
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();
        \App\Models\Opinion::factory()->count(5)->state([
            'critique_id' => $critique->id
        ])->create();

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->getJson(route('critiques.opinions.index', ['critique' => $critique->id]))
        ->assertStatus(200)
        ->assertJsonStructure([[
            'id', 'text', 'is_public',
            'created_at', 'updated_at',
        ]]);
    }

    public function testCritiqueCreateOpinion() {
        // Critique User & Critique
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Opinion data
        $opinionData = [
            'critique' => $critique->id,
            'text' => $this->faker->paragraph(),
            'is_public' => true
        ];

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->postJson(route('critiques.opinions.store', $opinionData))
        ->assertStatus(201)
        ->assertJsonStructure([
            'id', 'text', 'is_public',
            'created_at', 'updated_at',
        ]);
    }

    public function testCritiqueShowOpinion() {
        // Critique User, Critique, and Opinion
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();
        $opinion = \App\Models\Opinion::factory()->state(['critique_id' => $critique->id])->create();

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->getJson(route('opinions.show', ['opinion' => $opinion->id]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'text', 'is_public',
            'created_at', 'updated_at',
        ]);
    }

    public function testCritiqueUpdateOpinion() {
        // Critique User, Critique, and Opinion
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();
        $opinion = \App\Models\Opinion::factory()->state(['critique_id' => $critique->id])->create();

        // Opinion data
        $opinionData = [
            'opinion' => $opinion->id,
            'text' => $this->faker->paragraph(),
            'is_public' => true
        ];

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->putJson(route('opinions.update', $opinionData))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'text', 'is_public',
            'created_at', 'updated_at',
        ]);
    }

    public function  testCritiqueDeleteOpinion() {
        // Critique User, Critique, and Opinion
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();
        $opinion = \App\Models\Opinion::factory()->state(['critique_id' => $critique->id])->create();

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->deleteJson(route('opinions.destroy', ['opinion' => $opinion->id]))
        ->assertStatus(200);
    }
}
