<?php

namespace Tests\Feature;

use App\Models\Critique;
use App\Models\Opinion;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testSearch()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Extra user & critique
        Critique::factory()->state([
            'user_id' => User::factory()->role('CRITIQUE')->create(),
            'name' => 'search'
        ])->create();

        // Another extra user & critique
        Critique::factory()->state([
            'user_id' => User::factory()->role('CRITIQUE')->create(),
            'username' => 'search'
        ])->create();

        // Opinion
        Opinion::factory()->state(['critique_id' => $critique->id, 'text' => 'search'])->create();

        // Topic
        Topic::factory()->state(['text' => 'search'])->create();

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->getJson(route('search') . "?search=search")
            ->assertStatus(200)
            ->assertJsonStructure([
                'critiques' => [
                    'data' => [[
                        'id', 'name', 'username'
                    ]]
                ],
                'topics' => [
                    'data' => [[
                        'id', 'text',
                    ]]
                ],
                'opinions' => [
                    'data' => [[
                        'id', 'text', 'is_public',
                        'created_at', 'updated_at',
                        'like_count', 'liked_by_user'
                    ]]
                ],
            ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(3, User::get());

        // Check number of critiques
        $this->assertCount(3, Critique::get());

        // Check number of opinions
        $this->assertCount(1, Opinion::get());

        // Check number of topics
        $this->assertCount(1, Topic::get());
    }
}
