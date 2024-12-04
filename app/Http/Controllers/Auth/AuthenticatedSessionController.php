<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Interfaces\AuthenticationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly AuthenticationServiceInterface $authenticationService)
    {
    }

    public function store(LoginRequest $request): JsonResponse
    {
        [$user, $token] = $this->authenticationService->login($request);

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->authenticationService->logout($request);

        return response()->json([
            'message' => 'Logout successful.',
        ]);
    }
}
