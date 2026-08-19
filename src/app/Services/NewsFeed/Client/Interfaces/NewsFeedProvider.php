<?php

namespace App\Services\NewsFeed\Client\Interfaces;

interface NewsFeedProvider
{
    /**
     * Used to get the API key for the request
     */
    public function getHeaderName(): string;

    /**
     * Used to get the API key for the request
     */
    public function getHeaders(): array;

    /**
     * Used to get the URL for the API server in production
     */
    public function getUrl(): string;
}
