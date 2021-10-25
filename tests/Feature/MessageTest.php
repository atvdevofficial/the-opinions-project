<?php

namespace Tests\Feature;

use App\Models\Critique;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCritiqueMessageList()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // receiver Critique User & Critique
        $receiverCritiqueUser = User::factory()->role('CRITIQUE')->create();
        $receiverCritique = Critique::factory()->state(['user_id' => $receiverCritiqueUser->id])->create();

        // 2nd receiver Critique User & Critique
        $secReceiverCritiqueUser = User::factory()->role('CRITIQUE')->create();
        $secReceiverCritique = Critique::factory()->state(['user_id' => $secReceiverCritiqueUser->id])->create();

        $critique->sentMessages()->create([
            'receiver_id' => $receiverCritique->id,
            'text' => $this->faker->sentence,
            'created_at' => '2020-10-25 05:00:00'
        ]);

        $receiverCritique->sentMessages()->create([
            'receiver_id' => $critique->id,
            'text' => $this->faker->sentence,
            'created_at' => '2020-10-20 05:00:00'
        ]);

        $secReceiverCritique->sentMessages()->create([
            'receiver_id' => $critique->id,
            'text' => $this->faker->sentence,
            'created_at' => '2020-10-20 05:00:00'
        ]);

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->getJson(route('critiques.messages.index', ['critique' => $critique->id]))
            ->assertSuccessful()
            ->assertJsonStructure([[
                'critique_id', 'critique_name',
                'text', 'timestamp'
            ]]);

         /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(3, User::get());

        // Check number of critiques
        $this->assertCount(3, Critique::get());

        // Check number of messages
        $this->assertCount(3, Message::get());
    }

    public function testCritiqueCreateMessage()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // receiver Critique User & Critique
        $receiverCritiqueUser = User::factory()->role('CRITIQUE')->create();
        $receiverCritique = Critique::factory()->state(['user_id' => $receiverCritiqueUser->id])->create();

        // Message data
        $messageData = [
            'critique' => $critique->id,
            'receiver_id' => $receiverCritique->id,
            'text' => $this->faker->sentence
        ];

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->postJson(route('critiques.messages.store', $messageData))
            ->assertSuccessful()
            ->assertJsonStructure([
                'sender_id', 'receiver_id',
                'id', 'text', 'created_at'
            ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(2, User::get());

        // Check number of critiques
        $this->assertCount(2, Critique::get());

        // Check number of messages
        $this->assertCount(1, Message::get());
    }
}
