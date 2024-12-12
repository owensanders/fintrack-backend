<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthenticationException;
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
        try {
            $authData = $this->authenticationService->login($request);

            return response()->json([
                'message' => 'Login successful',
                'user' => $authData['user'],
                'token' => $authData['token'],
            ]);
        } catch (AuthenticationException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authenticationService->logout($request);

            return response()->json([
                'message' => 'Logout successful.',
            ]);
        } catch (AuthenticationException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $authData = $this->authenticationService->register($request);

            return response()->json([
                'message' => 'Registration successful.',
                'user' => $authData['user'],
                'token' => $authData['token'],
            ]);
        } catch (AuthenticationException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
