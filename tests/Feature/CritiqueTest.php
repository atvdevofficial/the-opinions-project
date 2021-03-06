<?php

namespace Tests\Feature;

use App\Models\Critique;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CritiqueTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testAdminShowCritiqueList()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        // Critique Critiques
        User::factory()
            ->state(['role' => 'CRITIQUE'])
            ->has(Critique::factory())
            ->count(3)
            ->create();

        // Sanctum
        Sanctum::actingAs($administrator);

        // Response
        $this->getJson(route('critiques.index'))
            ->assertStatus(200)
            ->assertJsonStructure([[
                'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
            ]]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(4, User::get());

        // Check number of critiques
        $this->assertCount(3, Critique::get());
    }

    public function testAdminCreateCritique()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        // Critique Critique
        $critiqueData = [
            'email' => 'sample@sample.com',
            'password' => 'password',
            'name' => $this->faker->name,
            'username' => $this->faker->unique()->word
        ];

        // Sanctum
        Sanctum::actingAs($administrator);

        // Response
        $this->postJson(route('critiques.store', $critiqueData))
            ->assertStatus(201)
            ->assertJsonStructure([
                'id', 'name', 'username',
                'user' => ['id', 'email', 'email_verified_at', 'role']
            ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(2, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());
    }

    public function testAdminShowCritique()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        // Critique Critique
        $critique = Critique::factory()
            ->for(
                User::factory()
                    ->state(['role' => 'CRITIQUE'])
            )
            ->create();

        // Sanctum
        Sanctum::actingAs($administrator);

        // Response
        $this->getJson(route('critiques.show', ['critique' => $critique->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
            ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(2, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());
    }

    public function testAdminUpdateCritique()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        // Critique Critique
        $critique = Critique::factory()
            ->for(
                User::factory()
                    ->state(['role' => 'CRITIQUE'])
            )
            ->create();

        // Updated Critique Critique
        $critiqueData = [
            'critique' => $critique->id,

            'email' => 'sample@sample.com',
            'password' => 'password',
            'name' => $this->faker->name,
            'username' => $this->faker->unique()->word
        ];

        // Sanctum
        Sanctum::actingAs($administrator);

        // Response
        $this->putJson(route('critiques.update', $critiqueData))
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'name', 'username',
                'user' => ['id', 'email', 'email_verified_at', 'role']
            ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(2, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());

        // Check critique data
        $critique = Critique::findOrFail($critique->id);
        $this->assertEquals($critiqueData['email'], $critique->user->email);
        $this->assertEquals($critiqueData['name'], $critique->name);
        Hash::check($critiqueData['password'], $critique->user->password); // Hash check password
    }

    public function testAdminDeleteCritique()
    {
        // Administrator Account
        $administrator = User::factory()->role('ADMINISTRATOR')->create();

        // Critique Critique
        $critique = Critique::factory()
            ->for(
                User::factory()
                    ->state(['role' => 'CRITIQUE'])
            )
            ->create();

        // Sanctum
        Sanctum::actingAs($administrator);

        // Response
        $this->deleteJson(route('critiques.destroy', ['critique' => $critique->id]))
            ->assertStatus(200);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(0, Critique::get());
    }

    public function testCritiqueShowCritique()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->getJson(route('critiques.show', ['critique' => $critique->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'name', 'user' => ['id', 'email', 'email_verified_at', 'role']
            ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());
    }

    public function testCritiqueUpdateCritique()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Updated Critique Critique
        $critiqueData = [
            'critique' => $critique->id,

            'email' => 'sample@sample.com',
            'password' => 'password',
            'name' => $this->faker->name,
            'username' => $this->faker->unique()->word
        ];

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->putJson(route('critiques.update', $critiqueData))
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'name', 'username',
                'user' => ['id', 'email', 'email_verified_at', 'role']
            ]);

        /**
         * Database checks
         */

        // Check number of users
        $this->assertCount(1, User::get());

        // Check number of critiques
        $this->assertCount(1, Critique::get());

        // Check critique data
        $critique = Critique::findOrFail($critique->id);
        $this->assertEquals($critiqueData['email'], $critique->user->email);
        $this->assertEquals($critiqueData['name'], $critique->name);
        Hash::check($critiqueData['password'], $critique->user->password); // Hash check password
    }

    public function testCritiqueStatisticCritique()
    {
        // Critique User & Critique
        $critiqueUser = User::factory()->role('CRITIQUE')->create();
        $critique = Critique::factory()->state(['user_id' => $critiqueUser->id])->create();

        // Sanctum
        Sanctum::actingAs($critiqueUser);

        // Response
        $this->getJson(route('critiques.statistics', ['critique' => $critique->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'topics', 'followers', 'followings'
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
