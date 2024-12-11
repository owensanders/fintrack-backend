<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserExpenseRequest;
use App\Http\Requests\UpdateUserExpenseRequest;
use App\Interfaces\UserExpensesServiceInterface;
use Illuminate\Http\JsonResponse;

class UserExpensesController extends Controller
{
    public function __construct(private UserExpensesServiceInterface $userExpensesService)
    {
    }

    public function store(CreateUserExpenseRequest $request): JsonResponse
    {
        $this->userExpensesService->store($request);
        $userDto = $this->userExpensesService->getAuthenticatedUserDto();

        return response()->json(data: $userDto, status: 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $hasDeleted = $this->userExpensesService->destroy($id);

        if ($hasDeleted) {
            $userDto = $this->userExpensesService->getAuthenticatedUserDto();
            return response()->json($userDto);
        }

        return response()->json(['message' => 'Record delete unsuccessful.'], 400);
    }

    public function update(UpdateUserExpenseRequest $request, int $id): JsonResponse
    {
        $this->userExpensesService->update($request, $id);
        $userDto = $this->userExpensesService->getAuthenticatedUserDto();

        return response()->json($userDto);
    }
}
