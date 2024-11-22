<?php

namespace App\Jobs;

use App\Services\NewsFeed\Api\Articles\TheNewsIntegrationService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchArticlesTheNewsApi implements ShouldQueue
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
    public function handle(TheNewsIntegrationService $articleService)
    {
        $articles = $articleService->fetchArticles(Carbon::now());
        $articleService->saveArticles($articles);
    }
}
