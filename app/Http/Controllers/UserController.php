<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserDto;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function update(UpdateUserRequest $request): ?User
    {
        $userData = UserDto::fromRequest($request)->toArray();

        return $this->userRepository->update($userData);
    }
}
