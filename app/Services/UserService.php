<?php

namespace App\Services;

use App\DataTransferObjects\UserDto;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserServiceInterface;
use Exception;
use Illuminate\Http\Request;

class UserService implements UserServiceInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function update(Request $request): ?UserDto
    {
        $userDto = UserDto::fromRequest($request);
        $user = $this->userRepository->update($userDto);

        if (!$user) {
            return null;
        }

        return UserDto::fromModel($user);
    }
}
