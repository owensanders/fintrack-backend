<?php

namespace DataTransferObjects;

use App\DataTransferObjects\SavingDto;
use App\Models\UserSaving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SavingDtoTest extends TestCase
{
    public function test_from_request_creates_dto_correctly(): void
    {
        // Given
        Auth::shouldReceive('id')->andReturn(1);
        $request = new Request([
            'id' => 10,
            'user_id' => 1,
            'saving_name' => 'Test Saving',
            'saving_amount' => 123.45,
            'saving_goal' => 1500,
        ]);

        // When
        $dto = SavingDto::fromRequest($request);

        // Then
        $this->assertInstanceOf(SavingDto::class, $dto);
        $this->assertEquals(10, $dto->id);
        $this->assertEquals(1, $dto->userId);
        $this->assertEquals('Test Saving', $dto->savingName);
        $this->assertEquals(123.45, $dto->savingAmount);
        $this->assertEquals(1500, $dto->savingGoal);
    }

    public function test_from_request_uses_auth_id_when_user_id_not_provided(): void
    {
        // Given
        Auth::shouldReceive('id')->andReturn(2);
        $request = new Request([
            'saving_name' => 'Auth Saving',
            'saving_amount' => 50.25,
            'saving_goal' => 2500.0,
        ]);

        // When
        $dto = SavingDto::fromRequest($request);

        // Then
        $this->assertEquals(2, $dto->userId);
        $this->assertEquals('Auth Saving', $dto->savingName);
        $this->assertEquals(50.25, $dto->savingAmount);
        $this->assertEquals(2500.0, $dto->savingGoal);
    }

    public function test_from_model_creates_dto_correctly(): void
    {
        // Given
        $mockModel = $this->mock(UserSaving::class);
        $mockModel->shouldReceive('getAttribute')->with('id')->andReturn(20);
        $mockModel->shouldReceive('getAttribute')->with('user_id')->andReturn(3);
        $mockModel->shouldReceive('getAttribute')->with('saving_name')->andReturn('Model Saving');
        $mockModel->shouldReceive('getAttribute')->with('saving_amount')->andReturn(75.50);
        $mockModel->shouldReceive('getAttribute')->with('saving_goal')->andReturn(5600.0);

        // When
        $dto = SavingDto::fromModel($mockModel);

        // Then
        $this->assertInstanceOf(SavingDto::class, $dto);
        $this->assertEquals(20, $dto->id);
        $this->assertEquals(3, $dto->userId);
        $this->assertEquals('Model Saving', $dto->savingName);
        $this->assertEquals(75.50, $dto->savingAmount);
        $this->assertEquals(5600.0, $dto->savingGoal);
    }

    public function test_to_array_returns_correct_array(): void
    {
        // Given
        $dto = new SavingDto(
            id: 30,
            userId: 4,
            savingName: 'Array Saving',
            savingAmount: 99.99,
            savingGoal: 1600.0,
        );

        // When
        $array = $dto->toArray();

        // Then
        $this->assertIsArray($array);
        $this->assertEquals(
            [
                'id' => 30,
                'user_id' => 4,
                'saving_name' => 'Array Saving',
                'saving_amount' => 99.99,
                'saving_goal' => 1600.0,
            ],
            $array
        );
    }
}
