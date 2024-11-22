<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserCreateResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     */
    public function login(LoginRequest $request, UserService $userService): JsonResponse
    {
        if (! auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $userService->getUserToken($request->email);

        return response()->json([
            'message' => 'User logged in successfully',
            'access_token' => $token,
        ], Response::HTTP_OK);
    }

    /**
     * Create user
     */
    public function register(RegisterRequest $request, UserService $userService): UserCreateResource|JsonResponse
    {
        try {
            $user = $userService->createUser($request->validated());

            return new UserCreateResource($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function reset(ResetPasswordRequest $request)
    {
        try {
            Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            return response()->json(['message' => 'Password reset successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function resetIndex(Request $request)
    {
        return view('auth.reset-password', ['token' => $request->token]);
    }

    public function forgotPassword(Request $request)
    {
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
