<?php

namespace App\Services;

use App\Interfaces\AuthenticationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function login(Request $request): array
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();
        $token = $user->createToken('login-token')->plainTextToken;

        return [$user, $token];
    }

    public function logout(Request $request): void
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
