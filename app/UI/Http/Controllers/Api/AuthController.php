<?php

namespace App\UI\Http\Controllers\Api;

use App\Application\Services\AuthApplicationService;
use App\Domain\Exceptions\AuthenticationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class AuthController extends Controller
{
    public function __construct(private AuthApplicationService $authApplicationService)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $token = $this->authApplicationService->register(
                $request->name,
                $request->email,
                $request->password
            );

            return ApiResponse::auth($token);
        } catch (AuthenticationException | InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), 422);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $token = $this->authApplicationService->login(
                $request->email,
                $request->password
            );

            return ApiResponse::auth($token);
        } catch (AuthenticationException $e) {
            return ApiResponse::error($e->getMessage(), 401);
        }
    }
}
