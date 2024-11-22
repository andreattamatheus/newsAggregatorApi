<?php

namespace App\Services\User;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserArticleService
{
    public function getAll(Request $request): LengthAwarePaginator
    {
        try {
            $preferencesByType = $this->userPreferences($request);

            return Article::query()
                ->when(! empty($preferencesByType['authors']), function ($query) use ($preferencesByType) {
                    $query->orWhereIn('keyword', $preferencesByType['authors']);
                })
                ->when(! empty($preferencesByType['news_sources']), function ($query) use ($preferencesByType) {
                    $query->orWhereIn('source', $preferencesByType['news_sources']);
                })
                ->when(! empty($preferencesByType['categories']), function ($query) use ($preferencesByType) {
                    $query->orWhereIn('category', $preferencesByType['categories']);
                })
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 10));
        } catch (\Exception $e) {
            throw new \Exception('Error retrieving preferences: '.$e->getMessage());
        }
    }

    private function userPreferences(Request $request): array
    {
        $userPreferences = $request->user()->preferences;
        $preferencesByType = [];
        foreach ($userPreferences as $userPreference) {
            $preferencesByType[$userPreference->preference->type] = $userPreference->content;
        }

        return $preferencesByType;
    }
}
