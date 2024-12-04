<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface AuthenticationServiceInterface
{
    public function login(Request $request): array;
    public function logout(Request $request): void;
    public function register(Request $request): array;
}
