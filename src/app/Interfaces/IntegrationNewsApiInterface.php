<?php

namespace App\Interfaces;

use Carbon\Carbon;

interface IntegrationNewsApiInterface
{
    public function fetchArticles(Carbon $fromDate): array;

    public function saveArticles(array $articles): void;
}
