<?php

namespace App\Interfaces;

use App\DataTransferObjects\UserDto;
use Illuminate\Http\Request;

interface UserServiceInterface
{
    public function update(Request $request): ?UserDto;
}
