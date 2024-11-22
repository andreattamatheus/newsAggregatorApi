<?php

namespace App\Services\NewsFeed\Client\Interfaces;

interface NewsFeedProvider
{
    /**
     * Used to get the API key for the request
     * @return string
     */
    public function getHeaderName(): string;

    /**
     * Used to get the API key for the request
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Used to get the URL for the API server in production
     * @return string
     */
    public function getUrl(): string;


}
