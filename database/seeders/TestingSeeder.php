<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Critiques
        $critique = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();

        // Topics
        $topics = \App\Models\Topic::factory()->count(5)->create()->pluck('id');

        // Followed Topics
        $critique->followedTopics()->attach($topics);

        // Followed Critiques
        $followedCritique = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->create()
        ])->create();
        $critique->followings()->attach($followedCritique->id);

        // Followers (Critiques)
        $critique->followers()->attach($followedCritique->id);

        // Opinions
        $opinion = \App\Models\Opinion::factory()->state(['critique_id' => $critique->id])->create();
        $opinion->topics()->sync($topics);
    }
}
