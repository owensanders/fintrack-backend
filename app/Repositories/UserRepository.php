<?php

namespace App\Repositories;

use App\DataTransferObjects\UserDto;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly User $model)
    {
        //
    }

    public function store(UserDto $dto): User
    {
        $userData = $dto->toArray();
        $userData['password'] = Hash::make($userData['password']);

        return $this->model->create($userData);
    }

    public function update(UserDto $dto): ?User
    {
        $user = $this->model->find($dto->id);

        if ($user) {
            $user->update([
                'name' => $dto->name,
                'email' => $dto->email,
                'monthly_income' => $dto->monthlyIncome,
            ]);

            return $user->refresh();
        }

        return null;
    }
}
