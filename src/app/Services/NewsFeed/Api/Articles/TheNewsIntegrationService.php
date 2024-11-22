<?php

namespace App\Services\NewsFeed\Api\Articles;

use App\Data\ArticleTheNewsData;
use App\Interfaces\IntegrationNewsApiInterface;
use App\Models\Article;
use App\Services\NewsFeed\Client\HttpClientService;
use App\Services\NewsFeed\Client\Interfaces\NewsFeedProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TheNewsIntegrationService implements IntegrationNewsApiInterface, NewsFeedProvider
{
    protected HttpClientService $httpClient;

    public function __construct()
    {
        $this->httpClient = new HttpClientService($this);
    }

    public function getHeaderName(): string
    {
        return 'TheNewsApi';
    }

    public function getHeaders(): array
    {
        return [
            'api_token' => config('app.api.the_news.key'),
            'locale' => 'us',
            'language' => 'en',
        ];
    }

    public function getUrl(): string
    {
        return config('app.api.the_news.url');
    }

    public function fetchArticles(Carbon $fromDate): array
    {
        return $this->httpClient->makeRequest('get', [
            'published_after' => $fromDate->format('Y-m-d'),
        ]);
    }

    public function saveArticles(array $articles): void
    {
        DB::transaction(static function () use ($articles) {
            collect($articles['data']['data'])
                ->map(fn($article) => ArticleTheNewsData::fromArray($article))
                ->each(fn(ArticleTheNewsData $articleData) => Article::query()->create([
                    'title' => $articleData->title,
                    'content' => $articleData->description,
                    'category' => $articleData->categories,
                    'publish_date' => $articleData->published_at->format('Y-m-d'),
                    'source' => $articleData->source,
                    'author' => $articleData->author,
                    'keyword' => $articleData->keywords
                ]));
        });
    }
}
