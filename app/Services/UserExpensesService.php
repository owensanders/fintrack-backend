<?php

namespace App\Services;

use App\DataTransferObjects\ExpenseDto;
use App\Interfaces\UserExpenseRepositoryInterface;
use App\Interfaces\UserExpensesServiceInterface;
use App\Models\UserExpense;
use Illuminate\Http\Request;

class UserExpensesService implements UserExpensesServiceInterface
{
    public function __construct(private UserExpenseRepositoryInterface $userExpenseRepository)
    {
    }

    public function store(Request $request): UserExpense
    {
        $expenseDto = ExpenseDto::fromRequest($request)->toArray();
        return $this->userExpenseRepository->store($expenseDto);
    }

    public function destroy(int $id): bool
    {
        return $this->userExpenseRepository->destroy($id);
    }

    public function update(Request $request, int $id): bool
    {
        return $this->userExpenseRepository->update($id, $request->toArray());
    }
}
