<?php

namespace App\Services\NewsFeed\Api\Articles;

use App\Data\ArticleNewYorkTimesData;
use App\Interfaces\IntegrationNewsApiInterface;
use App\Models\Article;
use App\Services\NewsFeed\Client\HttpClientService;
use App\Services\NewsFeed\Client\Interfaces\NewsFeedProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NewYorkTimesIntegrationService implements IntegrationNewsApiInterface, NewsFeedProvider
{
    protected HttpClientService $httpClient;

    public function __construct()
    {
        $this->httpClient = new HttpClientService($this);
    }

    public function getHeaderName(): string
    {
        return 'TheNewYorkTimes';
    }

    public function getHeaders(): array
    {
        return ['api-key' => config('app.api.new_york_times.key')];
    }

    public function getUrl(): string
    {
        return config('app.api.new_york_times.url');
    }

    public function fetchArticles(Carbon $fromDate): array
    {
        return $this->httpClient->makeRequest('get', [
            'pub_date' => $fromDate->format('Y-m-d'),
        ]);
    }

    public function saveArticles(array $articles): void
    {
        DB::transaction(static function () use ($articles) {
            collect($articles['data']['response']['docs'])
                ->map(fn($article) => ArticleNewYorkTimesData::fromArray($article))
                ->each(fn(ArticleNewYorkTimesData $articleData) => Article::query()->create([
                    'title' => $articleData->title,
                    'content' => $articleData->content,
                    'category' => $articleData->category,
                    'publish_date' => $articleData->publish_date->format('Y-m-d'),
                    'source' => $articleData->source,
                    'author' => $articleData->author,
                    'keyword' => $articleData->keywords,
                ]));
        });
    }
}
