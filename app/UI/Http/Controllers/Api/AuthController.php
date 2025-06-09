<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $token = $this->authService->register([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return ApiResponse::auth($token);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return ApiResponse::auth($token);
    }
}
