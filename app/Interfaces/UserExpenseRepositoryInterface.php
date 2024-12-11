<?php

namespace App\Interfaces;

use App\Models\UserExpense;

interface UserExpenseRepositoryInterface
{
    public function store(array $expense): UserExpense;
    public function destroy(int $id): bool;
    public function update(int $id, array $expense): bool;
}
