<?php

namespace App\Services;

use App\DataTransferObjects\UserDto;
use App\Interfaces\AuthenticationServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
        //
    }

    public function login(Request $request): array
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();
        $token = $user->createToken('login-token')->plainTextToken;

        return [
            'user' => UserDto::fromModel($user),
            'token' => $token
        ];
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
        $userDto = UserDto::fromRequest($request);
        $user = $this->userRepository->store($userDto);

        Auth::login($user);
        $request->session()->regenerate();
        $token = $user->createToken('register-token')->plainTextToken;

        return [
            'user' => UserDto::fromModel($user),
            'token' => $token
        ];
    }
}
