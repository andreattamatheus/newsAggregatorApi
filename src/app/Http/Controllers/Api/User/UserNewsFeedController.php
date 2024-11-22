<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Services\User\UserArticleService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserNewsFeedController extends Controller
{
    /**
     * Display a listing of the user's news feed.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\UserArticleService $userArticleService
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, UserArticleService $userArticleService)
    {
        try {
            $articles = $userArticleService->getAll($request);

            return ArticleResource::collection($articles);
        } catch (\Exception $e) {
            logger()->channel('daily')->error('Error retrieving the articles: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error retrieving the articles. Contact support.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
