<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    /**
     * @throws Exception
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $updatedUser = $this->userService->update($request);

        if($updatedUser) {
            return response()->json($updatedUser);
        }

        return response()->json('There was an error while updating the user', 500);
    }
}
