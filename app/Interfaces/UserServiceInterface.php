<?php

namespace App\Interfaces;

use App\DataTransferObjects\UserDto;
use App\Exceptions\UnauthorisedUpdateException;
use Illuminate\Http\Request;

interface UserServiceInterface
{
    /**
     * @throws UnauthorisedUpdateException
     */
    public function update(Request $request): ?UserDto;
}
