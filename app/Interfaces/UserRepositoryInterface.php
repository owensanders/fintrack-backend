<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function store(array $userData): User;
    public function update(array $userData): ?User;
}
