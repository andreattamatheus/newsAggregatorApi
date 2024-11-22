<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class ArticleOpenNewsData extends Data
{
    public function __construct(
        public string $title,
        public string $content,
        public string $category,
        public Carbon $publish_date,
        public string $source,
        public ?string $author,
        public string $keyword
    ) {}

    /**
     * Factory method to transform raw data into the DTO
     */
    public static function fromArray(array $attributes): self
    {
        return new self(
            title: $attributes['title'] ?? 'No Title',
            content: $attributes['description'] ?? 'No Description',
            category: isset($attributes['categories']) ? implode(',', $attributes['categories']) : 'Uncategorized',
            publish_date: isset($attributes['publishedAt'])
                ? Carbon::parse($attributes['publishedAt'])
                : now(),
            source: $attributes['source']['name'] ?? 'Unknown',
            author: $attributes['author'] ?? 'Unknown',
            keyword: $attributes['keywords'] ?? 'No Keywords'
        );
    }
}
