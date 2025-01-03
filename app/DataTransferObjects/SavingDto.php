<?php

namespace App\DataTransferObjects;

use App\Models\UserSaving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

readonly class SavingDto
{
    public function __construct(
        public ?int $id,
        public int $userId,
        public string $savingName,
        public float $savingAmount,
        public float $savingGoal,
    ) {
        //
    }

    public static function fromRequest(Request $request): ?self
    {
        return new self(
            id: $request->input('id'),
            userId: $request->get('user_id', Auth::id()),
            savingName: $request->get('saving_name'),
            savingAmount: $request->get('saving_amount'),
            savingGoal: $request->get('saving_goal'),
        );
    }

    public static function fromModel(UserSaving $model): ?self
    {
        return new self(
            id: $model->id,
            userId: $model->user_id,
            savingName: $model->saving_name,
            savingAmount: $model->saving_amount,
            savingGoal: $model->saving_goal,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'saving_name' => $this->savingName,
            'saving_amount' => $this->savingAmount,
            'saving_goal' => $this->savingGoal,
        ];
    }
}
