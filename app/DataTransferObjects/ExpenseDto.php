<?php

namespace App\DataTransferObjects;

use App\Models\UserExpense;
use Illuminate\Http\Request;

readonly class ExpenseDto
{
    public function __construct(
        public ?int $id,
        public int $userId,
        public string $expenseName,
        public float $expenseAmount,
    ) {
        //
    }

    public static function fromRequest(Request $request): ?self
    {
        return new self(
            id: $request->input('id'),
            userId: $request->get('user_id'),
            expenseName: $request->get('expense_name'),
            expenseAmount: $request->get('expense_amount'),
        );
    }

    public static function fromModel(UserExpense $model): ?self
    {
        return new self(
            id: $model->id,
            userId: $model->user_id,
            expenseName: $model->expense_name,
            expenseAmount: $model->expense_amount,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'expense_name' => $this->expenseName,
            'expense_amount' => $this->expenseAmount,
        ];
    }
}
