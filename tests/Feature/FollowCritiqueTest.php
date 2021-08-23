<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FollowCritiqueTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCritiqueShowFollowList() {
        // Critique User & Critique
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Followings
        $critique->followings()->attach(
            \App\Models\Critique::factory()->count(1)->state([
                'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
            ])->create()->pluck('id')
        );

        // Followers
        $critique->followers()->attach(
            \App\Models\Critique::factory()->count(1)->state([
                'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
            ])->create()->pluck('id')
        );

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->getJson(route('follows.critiques.index'))
        ->assertStatus(200)
        ->assertJsonStructure([
            'followers' => [['id', 'name']],
            'followings' => [['id', 'name']],
        ]);
    }

    public function testCritiqueFollow() {
        // Critique User & Critique
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // To Follow Critique
        $toFollowCritique = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();

        // Query
        $hasFollowed = DB::table('follow_critique')->whereFollowerId($critique->id)->whereFollowedId($toFollowCritique->id)->exists();
        $this->assertFalse($hasFollowed);

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->putJson(route('follows.critiques.follow', ['critique' => $toFollowCritique->id]))
        ->assertStatus(200);

        // Query
        $hasFollowed = DB::table('follow_critique')->whereFollowerId($critique->id)->whereFollowedId($toFollowCritique->id)->exists();
        $this->assertTrue($hasFollowed);
    }

    public function testCritiqueUnfollow() {
        // Critique User & Critique
        $critiqueUser = \App\Models\User::factory()->role('CRITIQUE')->create();
        $critique = \App\Models\Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // To Follow Critique
        $followedCritique = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();
        $critique->followings()->attach($followedCritique->id);

        // Query
        $hasFollowed = DB::table('follow_critique')->whereFollowerId($critique->id)->whereFollowedId($followedCritique->id)->exists();
        $this->assertTrue($hasFollowed);

        // Response
        $this->actingAs($critiqueUser, 'api')
        ->putJson(route('follows.critiques.unfollow', ['critique' => $followedCritique->id]))
        ->assertStatus(200);

        // Query
        $hasFollowed = DB::table('follow_critique')->whereFollowerId($critique->id)->whereFollowedId($followedCritique->id)->exists();
        $this->assertFalse($hasFollowed);
    }
}
