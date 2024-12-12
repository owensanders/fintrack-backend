<?php

namespace App\Interfaces;

use App\DataTransferObjects\UserDto;
use App\Exceptions\UnauthorizedUpdateException;
use Illuminate\Http\Request;

interface UserServiceInterface
{
    /**
     * @throws UnauthorizedUpdateException
     */
    public function update(Request $request): ?UserDto;
}
