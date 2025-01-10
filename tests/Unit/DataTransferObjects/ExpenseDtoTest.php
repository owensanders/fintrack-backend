<?php

namespace DataTransferObjects;

use App\DataTransferObjects\ExpenseDto;
use App\Models\UserExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ExpenseDtoTest extends TestCase
{
    public function test_from_request_creates_dto_correctly(): void
    {
        // Given
        Auth::shouldReceive('id')->andReturn(1);
        $request = new Request([
            'id' => 10,
            'user_id' => 1,
            'expense_name' => 'Test Expense',
            'expense_amount' => 123.45,
        ]);

        // When
        $dto = ExpenseDto::fromRequest($request);

        // Then
        $this->assertInstanceOf(ExpenseDto::class, $dto);
        $this->assertEquals(10, $dto->id);
        $this->assertEquals(1, $dto->userId);
        $this->assertEquals('Test Expense', $dto->expenseName);
        $this->assertEquals(123.45, $dto->expenseAmount);
    }

    public function test_from_request_uses_auth_id_when_user_id_not_provided(): void
    {
        // Given
        Auth::shouldReceive('id')->andReturn(2);
        $request = new Request([
            'expense_name' => 'Auth Expense',
            'expense_amount' => 50.25,
        ]);

        // When
        $dto = ExpenseDto::fromRequest($request);

        // Then
        $this->assertEquals(2, $dto->userId);
        $this->assertEquals('Auth Expense', $dto->expenseName);
        $this->assertEquals(50.25, $dto->expenseAmount);
    }

    public function test_from_model_creates_dto_correctly(): void
    {
        // Given
        $mockModel = $this->mock(UserExpense::class);
        $mockModel->shouldReceive('getAttribute')->with('id')->andReturn(20);
        $mockModel->shouldReceive('getAttribute')->with('user_id')->andReturn(3);
        $mockModel->shouldReceive('getAttribute')->with('expense_name')->andReturn('Model Expense');
        $mockModel->shouldReceive('getAttribute')->with('expense_amount')->andReturn(75.50);

        // When
        $dto = ExpenseDto::fromModel($mockModel);

        // Then
        $this->assertInstanceOf(ExpenseDto::class, $dto);
        $this->assertEquals(20, $dto->id);
        $this->assertEquals(3, $dto->userId);
        $this->assertEquals('Model Expense', $dto->expenseName);
        $this->assertEquals(75.50, $dto->expenseAmount);
    }

    public function test_to_array_returns_correct_array(): void
    {
        // Given
        $dto = new ExpenseDto(
            id: 30,
            userId: 4,
            expenseName: 'Array Expense',
            expenseAmount: 99.99
        );

        // When
        $array = $dto->toArray();

        // Then
        $this->assertIsArray($array);
        $this->assertEquals(
            [
                'id' => 30,
                'user_id' => 4,
                'expense_name' => 'Array Expense',
                'expense_amount' => 99.99,
            ],
            $array
        );
    }
}
