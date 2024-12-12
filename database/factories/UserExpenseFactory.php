<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserExpense;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserExpenseFactory extends Factory
{
    protected $model = UserExpense::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'expense_name' => $this->faker->word(),
            'expense_amount' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
