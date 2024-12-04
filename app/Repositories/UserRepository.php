<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly User $model)
    {
        //
    }

    public function store(array $user): User
    {
        return $this->model->create($user);
    }
}
