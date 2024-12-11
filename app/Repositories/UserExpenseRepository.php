<?php

namespace App\Repositories;

use App\DataTransferObjects\ExpenseDto;
use App\Interfaces\UserExpenseRepositoryInterface;
use App\Models\UserExpense;

class UserExpenseRepository implements UserExpenseRepositoryInterface
{
    public function __construct(private readonly UserExpense $model)
    {
        //
    }

    public function store(ExpenseDto $expenseDto): ExpenseDto
    {
        $newExpense = $this->model->create($expenseDto->toArray());
        return ExpenseDto::fromModel($newExpense);
    }

    public function destroy(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    public function update(int $id, ExpenseDto $expenseDto): bool
    {
        $userExpense = $this->model->find($id);

        if (!$userExpense) {
            return false;
        }

        $userExpense->update($expenseDto->toArray());

        return true;
    }
}
