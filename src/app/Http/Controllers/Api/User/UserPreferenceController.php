<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserPreferenceRequest;
use App\Http\Resources\UserPreferenceResource;
use App\Services\User\UserPreferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class UserPreferenceController extends Controller
{
    /**
     * Display a listing of user preferences.
     */
    public function index(Request $request, UserPreferenceService $userPreferenceService): AnonymousResourceCollection|JsonResponse
    {
        try {
            $preferences = $userPreferenceService->getAll($request);

            return UserPreferenceResource::collection($preferences);
        } catch (\Exception $e) {
            logger()->channel('daily')->error('Error retrieving the preferences: '.$e->getMessage());

            return response()->json([
                'message' => 'Error retrieving the preferences. Contact support.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created user preference in storage.
     */
    public function store(StoreUserPreferenceRequest $request, UserPreferenceService $userPreferenceService): UserPreferenceResource|JsonResponse
    {
        try {
            $preferences = $userPreferenceService->store($request);

            return new UserPreferenceResource($preferences);
        } catch (\Exception $e) {
            logger()->channel('daily')->error('Error retrieving the preferences: '.$e->getMessage());

            return response()->json([
                'message' => 'Error retrieving the preferences. Contact support.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
