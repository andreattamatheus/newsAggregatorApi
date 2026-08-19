<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Article $article */
        $article = $this->resource;

        return [
            'id' => $article->id,
            'title' => $article->title,
            'content' => $article->content,
            'author' => $article->author,
            'source' => $article->source,
            'category' => $article->category,
            'publish_date' => $article->publish_date,
            'keyword' => $article->keyword,
        ];
    }
}
