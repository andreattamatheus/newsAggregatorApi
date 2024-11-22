<?php

namespace App\Services\User;

use App\Http\Requests\StoreUserPreferenceRequest;
use App\Models\Preference;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserPreferenceService
{
    public function getAll(Request $request): LengthAwarePaginator
    {
        try {
            return UserPreference::query()
                ->where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 10));
        } catch (\Exception $e) {
            throw new \Exception('Error retrieving preferences: '.$e->getMessage());
        }
    }

    public function store(StoreUserPreferenceRequest $request): UserPreference
    {
        try {
            $userPreference = UserPreference::query()
                ->where('user_id', $request->user()->id)
                ->whereHas('preference', function ($query) use ($request) {
                    $query->where('type', $request->get('type'));
                })
                ->first();

            if ($userPreference) {
                return DB::transaction(function () use ($userPreference, $request) {
                    $values = explode(',', $request->get('values'));
                    $userPreference->content = array_unique(array_merge($userPreference->content, $values));
                    $userPreference->save();

                    return $userPreference;
                });
            }

            $preference = Preference::query()
                ->where('type', $request->get('type'))
                ->first();

            return UserPreference::firstOrCreate([
                'user_id' => $request->user()->id,
                'preference_id' => $preference->id,
                'content' => $request->get('values'),
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error creating preferences: '.$e->getMessage());
        }
    }
}
