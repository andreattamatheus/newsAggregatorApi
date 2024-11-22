<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class ArticleTheNewsData extends Data
{
    public function __construct(
        public string $title,
        public string $description,
        public string $categories,
        public Carbon $published_at,
        public string $source,
        public string $author,
        public string $keywords,

    ) {
    }

    public static function fromArray(array $attributes): self
    {
        return new self(
            title: $attributes['title'] ?? 'No Title',
            description: $attributes['description'] ?? 'No Description',
            categories: $attributes['categories']  ? implode(',', $attributes['categories']) : ['Uncategorized'],
            published_at: isset($attributes['published_at'])
                ? Carbon::parse($attributes['published_at'])
                : now(),
            source: $attributes['source'] ?? 'Unknown',
            author: $attributes['author'] ?? 'Unknown',
            keywords: $attributes['keywords'] ?? 'No Keywords'
        );
    }

}
