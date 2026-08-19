<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Tests\TestCase;

class ArticleEndpointsTest extends TestCase
{
    public function test_guest_cannot_access_article_endpoints(): void
    {
        $this->getJson('/api/v1/articles')->assertUnauthorized();
    }

    public function test_authenticated_user_can_list_articles_with_filters(): void
    {
        $user = User::factory()->create();
        Article::factory()->create(['title' => 'Technology wins', 'keyword' => 'technology', 'source' => 'Wire']);
        Article::factory()->create(['title' => 'Sports news', 'keyword' => 'sports', 'source' => 'Daily']);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/articles?keyword=technology&per_page=1');

        $response->assertOk()
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Technology wins');
    }

    public function test_authenticated_user_can_view_an_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['title' => 'A known headline']);

        $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/articles/{$article->id}")
            ->assertOk()
            ->assertJsonPath('data.title', 'A known headline');
    }
}
