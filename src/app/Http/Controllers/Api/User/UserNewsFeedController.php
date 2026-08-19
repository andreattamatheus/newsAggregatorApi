<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Services\User\UserArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class UserNewsFeedController extends Controller
{
    /**
     * Display a listing of the user's news feed.
     */
    public function index(Request $request, UserArticleService $userArticleService): AnonymousResourceCollection|JsonResponse
    {
        try {
            $articles = $userArticleService->getAll($request);

            return ArticleResource::collection($articles);
        } catch (\Exception $e) {
            logger()->channel('daily')->error('Error retrieving the articles: '.$e->getMessage());

            return response()->json([
                'message' => 'Error retrieving the articles. Contact support.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
