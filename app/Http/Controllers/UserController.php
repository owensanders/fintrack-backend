<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserServiceInterface;
use App\Exceptions\UnauthorizedUpdateException;
use Illuminate\Http\JsonResponse;
use Exception;

class UserController extends Controller
{
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        try {
            $updatedUser = $this->userService->update($request);

            if ($updatedUser) {
                return response()->json($updatedUser);
            }

            return response()->json(['message' => 'The updated user could no be found.'], 404);
        } catch (UnauthorizedUpdateException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
}
