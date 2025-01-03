<?php

namespace App\Services;

use App\DataTransferObjects\SavingDto;
use App\DataTransferObjects\UserDto;
use App\Exceptions\UnauthorisedSavingAccessException;
use App\Exceptions\UserSavingNotFoundException;
use App\Interfaces\UserSavingRepositoryInterface;
use App\Interfaces\UserSavingsServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserSavingsService implements UserSavingsServiceInterface
{
    public function __construct(private readonly UserSavingRepositoryInterface $userSavingRepository)
    {
    }

    public function store(Request $request): SavingDto
    {
        $savingDto = SavingDto::fromRequest($request);
        return $this->userSavingRepository->store($savingDto);
    }

    /**
     * @throws UserSavingNotFoundException
     * @throws UnauthorisedSavingAccessException
     */
    public function destroy(int $id): void
    {
        $userSaving = $this->userSavingRepository->find($id);

        if (!$userSaving) {
            throw new UserSavingNotFoundException("Saving not found.");
        }

        if ($userSaving->user_id !== Auth::id()) {
            throw new UnauthorisedSavingAccessException("You do not have permission to delete this saving.");
        }

        $this->userSavingRepository->destroy($id);
    }

    /**
     * @throws UserSavingNotFoundException
     * @throws UnauthorisedSavingAccessException
     */
    public function update(Request $request, int $id): void
    {
        $userSaving = $this->userSavingRepository->find($id);

        if (!$userSaving) {
            throw new UserSavingNotFoundException("Saving not found.");
        }

        if ($userSaving->user_id !== Auth::id()) {
            throw new UnauthorisedSavingAccessException("You do not have permission to update this saving.");
        }

        $savingDto = SavingDto::fromRequest($request);
        $this->userSavingRepository->update($id, $savingDto);
    }

    public function getAuthenticatedUserDto(): array
    {
        $user = Auth::user();
        return UserDto::fromModel($user)->toArray();
    }
}
