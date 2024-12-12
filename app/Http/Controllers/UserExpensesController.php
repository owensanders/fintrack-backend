<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorisedExpenseAccessException;
use App\Exceptions\UserExpenseNotFoundException;
use App\Http\Requests\CreateUserExpenseRequest;
use App\Http\Requests\UpdateUserExpenseRequest;
use App\Interfaces\UserExpensesServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class UserExpensesController extends Controller
{
    public function __construct(private readonly UserExpensesServiceInterface $userExpensesService)
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
        try {
            $this->userExpensesService->destroy($id);
            $userDto = $this->userExpensesService->getAuthenticatedUserDto();

            return response()->json($userDto);
        } catch (UserExpenseNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (UnauthorisedExpenseAccessException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    public function update(UpdateUserExpenseRequest $request, int $id): JsonResponse
    {
        try {
            $this->userExpensesService->update($request, $id);
            $userDto = $this->userExpensesService->getAuthenticatedUserDto();

            return response()->json($userDto);
        } catch (UserExpenseNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (UnauthorisedExpenseAccessException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
}
