<?php

namespace App\Repositories;

use App\Interfaces\UserExpenseRepositoryInterface;
use App\Models\UserExpense;

class UserExpenseRepository implements UserExpenseRepositoryInterface
{
    public function __construct(private readonly UserExpense $model)
    {
        //
    }

    public function store(array $expense): UserExpense
    {
        return $this->model->create($expense);
    }

    public function destroy(int $id): bool
    {
        return $this->model->destroy($id);
    }

    public function update(int $id, array $expense): bool
    {
        $userExpense = $this->model::find($id);

        if (!$userExpense) {
            return false;
        }

        $userExpense->update([
            'expense_name' => $expense['expense_name'],
            'expense_amount' => $expense['expense_amount'],
        ]);

        return true;
    }
}
