<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {
            Article::create([
                'title' => fake()->sentence,
                'content' => fake()->paragraph,
                'author' => fake()->name,
                'source' => fake()->company,
                'category' => fake()->word,
                'publish_date' => fake()->dateTime,
                'keyword' => fake()->word,
            ]);
        }
    }
}
