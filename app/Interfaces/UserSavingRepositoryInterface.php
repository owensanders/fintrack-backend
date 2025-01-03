<?php

namespace App\Interfaces;

use App\DataTransferObjects\SavingDto;
use App\Exceptions\UserExpenseNotFoundException;
use App\Models\UserSaving;

interface UserSavingRepositoryInterface
{
    public function store(SavingDto $savingDto): SavingDto;
    public function destroy(int $id): bool;
    /**
     * @throws UserExpenseNotFoundException
     */
    public function update(int $id, SavingDto $savingDto): bool;
    public function find(int $id): ?UserSaving;
}
