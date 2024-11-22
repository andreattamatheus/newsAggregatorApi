<?php

namespace App\Jobs\Api;

use App\Services\NewsFeed\Api\Articles\NewYorkTimesIntegrationService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchArticlesTheNewYorkTimes implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(NewYorkTimesIntegrationService $articleService)
    {
        $articles = $articleService->fetchArticles(Carbon::now());
        $articleService->saveArticles($articles);
    }
}
