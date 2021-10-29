<?php

namespace Tests\Feature;

use App\Models\Critique;
use App\Models\Opinion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testFeed()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // To Follow Critique
        $followedCritique = Critique::factory()->state([
            'user_id' => User::factory()->role('CRITIQUE')->create()
        ])->create();
        $critique->followings()->attach($followedCritique->id);

        Opinion::factory()->count(5)->state([
            'critique_id' => $followedCritique->id
        ])->create();

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->getJson(route('feed'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [[
                    'id', 'text', 'is_public',
                    'created_at', 'updated_at',
                    'like_count', 'liked_by_user'
                ]]
            ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(2, User::get());

        // Check number of critiques
        $this->assertCount(2, Critique::get());

        // Check number of opinions
        $this->assertCount(5, Opinion::get());
    }
}
