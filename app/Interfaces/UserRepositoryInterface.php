<?php

namespace App\Interfaces;

use App\DataTransferObjects\UserDto;
use App\Models\User;

interface UserRepositoryInterface
{
    public function store(UserDto $dto): User;
    public function update(UserDto $dto): ?User;
}
