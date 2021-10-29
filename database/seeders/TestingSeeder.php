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
        // Topics
        $topics = \App\Models\Topic::factory()->count(5)->create();

        // Critique One
        $critiqueOne = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->state(['email' => 'critiqueOne@opinions.com'])->create()
        ])->create();

        // Critique One Followed Topics
        $critiqueOne->followedTopics()->attach($topics);

        // Critique One Opinions
        \App\Models\Opinion::factory()
            ->count(10)
            ->state(['critique_id' => $critiqueOne->id])
            ->hasAttached($topics)
            ->create();
        // $opinion->topics()->sync($topics);

        // Critiques Two
        $critiqueTwo = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->state(['email' => 'critiqueTwo@opinions.com'])->create()
        ])->create();

        // Critique Two Followed Topics
        $critiqueTwo->followedTopics()->attach($topics);

        // Critique Two Opinions
        \App\Models\Opinion::factory()
            ->count(10)
            ->state(['critique_id' => $critiqueTwo->id])
            ->hasAttached($topics)
            ->create();

        // Critiques Three
        $critiqueThree = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->state(['email' => 'critiqueThree@opinions.com'])->create()
        ])->create();

        // Critique Three Opinions
        \App\Models\Opinion::factory()
            ->count(10)
            ->state(['critique_id' => $critiqueThree->id])
            ->hasAttached($topics)
            ->create();

        // Critique One follows Critiqie Two
        $critiqueOne->followings()->attach($critiqueTwo->id);
        // Critique One follows Critiqie Three
        $critiqueOne->followings()->attach($critiqueThree->id);

        // Critique Two is one of the followers of Critique One
        $critiqueOne->followers()->attach($critiqueTwo->id);

        // Critiques Three
        $critiqueFour = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->state(['email' => 'critiqueFour@opinions.com'])->create()
        ])->create();

        // Critique Three Opinions
        \App\Models\Opinion::factory()
            ->count(10)
            ->state(['critique_id' => $critiqueFour->id])
            ->hasAttached($topics)
            ->create();

        /**
         * Messages
         */
        $critiqueOne->sentMessages()->create(['receiver_id' => $critiqueTwo->id, 'text' => 'Hello World, from critique one to critique two']);
        $critiqueTwo->sentMessages()->create(['receiver_id' => $critiqueOne->id,'text' => 'Hello World, from critique two to critique one']);
        $critiqueThree->sentMessages()->create(['receiver_id' => $critiqueOne->id,'text' => 'Hello World, from critique three to critique one']);
    }
}
