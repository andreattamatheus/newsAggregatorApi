<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Preference;
use App\Models\User;
use App\Models\UserPreference;
use Tests\TestCase;

class UserNewsFeedEndpointTest extends TestCase
{
    public function test_authenticated_user_receives_articles_matching_preferences(): void
    {
        $user = User::factory()->create();
        $category = Preference::query()->create(['type' => 'categories']);
        UserPreference::query()->create([
            'user_id' => $user->id,
            'preference_id' => $category->id,
            'content' => ['science'],
        ]);
        Article::factory()->create(['title' => 'Science story', 'category' => 'science']);
        Article::factory()->create(['title' => 'Unrelated story', 'category' => 'sports']);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/user/news-feed');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Science story');
    }
}
