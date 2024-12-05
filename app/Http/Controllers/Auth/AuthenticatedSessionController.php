<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\AuthenticationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly AuthenticationServiceInterface $authenticationService)
    {
        //
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $authData = $this->authenticationService->login($request);

        return response()->json([
            'message' => 'Login successful',
            'user' => $authData['user'],
            'token' => $authData['token'],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authenticationService->logout($request);

        return response()->json([
            'message' => 'Logout successful.',
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $authData = $this->authenticationService->register($request);

        return response()->json([
            'message' => 'Registration successful.',
            'user' => $authData['user'],
            'token' => $authData['token'],
        ]);
    }
}
