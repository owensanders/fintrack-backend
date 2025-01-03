<?php

namespace App\Interfaces;

use App\DataTransferObjects\SavingDto;
use App\Exceptions\UnauthorisedSavingAccessException;
use App\Exceptions\UserSavingNotFoundException;
use Illuminate\Http\Request;

interface UserSavingsServiceInterface
{
    public function store(Request $request): SavingDto;
    /**
     * @throws UserSavingNotFoundException
     * @throws UnauthorisedSavingAccessException
     */
    public function destroy(int $id): void;
    /**
     * @throws UserSavingNotFoundException
     * @throws UnauthorisedSavingAccessException
     */
    public function update(Request $request, int $id): void;
}
