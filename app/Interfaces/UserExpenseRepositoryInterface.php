<?php

namespace App\Interfaces;

use App\DataTransferObjects\ExpenseDto;

interface UserExpenseRepositoryInterface
{
    public function store(ExpenseDto $expenseDto): ExpenseDto;
    public function destroy(int $id): bool;
    public function update(int $id, ExpenseDto $expenseDto): bool;
}
