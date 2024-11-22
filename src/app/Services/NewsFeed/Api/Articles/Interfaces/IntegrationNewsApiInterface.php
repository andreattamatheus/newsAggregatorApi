<?php

namespace App\Services\NewsFeed\Api\Articles\Interfaces;

use Carbon\Carbon;

interface IntegrationNewsApiInterface
{
    public function fetchArticles(Carbon $fromDate): array;

    public function saveArticles(array $articles): void;
}
