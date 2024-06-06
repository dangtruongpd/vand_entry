<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Log in to the system
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!auth()->attempt($credentials)) {
            return Response::error(__('Authentication failed.'), [], 403);
        } else {
            $token = $request->user()->createToken('authToken');

            return Response::success(__(' You\'re logged in!'), [
                'token_type' => 'Bearer',
                'access_token' => $token->plainTextToken,
            ]);
        }
    }

    /**
     * Log out from the system
     * 
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return Response::success(__(' You\'re logged out!'));
    }
}
