<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserSaving;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSavingFactory extends Factory
{
    protected $model = UserSaving::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'saving_name' => $this->faker->word(),
            'saving_amount' => $this->faker->randomFloat(2, 10, 500),
            'saving_goal' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
