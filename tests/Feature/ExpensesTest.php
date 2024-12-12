<?php

namespace Tests\Feature;

use App\Interfaces\UserExpensesServiceInterface;
use App\Models\User;
use App\Models\UserExpense;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExpensesTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function test_user_can_create_expense(): void
    {
        $user = User::factory()->create();
        $expenseName = $this->faker->word;
        $expenseAmount = 5632.23;

        $response = $this->actingAs($user)->postJson('user-expenses', [
            'user_id' => $user->id,
            'expense_name' => $expenseName,
            'expense_amount' => $expenseAmount,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_expenses', [
            'user_id' => $user->id,
            'expense_name' => $expenseName,
            'expense_amount' => $expenseAmount,
        ]);
    }

    public function test_user_cannot_delete_non_existing_expense(): void
    {
        $user = User::factory()->create();
        $nonExistingExpenseId = 9999;

        $response = $this->actingAs($user)->deleteJson('user-expenses/' . $nonExistingExpenseId);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Expense not found.',
        ]);
    }

    public function test_user_cannot_delete_expense_without_permission(): void
    {
        $user = User::factory()->create();
        $expense = UserExpense::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->deleteJson('user-expenses/' . $expense->id);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'You do not have permission to delete this expense.',
        ]);
    }

    public function test_user_can_update_expense(): void
    {
        $user = User::factory()->create();
        $expenseName = $this->faker->word;
        $expenseAmount = 766.23;

        $response = $this->actingAs($user)->postJson('user-expenses', [
            'user_id' => $user->id,
            'expense_name' => $expenseName,
            'expense_amount' => $expenseAmount,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_expenses', [
            'user_id' => $user->id,
            'expense_name' => $expenseName,
            'expense_amount' => $expenseAmount,
        ]);

        $expenseId = $user->expenses[0]['id'];

        $response = $this->actingAs($user)->deleteJson('user-expenses/' . $expenseId);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('user_expenses', [
            'id' => $expenseId,
            'user_id' => $user->id,
            'expense_name' => $expenseName,
            'expense_amount' => $expenseAmount,
        ]);
    }

    public function test_user_cannot_update_non_existing_expense(): void
    {
        $user = User::factory()->create();
        $nonExistingExpenseId = 9999;

        $response = $this->actingAs($user)->putJson('user-expenses/' . $nonExistingExpenseId, [
            'expense_name' => 'Updated Name',
            'expense_amount' => 100.00,
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Expense not found.',
        ]);
    }

    public function test_user_cannot_update_expense_without_permission(): void
    {
        $user = User::factory()->create();
        $expense = UserExpense::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->putJson('user-expenses/' . $expense->id, [
            'expense_name' => 'Updated Name',
            'expense_amount' => 100.00,
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'You do not have permission to update this expense.',
        ]);
    }

    public function test_generic_exception_handling(): void
    {
        $user = User::factory()->create();
        $expense = UserExpense::factory()->create();

        $this->app->bind(UserExpensesServiceInterface::class, function () {
            throw new \Exception('An unexpected error occurred.');
        });

        $response = $this->actingAs($user)->putJson('user-expenses/' . $expense->id, [
            'expense_name' => 'Updated Name',
            'expense_amount' => 100.00,
        ]);

        $response->assertStatus(500);
        $response->assertJson([
            'message' => 'An unexpected error occurred.',
        ]);
    }

    public function test_user_cannot_update_an_expense_if_amount_is_missing(): void
    {
        $user = User::factory()->create();
        $originalExpenseName = $this->faker->word;
        $originalExpenseAmount = 100.54;

        $originalExpense = UserExpense::factory()->create([
            'user_id' => $user->id,
            'expense_name' => $originalExpenseName,
            'expense_amount' => $originalExpenseAmount,
        ]);

        $expenseId = $originalExpense->id;
        $newExpenseName = $this->faker->word;
        $newExpenseAmount = null;

        $response = $this->actingAs($user)->putJson('user-expenses/' . $expenseId, [
            'id' => $expenseId,
            'expense_name' => $newExpenseName,
            'expense_amount' => $newExpenseAmount,
        ]);
        $response->assertStatus(422);
        $this->assertDatabaseMissing('user_expenses', [
            'id' => $expenseId,
            'user_id' => $user->id,
            'expense_name' => $newExpenseName,
            'expense_amount' => $newExpenseAmount,
        ]);
    }

    public function test_user_expense_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $expense = UserExpense::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $expense->user->id);
        $this->assertInstanceOf(User::class, $expense->user);
    }
}
