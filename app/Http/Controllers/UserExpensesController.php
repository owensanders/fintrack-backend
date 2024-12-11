<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserDto;
use App\Http\Requests\CreateUserExpenseRequest;
use App\Http\Requests\UpdateUserExpenseRequest;
use App\Interfaces\UserExpensesServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserExpensesController extends Controller
{
    public function __construct(private UserExpensesServiceInterface $userExpensesService)
    {
    }

    public function store(CreateUserExpenseRequest $request): JsonResponse
    {
        $newExpense = $this->userExpensesService->store($request);

        return response()->json(data: UserDto::fromModel($newExpense->user), status: 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $hasDeleted = $this->userExpensesService->destroy($id);

        if ($hasDeleted) {
            return response()->json(UserDto::fromModel(auth()->user()));
        }

        return response()->json(['message' => 'Record delete unsuccessful.'], 400);
    }

    public function update(UpdateUserExpenseRequest $request, int $id): JsonResponse
    {
        $this->userExpensesService->update($request, $id);

        return response()->json(UserDto::fromModel(auth()->user()));
    }
}
