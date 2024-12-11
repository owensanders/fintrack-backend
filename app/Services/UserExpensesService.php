<?php

namespace App\Services;

use App\DataTransferObjects\ExpenseDto;
use App\DataTransferObjects\UserDto;
use App\Interfaces\UserExpenseRepositoryInterface;
use App\Interfaces\UserExpensesServiceInterface;
use App\Models\UserExpense;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserExpensesService implements UserExpensesServiceInterface
{
    public function __construct(private UserExpenseRepositoryInterface $userExpenseRepository)
    {
    }

    public function store(Request $request): ExpenseDto
    {
        $expenseDto = ExpenseDto::fromRequest($request);
        return $this->userExpenseRepository->store($expenseDto);
    }

    public function destroy(int $id): bool
    {
        return $this->userExpenseRepository->destroy($id);
    }

    public function update(Request $request, int $id): bool
    {
        $expenseDto = ExpenseDto::fromRequest($request);
        return $this->userExpenseRepository->update($id, $expenseDto);
    }

    public function getAuthenticatedUserDto(): UserDto
    {
        $user = Auth::user();
        return UserDto::fromModel($user);
    }
}
