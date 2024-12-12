<?php

namespace App\Services;

use App\DataTransferObjects\UserDto;
use App\Exceptions\UnauthorisedUpdateException;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService implements UserServiceInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @throws UnauthorisedUpdateException
     */
    public function update(Request $request): ?UserDto
    {
        $userId = $request->input('id');
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->id !== (int)$userId) {
            throw new UnauthorisedUpdateException();
        }

        $userDto = UserDto::fromRequest($request);
        $user = $this->userRepository->update($userDto);

        if (!$user) {
            return null;
        }

        return UserDto::fromModel($user);
    }
}
