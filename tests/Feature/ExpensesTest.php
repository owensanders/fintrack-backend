<?php

namespace Tests\Feature;

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

    public function test_user_cannot_create_expense_if_expense_name_missing(): void
    {
        $user = User::factory()->create();
        $expenseName = null;
        $expenseAmount = 98732.23;

        $response = $this->actingAs($user)->postJson('user-expenses', [
            'user_id' => $user->id,
            'expense_name' => $expenseName,
            'expense_amount' => $expenseAmount,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('user_expenses', [
            'user_id' => $user->id,
            'expense_name' => $expenseName,
            'expense_amount' => $expenseAmount,
        ]);
    }

    public function test_user_cannot_create_expense_if_expense_amount_missing(): void
    {
        $user = User::factory()->create();
        $expenseName = $this->faker->word;
        $expenseAmount = null;

        $response = $this->actingAs($user)->postJson('user-expenses', [
            'user_id' => $user->id,
            'expense_name' => $expenseName,
            'expense_amount' => $expenseAmount,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('user_expenses', [
            'user_id' => $user->id,
            'expense_name' => $expenseName,
            'expense_amount' => $expenseAmount,
        ]);
    }

    public function test_user_cannot_create_expense_if_expense_amount_format_is_incorrect(): void
    {
        $user = User::factory()->create();
        $expenseName = $this->faker->word;
        $expenseAmount = 1622.1;

        $response = $this->actingAs($user)->postJson('user-expenses', [
            'user_id' => $user->id,
            'expense_name' => $expenseName,
            'expense_amount' => $expenseAmount,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('user_expenses', [
            'user_id' => $user->id,
            'expense_name' => $expenseName,
            'expense_amount' => $expenseAmount,
        ]);
    }

    public function test_user_can_delete_an_expense(): void
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

    public function test_user_can_update_an_expense(): void
    {
        $user = User::factory()->create();
        $originalExpenseName = $this->faker->word;
        $originalExpenseAmount = 429922.23;

        $originalExpense = UserExpense::factory()->create([
            'user_id' => $user->id,
            'expense_name' => $originalExpenseName,
            'expense_amount' => $originalExpenseAmount,
        ]);

        $expenseId = $originalExpense->id;
        $newExpenseName = $this->faker->word;
        $newExpenseAmount = 600.53;

        $response = $this->actingAs($user)->putJson('user-expenses/' . $expenseId, [
            'id' => $expenseId,
            'expense_name' => $newExpenseName,
            'expense_amount' => $newExpenseAmount,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('user_expenses', [
            'id' => $expenseId,
            'user_id' => $user->id,
            'expense_name' => $newExpenseName,
            'expense_amount' => $newExpenseAmount,
        ]);
    }

    public function test_user_cannot_update_an_expense_if_expense_name_is_missing(): void
    {
        $user = User::factory()->create();
        $originalExpenseName = $this->faker->word;
        $originalExpenseAmount = 3456.76;

        $originalExpense = UserExpense::factory()->create([
            'user_id' => $user->id,
            'expense_name' => $originalExpenseName,
            'expense_amount' => $originalExpenseAmount,
        ]);

        $expenseId = $originalExpense->id;
        $newExpenseName = null;
        $newExpenseAmount = 1400.22;

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
