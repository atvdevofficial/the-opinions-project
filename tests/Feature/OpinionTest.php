<?php

namespace Tests\Feature;

use App\Models\Critique;
use App\Models\Opinion;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OpinionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCritiqueShowOpinionList() {
        // Critique User, Critique, and Opinions
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();
        Opinion::factory()->count(5)->state([
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

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());

        // Check number of opinions
        $this->assertCount(5, Opinion::get());
    }

    public function testCritiqueCreateOpinion() {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Topic
        $topic = Topic::factory()->create();

        // Opinion data
        $opinionData = [
            'critique' => $critique->id,
            'text' => $this->faker->paragraph(),
            'is_public' => true,

            'topics' => [$topic->id,],
        ];

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->postJson(route('critiques.opinions.store', $opinionData))
        ->assertStatus(201)
        ->assertJsonStructure([
            'id', 'text', 'is_public',
            'created_at', 'updated_at',
        ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());

        // Check number of opinions
        $this->assertCount(1, Opinion::get());

        // Check number of topics
        $this->assertCount(1, Topic::get());
    }

    public function testCritiqueShowOpinion() {
        // Critique User, Critique, and Opinion
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();
        $opinion = Opinion::factory()->state(['critique_id' => $critique->id])->create();

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->getJson(route('opinions.show', ['opinion' => $opinion->id]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'text', 'is_public',
            'created_at', 'updated_at',
        ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());

        // Check number of opinions
        $this->assertCount(1, Opinion::get());
    }

    public function testCritiqueUpdateOpinion() {
        // Critique User, Critique, and Opinion
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();
        $opinion = Opinion::factory()->state(['critique_id' => $critique->id])->create();

        // Topic
        $topic = Topic::factory()->create();

        // Opinion data
        $opinionData = [
            'opinion' => $opinion->id,
            'text' => $this->faker->paragraph(),
            'is_public' => true,

            'topics' => [$topic->id,],
        ];

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->putJson(route('opinions.update', $opinionData))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'text', 'is_public',
            'created_at', 'updated_at',
        ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());

        // Check number of opinions
        $this->assertCount(1, Opinion::get());

        // Check number of topics
        $this->assertCount(1, Topic::get());
    }

    public function  testCritiqueDeleteOpinion() {
        // Critique User, Critique, and Opinion
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();
        $opinion = Opinion::factory()->state(['critique_id' => $critique->id])->create();

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->deleteJson(route('opinions.destroy', ['opinion' => $opinion->id]))
        ->assertStatus(200);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());

        // Check number of opinions
        $this->assertCount(0, Opinion::get());
    }
}
