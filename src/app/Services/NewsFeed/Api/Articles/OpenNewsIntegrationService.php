<?php

namespace App\Services\NewsFeed\Api\Articles;

use App\Data\ArticleOpenNewsData;
use App\Models\Article;
use App\Services\NewsFeed\Api\Articles\Interfaces\IntegrationNewsApiInterface;
use App\Services\NewsFeed\Client\HttpClientService;
use App\Services\NewsFeed\Client\Interfaces\NewsFeedProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OpenNewsIntegrationService implements IntegrationNewsApiInterface, NewsFeedProvider
{
    protected HttpClientService $httpClient;

    public function __construct()
    {
        $this->httpClient = new HttpClientService($this);
    }

    public function getHeaderName(): string
    {
        return 'OpenNews';
    }

    public function getHeaders(): array
    {
        return ['apiKey' => config('app.api.news.key')];
    }

    public function getUrl(): string
    {
        return config('app.api.news.url');
    }

    public function fetchArticles(Carbon $fromDate): array
    {
        return $this->httpClient->makeRequest('get', [
            'q' => 'technology',
            'from' => $fromDate->format('Y-m-d'),
            'sortBy' => 'publishedAt',
        ]);

    }

    public function saveArticles(array $articles): void
    {
        DB::transaction(static function () use ($articles) {
            collect($articles['data']['articles'])
                ->map(fn($article) => ArticleOpenNewsData::fromArray($article))
                ->each(fn(ArticleOpenNewsData $articleData) => Article::query()->create([
                    'title' => $articleData->title,
                    'content' => $articleData->content,
                    'category' => $articleData->category,
                    'publish_date' => $articleData->publish_date->format('Y-m-d'),
                    'source' => $articleData->source,
                    'author' => $articleData->author,
                    'keyword' => $articleData->keyword,
                ]));
        });
    }
}
