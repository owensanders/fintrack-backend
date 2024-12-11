<?php

namespace App\DataTransferObjects;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

readonly class UserDto
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public float $monthlyIncome,
        public ?array $expenses = null,
        public ?string $password = null,
    ) {
        //
    }

    public static function fromModel(User|Authenticatable|null $user): ?self
    {
        if (!$user) {
            return null;
        }

        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            monthlyIncome: $user->monthly_income,
            expenses: $user->expenses,
        );
    }

    public static function fromRequest(Request $request): ?self
    {
        return new self(
            id: $request->input('id'),
            name: $request->get('name'),
            email: $request->get('email'),
            monthlyIncome: $request->get('monthly_income', 0.00),
            password: $request->get('password'),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'monthly_income' => $this->monthlyIncome,
            'expenses' => $this->expenses,
            'password' => $this->password,
        ];
    }
}
