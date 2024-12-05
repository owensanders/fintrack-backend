<?php

namespace App\DataTransferObjects;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

readonly class UserDto
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public ?string $password = null,
    ) {
        //
    }

    public static function fromModel(User|Authenticatable|null $user): ?self
    {
        if (!$user) {
            return null;
        }

        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
        );
    }

    public static function fromRequest(Request $request): ?self
    {
        return new self(
            id: $request->input('id'),
            name: $request->get('name'),
            email: $request->get('email'),
            password: $request->get('password'),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
