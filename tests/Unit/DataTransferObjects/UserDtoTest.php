<?php

namespace DataTransferObjects;

use App\DataTransferObjects\UserDto;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class UserDtoTest extends TestCase
{
    public function test_from_request_creates_dto_correctly(): void
    {
        // Given
        $request = new Request([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'monthly_income' => 1230.45,
            'password' => 'password',
        ]);

        // When
        $dto = UserDto::fromRequest($request);

        // Then
        $this->assertInstanceOf(UserDto::class, $dto);
        $this->assertEquals(1, $dto->id);
        $this->assertEquals('John Doe', $dto->name);
        $this->assertEquals('john@doe.com', $dto->email);
        $this->assertEquals(1230.45, $dto->monthlyIncome);
        $this->assertEquals('password', $dto->password);
    }

    public function test_from_model_creates_dto_correctly(): void
    {
        // Given
        $mockModel = $this->mock(User::class);
        $mockModel->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $mockModel->shouldReceive('getAttribute')->with('name')->andReturn('John Doe');
        $mockModel->shouldReceive('getAttribute')->with('email')->andReturn('john@doe.com');
        $mockModel->shouldReceive('getAttribute')->with('monthly_income')->andReturn(2500);
        $mockModel->shouldReceive('getAttribute')->with('expense_total_amount')->andReturn(1000.75);
        $mockModel->shouldReceive('getAttribute')->with('savings_total_amount')->andReturn(7500.50);
        $mockModel->shouldReceive('getAttribute')->with('expenses')->andReturn([]);
        $mockModel->shouldReceive('getAttribute')->with('savings')->andReturn([]);

        // When
        $dto = UserDto::fromModel($mockModel);

        // Then
        $this->assertInstanceOf(UserDto::class, $dto);
        $this->assertEquals(1, $dto->id);
        $this->assertEquals('John Doe', $dto->name);
        $this->assertEquals('john@doe.com', $dto->email);
        $this->assertEquals(2500, $dto->monthlyIncome);
        $this->assertEquals(1000.75, $dto->expenseTotalAmount);
        $this->assertEquals(7500.50, $dto->savingsTotalAmount);
        $this->assertEquals([], $dto->expenses);
        $this->assertEquals([], $dto->savings);
    }

    public function test_to_array_returns_correct_array(): void
    {
        // Given
        $dto = new UserDto(
            id: 1,
            name: 'John Doe',
            email: 'john@doe.com',
            monthlyIncome: 2500.0,
            expenseTotalAmount: 7500.50,
            savingsTotalAmount: 7500.50
        );

        // When
        $array = $dto->toArray();

        // Then
        $this->assertIsArray($array);
        $this->assertEquals(
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@doe.com',
                'password' => null,
                'monthly_income' => 2500.0,
                'expense_total_amount' => 7500.50,
                'savings_total_amount' => 7500.50,
                'expenses' => null,
                'savings' => null,
            ],
            $array
        );
    }
}
