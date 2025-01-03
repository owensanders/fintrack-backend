<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorisedSavingAccessException;
use App\Exceptions\UserSavingNotFoundException;
use App\Http\Requests\CreateUserSavingRequest;
use App\Http\Requests\UpdateUserSavingRequest;
use App\Interfaces\UserSavingsServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class UserSavingsController extends Controller
{
    public function __construct(private readonly UserSavingsServiceInterface $userSavingsService)
    {
    }

    public function store(CreateUserSavingRequest $request): JsonResponse
    {
        $this->userSavingsService->store($request);
        $userDto = $this->userSavingsService->getAuthenticatedUserDto();

        return response()->json(data: $userDto, status: 201);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userSavingsService->destroy($id);
            $userDto = $this->userSavingsService->getAuthenticatedUserDto();

            return response()->json($userDto);
        } catch (UserSavingNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (UnauthorisedSavingAccessException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    public function update(UpdateUserSavingRequest $request, int $id): JsonResponse
    {
        try {
            $this->userSavingsService->update($request, $id);
            $userDto = $this->userSavingsService->getAuthenticatedUserDto();

            return response()->json($userDto);
        } catch (UserSavingNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (UnauthorisedSavingAccessException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
}
