<?php

namespace Database\Seeders;

use App\Models\Preference;
use Illuminate\Database\Seeder;

class PreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Preference::factory()->create([
            'type' => 'categories',
        ]);

        Preference::factory()->create([
            'type' => 'authors',
        ]);
        Preference::factory()->create([
            'type' => 'news_sources',
        ]);
    }
}
