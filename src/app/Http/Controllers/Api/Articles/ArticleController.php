<?php

namespace App\Http\Controllers\Api\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\Article\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request.
     */
    public function index(Request $request, ArticleService $articleService): JsonResponse|ResourceCollection
    {
        try {
            $articles = $articleService->getAll($request);

            return ArticleResource::collection($articles);
        } catch (\Exception $e) {
            logger()->channel('daily')->error('Error retrieving the articles: '.$e->getMessage());

            return response()->json([
                'message' => 'Error retrieving the article. Contact support.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article): JsonResponse|ArticleResource
    {
        try {
            return new ArticleResource($article);
        } catch (\Exception $e) {
            logger()->channel('daily')->error('Error getting the article: '.$e->getMessage());

            return response()->json([
                'message' => 'Error getting the article. Contact support.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
