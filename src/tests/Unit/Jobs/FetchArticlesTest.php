<?php

namespace Tests\Unit\Jobs;

use App\Jobs\Api\FetchArticlesOpenNews;
use App\Jobs\Api\FetchArticlesTheNewsApi;
use App\Jobs\Api\FetchArticlesTheNewYorkTimes;
use App\Jobs\Articles\FetchArticles;
use App\Services\NewsFeed\Api\Articles\NewYorkTimesIntegrationService;
use App\Services\NewsFeed\Api\Articles\OpenNewsIntegrationService;
use App\Services\NewsFeed\Api\Articles\TheNewsIntegrationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Mockery;
use Tests\TestCase;

class FetchArticlesTest extends TestCase
{
    public function test_aggregation_job_dispatches_each_provider_job(): void
    {
        Bus::fake();

        (new FetchArticles)->handle();

        Bus::assertDispatched(FetchArticlesOpenNews::class);
        Bus::assertDispatched(FetchArticlesTheNewsApi::class);
        Bus::assertDispatched(FetchArticlesTheNewYorkTimes::class);
    }

    public function test_open_news_job_fetches_and_saves_articles(): void
    {
        $service = Mockery::mock(OpenNewsIntegrationService::class);
        $service->expects('fetchArticles')->with(Mockery::type(Carbon::class))->andReturn(['data' => ['articles' => []]]);
        $service->expects('saveArticles')->with(['data' => ['articles' => []]]);
        $this->app->instance(OpenNewsIntegrationService::class, $service);

        (new FetchArticlesOpenNews)->handle($service);
    }

    public function test_the_news_job_fetches_and_saves_articles(): void
    {
        $service = Mockery::mock(TheNewsIntegrationService::class);
        $service->expects('fetchArticles')->with(Mockery::type(Carbon::class))->andReturn(['data' => ['data' => []]]);
        $service->expects('saveArticles')->with(['data' => ['data' => []]]);
        $this->app->instance(TheNewsIntegrationService::class, $service);

        (new FetchArticlesTheNewsApi)->handle($service);
    }

    public function test_new_york_times_job_fetches_and_saves_articles(): void
    {
        $service = Mockery::mock(NewYorkTimesIntegrationService::class);
        $service->expects('fetchArticles')->with(Mockery::type(Carbon::class))->andReturn(['data' => ['response' => ['docs' => []]]]);
        $service->expects('saveArticles')->with(['data' => ['response' => ['docs' => []]]]);
        $this->app->instance(NewYorkTimesIntegrationService::class, $service);

        (new FetchArticlesTheNewYorkTimes)->handle($service);
    }
}
