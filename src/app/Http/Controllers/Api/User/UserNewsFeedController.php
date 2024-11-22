<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Services\User\UserArticleService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserNewsFeedController extends Controller
{
    public function index(Request $request, UserArticleService $userArticleService)
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
