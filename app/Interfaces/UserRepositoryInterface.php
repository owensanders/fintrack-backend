<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function store(array $user): User;
}
