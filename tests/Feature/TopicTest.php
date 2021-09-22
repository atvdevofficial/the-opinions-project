<?php

namespace Tests\Feature;

use App\Models\Critique;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TopicTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testAdminShowTopicList()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        // Topics
        Topic::factory()->count(5)->create();

        // Response
        $this->actingAs($administrator, 'api')
            ->getJson(route('topics.index'))
            ->assertStatus(200)
            ->assertJsonStructure([[
                'id', 'text',
                'created_at', 'updated_at',
            ]]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of topics
        $this->assertCount(5, Topic::get());
    }

    public function testAdminCreateTopic()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

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

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of topics
        $this->assertCount(1, Topic::get());
    }

    public function testAdminShowTopic()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        // Topics
        $topic = Topic::factory()->create();

        // Response
        $this->actingAs($administrator, 'api')
            ->getJson(route('topics.show', ['topic' => $topic->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'text',
                'created_at', 'updated_at',
            ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of topics
        $this->assertCount(1, Topic::get());
    }

    public function testAdminUpdateTopic()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        // Topic
        $topic = Topic::factory()->create();

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

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of topics
        $this->assertCount(1, Topic::get());

        // Check if topic was updated
        $topic = Topic::findOrFail($topic->id);
        $this->assertEquals($topicData['text'], $topic->text);
    }

    public function testAdminDestroyTopic()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        // Topic
        $topic = Topic::factory()->create();

        // Response
        $this->actingAs($administrator, 'api')
            ->deleteJson(route('topics.destroy', ['topic' => $topic->id]))
            ->assertStatus(200);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of topics
        $this->assertCount(0, Topic::get());
    }

    public function testCritiqueShowTopicList()
    {
        // Critique Account
        $critiqueUser = User::factory()
            ->state(['role' => 'CRITIQUE'])
            ->has(Critique::factory())
            ->create();

        // Topics
        Topic::factory()->count(5)->create();

        // Response
        $this->actingAs($critiqueUser, 'api')
            ->getJson(route('topics.index'))
            ->assertStatus(200)
            ->assertJsonStructure([[
                'id', 'text',
                'created_at', 'updated_at',
            ]]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());
    }

    public function testCritiqueShowTopic()
    {
        // Critique Account
        $critiqueUser = User::factory()
            ->state(['role' => 'CRITIQUE'])
            ->has(Critique::factory())
            ->create();

        // Topic
        $topic = Topic::factory()->create();

        // Response
        $this->actingAs($critiqueUser, 'api')
            ->getJson(route('topics.show', ['topic' => $topic->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'text',
                'created_at', 'updated_at',
            ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());
    }
}
