<?php

namespace App\Interfaces;

use App\DataTransferObjects\ExpenseDto;
use App\Exceptions\UnauthorisedExpenseAccessException;
use App\Exceptions\UserExpenseNotFoundException;
use Illuminate\Http\Request;

interface UserExpensesServiceInterface
{
    public function store(Request $request): ExpenseDto;
    /**
     * @throws UserExpenseNotFoundException
     * @throws UnauthorisedExpenseAccessException
     */
    public function destroy(int $id): void;
    /**
     * @throws UserExpenseNotFoundException
     * @throws UnauthorisedExpenseAccessException
     */
    public function update(Request $request, int $id): void;
}
