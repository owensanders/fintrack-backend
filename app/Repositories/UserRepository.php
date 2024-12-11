<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly User $model)
    {
        //
    }

    public function store(array $userData): User
    {
        $userData['password'] = Hash::make($userData['password']);

        return $this->model->create($userData);
    }

    public function update(array $userData): ?User
    {
        $user = $this->model->find($userData['id']);

        if ($user) {
            $user->update([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'monthly_income' => $userData['monthly_income'],
            ]);

            return $user;
        }

        return null;
    }
}
