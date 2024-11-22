<?php

namespace Database\Seeders;

use App\Models\UserPreference;
use Illuminate\Database\Seeder;

class UserPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserPreference::query()->create([
            'user_id' => 1,
            'preference_id' => 1,
            'content' => ['technology', 'business', 'science'],
        ]);

        UserPreference::query()->create([
            'user_id' => 1,
            'preference_id' => 2,
            'content' => ['John Doe', 'Jane Doe'],
        ]);

        UserPreference::query()->create([
            'user_id' => 1,
            'preference_id' => 3,
            'content' => ['bbc-news', 'cnn', 'fox-news'],
        ]);
    }
}
