<?php

namespace App\Repositories;

use App\DataTransferObjects\SavingDto;
use App\Exceptions\UserSavingNotFoundException;
use App\Interfaces\UserSavingRepositoryInterface;
use App\Models\UserSaving;

class UserSavingRepository implements UserSavingRepositoryInterface
{
    public function __construct(private readonly UserSaving $model)
    {
    }

    public function store(SavingDto $savingDto): SavingDto
    {
        $newSaving = $this->model->create($savingDto->toArray());
        return SavingDto::fromModel($newSaving);
    }

    public function destroy(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    /**
     * @throws UserSavingNotFoundException
     */
    public function update(int $id, SavingDto $savingDto): bool
    {
        $userSaving = $this->find($id);

        if (!$userSaving) {
            throw new UserSavingNotFoundException();
        }

        $userSaving->update($savingDto->toArray());
        return true;
    }

    public function find(int $id): ?UserSaving
    {
        return $this->model->find($id);
    }
}
