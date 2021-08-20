<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TopicTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testAdminShowTopicList() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Topics
        \App\Models\Topic::factory()->count(5)->create();

        // Response
        $this->actingAs($administrator, 'api')
        ->getJson(route('topics.index'))
        ->assertStatus(200)
        ->assertJsonStructure([[
            'id', 'text',
            'created_at', 'updated_at',
        ]]);
    }

    public function testAdminCreateTopic() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Update Topic Data
        $topicData = [
            'text' => $this->faker->word,
        ];

        // Response
        $this->actingAs($administrator, 'api')
        ->postJson(route('topics.store', $topicData))
        ->assertStatus(201)
        ->assertJsonStructure([
            'id', 'text',
            'created_at', 'updated_at',
        ]);
    }

    public function testAdminShowTopic() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Topics
        $topic = \App\Models\Topic::factory()->create();

        // Response
        $this->actingAs($administrator, 'api')
        ->getJson(route('topics.show', ['topic' => $topic->id]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'text',
            'created_at', 'updated_at',
        ]);
    }

    public function testAdminUpdateTopic() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Topic
        $topic = \App\Models\Topic::factory()->create();

        // Update Topic Data
        $topicData = [
            'topic' => $topic->id,

            'text' => $this->faker->word,
        ];

        // Response
        $this->actingAs($administrator, 'api')
        ->putJson(route('topics.update', $topicData))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'text',
            'created_at', 'updated_at',
        ]);
    }

    public function testAdminDestroyTopic() {
        // Administrator Account
        $administrator = \App\Models\User::factory()->role('ADMINISTRATOR')->create();

        // Topic
        $topic = \App\Models\Topic::factory()->create();

        // Response
        $this->actingAs($administrator, 'api')
        ->deleteJson(route('topics.destroy', ['topic' => $topic->id]))
        ->assertStatus(200);
    }

    public function testCritiqueShowTopicList() {
        // Critique Account
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Topics
        \App\Models\Topic::factory()->count(5)->create();

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->getJson(route('topics.index'))
        ->assertStatus(200)
        ->assertJsonStructure([[
            'id', 'text',
            'created_at', 'updated_at',
        ]]);
    }

    public function testCritiqueShowTopic() {
        // Critique Account
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Topic
        $topic = \App\Models\Topic::factory()->create();

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->getJson(route('topics.show', ['topic' => $topic->id]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'id', 'text',
            'created_at', 'updated_at',
        ]);
    }
}
