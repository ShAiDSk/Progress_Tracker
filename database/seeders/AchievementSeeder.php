<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First Steps',
                'description' => 'Complete your very first goal.',
                'icon' => '🚀',
            ],
            [
                'name' => 'On Fire',
                'description' => 'Reach a 3-day streak.',
                'icon' => '🔥',
            ],
            [
                'name' => 'Goal Crusher',
                'description' => 'Complete 10 total goals.',
                'icon' => '🏆',
            ],
            [
                'name' => 'Early Bird',
                'description' => 'Complete a goal before 8 AM.',
                'icon' => '🌅',
            ]
        ];

        foreach ($badges as $badge) {
            Achievement::firstOrCreate(['name' => $badge['name']], $badge);
        }
    }
}