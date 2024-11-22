<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Contracts\BaseData;

class ArticleNewYorkTimesData extends Data
{
    public function __construct(
        public string $title,
        public string $content,
        public string $category,
        public Carbon $publish_date,
        public string $source,
        public ?string $author,
        public string $keywords
    ) {}

    /**
     * Factory method to transform raw data into the DTO
     */
    public static function fromArray(array $attributes): self
    {
        return new self(
            title: $attributes['headline']['main'] ?? 'No Title',
            content: $attributes['abstract'] ?? 'No Description',
            category: $attributes['section_name'] ?? 'Uncategorized',
            publish_date: isset($attributes['publishedAt'])
                ? Carbon::parse($attributes['publishedAt'])
                : now(),
            source: $attributes['source'] ?? 'Unknown',
            author: $attributes['byline']['original'] ?? 'Unknown',
            keywords: isset($attributes['keywords'])
                ? implode(array_slice(array_column($attributes['keywords'], 'value'), 0, 3))
                : 'No keywords',
        );
    }
}
