<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserDto;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $userData = UserDto::fromRequest($request)->toArray();

        return response()->json($this->userRepository->update($userData));
    }
}
