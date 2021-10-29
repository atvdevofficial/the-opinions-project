<?php

namespace Tests\Feature;

use App\Models\Critique;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CritiqueTopicTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCritiqueShowFollowedTopicsList()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Followed Topics
        $critique->followedTopics()->attach(
            Topic::factory()->count(5)->create()->pluck('id')
        );

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->getJson(route('follows.topics.index', ['critique' => $critique->id]))
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

        // Check number of critiques
        $this->assertCount(5, Topic::get());

        // Check number of critique_topics
        $this->assertCount(5, DB::table('critique_topic')->get());
    }

    public function testCritiqueFollowTopic()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // To Follow Topic
        $toFollowTopic = Topic::factory()->create();

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->putJson(route('follows.topics.follow', ['critique' => $critique-> id, 'topic' => $toFollowTopic->id]))
            ->assertStatus(200);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());

        // Check number of critiques
        $this->assertCount(1, Topic::get());

        // Check number of critique_topics
        $this->assertCount(1, DB::table('critique_topic')->get());

        // Check if critique has followed topic
        $hasFollowed = DB::table('critique_topic')->whereCritiqueId($critique->id)->whereTopicId($toFollowTopic->id)->exists();
        $this->assertTrue($hasFollowed);
    }

    public function testCritiqueUnfollowTopic()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // To Follow Topic
        $toFollowTopic = Topic::factory()->create();
        $critique->followedTopics()->attach($toFollowTopic->id);

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->putJson(route('follows.topics.unfollow', ['critique' => $critique-> id, 'topic' => $toFollowTopic->id]))
            ->assertStatus(200);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());

        // Check number of critiques
        $this->assertCount(1, Topic::get());

        // Check number of critique_topics
        $this->assertCount(0, DB::table('critique_topic')->get());

        // Check if critique has unfollowed topic
        $hasFollowed = DB::table('critique_topic')->whereCritiqueId($critique->id)->whereTopicId($toFollowTopic->id)->exists();
        $this->assertFalse($hasFollowed);
    }

    public function testCritiqueUpdateFollowedTopics()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Followed Topics
        $critique->followedTopics()->attach(
            Topic::factory()->count(5)->create()->pluck('id')
        );

        $updatedFollowedTopics = [
            'critique' => $critique->id,
            'topics' => [
                Topic::factory()->create()->id
            ]
        ];

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->putJson(route('follows.topics.update', $updatedFollowedTopics))
            ->assertSuccessful()
            ->assertJsonStructure(['message']);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());

        // Check number of critiques
        $this->assertCount(6, Topic::get());

        // Check number of critique_topics
        $this->assertCount(1, DB::table('critique_topic')->get());
    }
}
