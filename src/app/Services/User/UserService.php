<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getUserToken(string $email)
    {
        $user = User::query()->where('email', $email)->firstOrFail();

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function createUser(array $request): User
    {
        $user = new User([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        return DB::transaction(function () use ($user) {
            $user->save();

            return $user;
        });
    }
}
