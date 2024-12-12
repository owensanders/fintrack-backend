<?php

namespace App\Services;

use App\DataTransferObjects\ExpenseDto;
use App\DataTransferObjects\UserDto;
use App\Exceptions\UnauthorisedExpenseAccessException;
use App\Exceptions\UserExpenseNotFoundException;
use App\Interfaces\UserExpenseRepositoryInterface;
use App\Interfaces\UserExpensesServiceInterface;
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

    /**
     * @throws UserExpenseNotFoundException
     * @throws UnauthorisedExpenseAccessException
     */
    public function destroy(int $id): void
    {
        $userExpense = $this->userExpenseRepository->find($id);

        if (!$userExpense) {
            throw new UserExpenseNotFoundException("Expense not found.");
        }

        if ($userExpense->user_id !== Auth::id()) {
            throw new UnauthorisedExpenseAccessException("You do not have permission to delete this expense.");
        }

        $this->userExpenseRepository->destroy($id);
    }

    /**
     * @throws UserExpenseNotFoundException
     * @throws UnauthorisedExpenseAccessException
     */
    public function update(Request $request, int $id): void
    {
        $userExpense = $this->userExpenseRepository->find($id);

        if (!$userExpense) {
            throw new UserExpenseNotFoundException("Expense not found.");
        }

        if ($userExpense->user_id !== Auth::id()) {
            throw new UnauthorisedExpenseAccessException("You do not have permission to update this expense.");
        }

        $expenseDto = ExpenseDto::fromRequest($request);
        $this->userExpenseRepository->update($id, $expenseDto);
    }

    public function getAuthenticatedUserDto(): UserDto
    {
        $user = Auth::user();
        return UserDto::fromModel($user);
    }
}
