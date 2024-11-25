<?php

namespace App\Jobs\Articles;

use App\Jobs\Api\FetchArticlesOpenNews;
use App\Jobs\Api\FetchArticlesTheNewsApi;
use App\Jobs\Api\FetchArticlesTheNewYorkTimes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchArticles implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
    public function handle(): void
    {
        FetchArticlesOpenNews::dispatch();
        FetchArticlesTheNewsApi::dispatch();
        FetchArticlesTheNewYorkTimes::dispatch();
    }
}
