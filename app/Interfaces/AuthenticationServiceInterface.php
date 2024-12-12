<?php

namespace App\Interfaces;

use App\Exceptions\AuthenticationException;
use Illuminate\Http\Request;

interface AuthenticationServiceInterface
{
    /**
     * @throws AuthenticationException
     */
    public function login(Request $request): array;
    /**
     * @throws AuthenticationException
     */
    public function logout(Request $request): void;
    /**
     * @throws AuthenticationException
     */
    public function register(Request $request): array;
}
