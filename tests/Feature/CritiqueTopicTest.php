<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CritiqueTopicTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCritiqueShowFollowedTopicsList() {
        // Critique User & Critique
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Followed Topics
        $critique->followedTopics()->attach(
            \App\Models\Topic::factory()->count(5)->create()->pluck('id')
        );

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->getJson(route('follows.topics.index'))
        ->assertStatus(200)
        ->assertJsonStructure([[
            'id', 'text',
            'created_at', 'updated_at',
        ]]);
    }

    public function testCritiqueFollowTopic() {
        // Critique User & Critique
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // To Follow Topic
        $toFollowTopic = \App\Models\Topic::factory()->create();

        // Query
        $hasFollowed = DB::table('critique_topic')->whereCritiqueId($critique->id)->whereTopicId($toFollowTopic->id)->exists();
        $this->assertFalse($hasFollowed);

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->putJson(route('follows.topics.follow', ['topic' => $toFollowTopic->id]))
        ->assertStatus(200);

        // Query
        $hasFollowed = DB::table('critique_topic')->whereCritiqueId($critique->id)->whereTopicId($toFollowTopic->id)->exists();
        $this->assertTrue($hasFollowed);
    }

    public function testCritiqueUnfollowTopic() {
        // Critique User & Critique
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // To Follow Topic
        $toFollowTopic = \App\Models\Topic::factory()->create();
        $critique->followedTopics()->attach($toFollowTopic->id);

        // Query
        $hasFollowed = DB::table('critique_topic')->whereCritiqueId($critique->id)->whereTopicId($toFollowTopic->id)->exists();
        $this->assertTrue($hasFollowed);

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->putJson(route('follows.topics.unfollow', ['topic' => $toFollowTopic->id]))
        ->assertStatus(200);

        // Query
        $hasFollowed = DB::table('critique_topic')->whereCritiqueId($critique->id)->whereTopicId($toFollowTopic->id)->exists();
        $this->assertFalse($hasFollowed);
    }
}
