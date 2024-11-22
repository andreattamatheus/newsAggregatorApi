<?php

namespace App\Console\Commands;

use App\Jobs\FetchArticles;
use Illuminate\Console\Command;

class FetchArticlesFromApis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles-from-apis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        FetchArticles::dispatch();
    }
}
