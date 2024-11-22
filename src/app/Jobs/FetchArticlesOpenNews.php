<?php

namespace App\Jobs;

use App\Services\NewsFeed\Api\Articles\OpenNewsIntegrationService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchArticlesOpenNews implements ShouldQueue
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
    public function handle(OpenNewsIntegrationService $articleService)
    {
        $articles = $articleService->fetchArticles(Carbon::now());
        $articleService->saveArticles($articles);
    }
}
