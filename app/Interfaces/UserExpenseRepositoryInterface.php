<?php

namespace App\Interfaces;

use App\DataTransferObjects\ExpenseDto;
use App\Exceptions\UserExpenseNotFoundException;
use App\Models\UserExpense;

interface UserExpenseRepositoryInterface
{
    public function store(ExpenseDto $expenseDto): ExpenseDto;
    public function destroy(int $id): bool;
    /**
     * @throws UserExpenseNotFoundException
     */
    public function update(int $id, ExpenseDto $expenseDto): bool;
    public function find(int $id): ?UserExpense;
}
