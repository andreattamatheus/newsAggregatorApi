<?php

namespace App\Services\Article;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleService
{
    /**
     * Retrieve all articles records with pagination.
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        try {
            return Article::query()
                ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
                    $query->whereBetween('publish_date', [$request->get('start_date', now()->startOfDay()), $request->get('end_date', now()->endOfDay())]);
                })
                ->when($request->filled('keyword'), function ($query) use ($request) {
                    $query->whereLike('keyword', $request->get('keyword'));
                })
                ->when($request->filled('source'), function ($query) use ($request) {
                    $query->whereIn('source', $request->get('source'));
                })
                ->when($request->filled('category'), function ($query) use ($request) {
                    $query->where('category', $request->get('category'));
                })
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 10));
        } catch (\Exception $e) {
            throw new \Exception('Error retrieving articles: '.$e->getMessage());
        }
    }
}
