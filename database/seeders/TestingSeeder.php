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
        $topics = \App\Models\Topic::factory()->count(5)->create()->pluck('id');

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
            ->hasAttached(\App\Models\Topic::factory()->count(3))
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
            ->hasAttached(\App\Models\Topic::factory()->count(3))
            ->create();

        // Critiques Three
        $critiqueThree = \App\Models\Critique::factory()->state([
            'user_id' => \App\Models\User::factory()->role('CRITIQUE')->state(['email' => 'critiqueThree@opinions.com'])->create()
        ])->create();

        // Critique Two Opinions
        \App\Models\Opinion::factory()
            ->count(10)
            ->state(['critique_id' => $critiqueThree->id])
            ->hasAttached(\App\Models\Topic::factory()->count(3))
            ->create();

        // Critique One follows Critiqie Two
        $critiqueOne->followings()->attach($critiqueTwo->id);
        // Critique One follows Critiqie Three
        $critiqueOne->followings()->attach($critiqueThree->id);

        // Critique Two is one of the followers of Critique One
        $critiqueOne->followers()->attach($critiqueTwo->id);
    }
}
