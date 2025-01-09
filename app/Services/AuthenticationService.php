<?php

namespace App\Services;

use App\DataTransferObjects\UserDto;
use App\Exceptions\AuthenticationException;
use App\Interfaces\AuthenticationServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Auth\AuthenticationException as BaseAuthException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
        //
    }

    /**
     * @throws AuthenticationException
     */
    public function login(Request $request): array
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            $user = auth()->user();
            $token = $user->createToken('login-token')->plainTextToken;

            return [
                'user' => UserDto::fromModel($user)->toArray(),
                'token' => $token
            ];
        } catch (Exception $e) {
            throw new AuthenticationException('Invalid credentials provided.', 401, $e);
        }
    }

    /**
     * @throws AuthenticationException
     */
    public function logout(Request $request): void
    {
        try {
            $request->user()->tokens->each(fn ($token) => $token->delete());
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } catch (Exception $e) {
            throw new AuthenticationException('An error occurred while logging out.', 500, $e);
        }
    }

    /**
     * @throws AuthenticationException
     */
    public function register(Request $request): array
    {
        try {
            $userDto = UserDto::fromRequest($request);
            $user = $this->userRepository->store($userDto);

            Auth::login($user);
            $request->session()->regenerate();
            $token = $user->createToken('register-token')->plainTextToken;

            return [
                'user' => UserDto::fromModel($user)->toArray(),
                'token' => $token
            ];
        } catch (Exception $e) {
            throw new AuthenticationException('An error occurred during registration.', 500, $e);
        }
    }
}
