<?php

namespace App\Http\Resources;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var UserPreference $userPreference */
        $userPreference = $this->resource;

        return [
            'preference_type' => $userPreference->preference->type,
            'content' => $userPreference->content,
        ];
    }
}
