<?php

namespace App\Services;

use App\Interfaces\AuthenticationServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
        //
    }

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

    public function register(Request $request): array
    {
        $user = $this->userRepository->store([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $token = $user->createToken('register-token')->plainTextToken;

        return [$user, $token];
    }
}
