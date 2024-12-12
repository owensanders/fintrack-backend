<?php

namespace App\Services;

use App\DataTransferObjects\UserDto;
use App\Exceptions\UnauthorizedUpdateException;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService implements UserServiceInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @throws UnauthorizedUpdateException
     */
    public function update(Request $request): ?UserDto
    {
        $userId = $request->input('id');
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->id !== (int)$userId) {
            throw new UnauthorizedUpdateException();
        }

        $userDto = UserDto::fromRequest($request);
        $user = $this->userRepository->update($userDto);

        if (!$user) {
            return null;
        }

        return UserDto::fromModel($user);
    }
}
