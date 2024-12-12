<?php

namespace App\Repositories;

use App\DataTransferObjects\ExpenseDto;
use App\Exceptions\UserExpenseNotFoundException;
use App\Interfaces\UserExpenseRepositoryInterface;
use App\Models\UserExpense;

class UserExpenseRepository implements UserExpenseRepositoryInterface
{
    public function __construct(private readonly UserExpense $model)
    {
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
        $userExpense = $this->find($id);

        if (!$userExpense) {
            throw new UserExpenseNotFoundException();
        }

        $userExpense->update($expenseDto->toArray());
        return true;
    }

    public function find(int $id): ?UserExpense
    {
        return $this->model->find($id);
    }
}
