<?php

namespace Tests\Feature;

use App\Models\Critique;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FollowCritiqueTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCritiqueShowFollowList()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Followings
        $critique->followings()->attach(
            Critique::factory()->count(1)->state([
                'user_id' => User::factory()->role('CRITIQUE')->create()
            ])->create()->pluck('id')
        );

        // Followers
        $critique->followers()->attach(
            Critique::factory()->count(1)->state([
                'user_id' => User::factory()->role('CRITIQUE')->create()
            ])->create()->pluck('id')
        );

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->getJson(route('follows.critiques.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'followers' => [['id', 'name']],
                'followings' => [['id', 'name']],
            ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(3, User::get());

        // Check number of critiques
        $this->assertCount(3, Critique::get());

        // Check number of critique_topics
        $this->assertCount(2, DB::table('follow_critique')->get());
    }

    public function testCritiqueFollow()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // To Follow Critique
        $toFollowCritique = Critique::factory()->state([
            'user_id' => User::factory()->role('CRITIQUE')->create()
        ])->create();

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->putJson(route('follows.critiques.follow', ['critique' => $toFollowCritique->id]))
            ->assertStatus(200);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(2, User::get());

        // Check number of critiques
        $this->assertCount(2, Critique::get());

        // Check number of critique_topics
        $this->assertCount(1, DB::table('follow_critique')->get());

        // Check if critique has followed critique
        $hasFollowed = DB::table('follow_critique')->whereFollowerId($critique->id)->whereFollowedId($toFollowCritique->id)->exists();
        $this->assertTrue($hasFollowed);
    }

    public function testCritiqueUnfollow()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // To Follow Critique
        $followedCritique = Critique::factory()->state([
            'user_id' => User::factory()->role('CRITIQUE')->create()
        ])->create();
        $critique->followings()->attach($followedCritique->id);

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->putJson(route('follows.critiques.unfollow', ['critique' => $followedCritique->id]))
            ->assertStatus(200);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(2, User::get());

        // Check number of critiques
        $this->assertCount(2, Critique::get());

        // Check number of critique_topics
        $this->assertCount(0, DB::table('follow_critique')->get());

        // Check if critique has unfollowed critique
        $hasFollowed = DB::table('follow_critique')->whereFollowerId($critique->id)->whereFollowedId($followedCritique->id)->exists();
        $this->assertFalse($hasFollowed);
    }
}
