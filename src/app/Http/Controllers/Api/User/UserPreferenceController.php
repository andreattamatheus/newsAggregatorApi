<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserPreferenceRequest;
use App\Http\Resources\UserPreferenceResource;
use App\Services\User\UserPreferenceService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserPreferenceController extends Controller
{
    /**
     * Display a listing of user preferences.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\UserPreferenceService $userPreferenceService
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, UserPreferenceService $userPreferenceService)
    {
        try {
            $preferences = $userPreferenceService->getAll($request);

            return UserPreferenceResource::collection($preferences);
        } catch (\Exception $e) {
            logger()->channel('daily')->error('Error retrieving the preferences: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error retrieving the preferences. Contact support.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created user preference in storage.
     *
     * @param  \App\Http\Requests\StoreUserPreferenceRequest  $request
     * @param  \App\Services\UserPreferenceService  $userPreferenceService
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserPreferenceRequest $request, UserPreferenceService $userPreferenceService)
    {
        try {
            $preferences = $userPreferenceService->store($request);

            return new UserPreferenceResource($preferences);
        } catch (\Exception $e) {
            logger()->channel('daily')->error('Error retrieving the preferences: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error retrieving the preferences. Contact support.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
