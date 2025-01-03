<?php


use App\Interfaces\UserSavingsServiceInterface;
use App\Models\User;
use App\Models\UserSaving;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SavingsTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function test_user_can_create_saving(): void
    {
        $user = User::factory()->create();
        $savingName = $this->faker->word;
        $savingAmount = 5632.23;
        $savingGoal = 10000;

        $response = $this->actingAs($user)->postJson('user-savings', [
            'user_id' => $user->id,
            'saving_name' => $savingName,
            'saving_amount' => $savingAmount,
            'saving_goal' => $savingGoal,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_savings', [
            'user_id' => $user->id,
            'saving_name' => $savingName,
            'saving_amount' => $savingAmount,
            'saving_goal' => $savingGoal,
        ]);
    }

    public function test_user_cannot_create_saving_if_saving_name_missing(): void
    {
        $user = User::factory()->create();
        $savingName = null;
        $savingAmount = 98732.23;
        $savingGoal = 100000;

        $response = $this->actingAs($user)->postJson('user-savings', [
            'user_id' => $user->id,
            'saving_name' => $savingName,
            'saving_amount' => $savingAmount,
            'saving_goal' => $savingGoal,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('user_savings', [
            'user_id' => $user->id,
            'saving_name' => $savingName,
            'saving_amount' => $savingAmount,
            'saving_goal' => $savingGoal,
        ]);
    }

    public function test_user_cannot_create_saving_if_saving_amount_missing(): void
    {
        $user = User::factory()->create();
        $savingName = $this->faker->word;
        $savingAmount = null;
        $savingGoal = 1000;

        $response = $this->actingAs($user)->postJson('user-savings', [
            'user_id' => $user->id,
            'saving_name' => $savingName,
            'saving_amount' => $savingAmount,
            'saving_goal' => $savingGoal,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('user_savings', [
            'user_id' => $user->id,
            'saving_name' => $savingName,
            'saving_amount' => $savingAmount,
            'saving_goal' => $savingGoal,
        ]);
    }

    public function test_user_cannot_create_saving_if_saving_amount_format_is_incorrect(): void
    {
        $user = User::factory()->create();
        $savingName = $this->faker->word;
        $savingAmount = 1622.1;
        $savingGoal = 2000;

        $response = $this->actingAs($user)->postJson('user-savings', [
            'user_id' => $user->id,
            'saving_name' => $savingName,
            'saving_amount' => $savingAmount,
            'saving_goal' => $savingGoal,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('user_savings', [
            'user_id' => $user->id,
            'saving_name' => $savingName,
            'saving_amount' => $savingAmount,
            'saving_goal' => $savingGoal,
        ]);
    }

    public function test_user_can_update_an_saving(): void
    {
        $user = User::factory()->create();
        $originalSavingName = $this->faker->word;
        $originalSavingAmount = 429922.23;
        $originalSavingGoal = 500000;

        $originalSaving = Usersaving::factory()->create([
            'user_id' => $user->id,
            'saving_name' => $originalSavingName,
            'saving_amount' => $originalSavingAmount,
            'saving_goal' => $originalSavingGoal,
        ]);

        $savingId = $originalSaving->id;
        $newSavingName = $this->faker->word;
        $newSavingAmount = 600.53;
        $newSavingGoal = 1500.54;

        $response = $this->actingAs($user)->putJson('user-savings/' . $savingId, [
            'id' => $savingId,
            'saving_name' => $newSavingName,
            'saving_amount' => $newSavingAmount,
            'saving_goal' => $newSavingGoal,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('user_savings', [
            'id' => $savingId,
            'user_id' => $user->id,
            'saving_name' => $newSavingName,
            'saving_amount' => $newSavingAmount,
            'saving_goal' => $newSavingGoal,
        ]);
    }

    public function test_user_cannot_update_an_saving_if_saving_name_is_missing(): void
    {
        $user = User::factory()->create();
        $originalSavingName = $this->faker->word;
        $originalSavingAmount = 3456.76;
        $originalSavingGoal = 3000;

        $originalSaving = Usersaving::factory()->create([
            'user_id' => $user->id,
            'saving_name' => $originalSavingName,
            'saving_amount' => $originalSavingAmount,
            'saving_goal' => $originalSavingGoal,
        ]);

        $savingId = $originalSaving->id;
        $newSavingName = null;
        $newSavingAmount = 1400.22;
        $newSavingGoal = 3400;

        $response = $this->actingAs($user)->putJson('user-savings/' . $savingId, [
            'id' => $savingId,
            'saving_name' => $newSavingName,
            'saving_amount' => $newSavingAmount,
            'saving_goal' => $newSavingGoal,
        ]);
        $response->assertStatus(422);
        $this->assertDatabaseMissing('user_savings', [
            'id' => $savingId,
            'user_id' => $user->id,
            'saving_name' => $newSavingName,
            'saving_amount' => $newSavingAmount,
            'saving_goal' => $newSavingGoal,
        ]);
    }

    public function test_user_cannot_delete_non_existing_saving(): void
    {
        $user = User::factory()->create();
        $nonExistingSavingId = 9999;

        $response = $this->actingAs($user)->deleteJson('user-savings/' . $nonExistingSavingId);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Saving not found.',
        ]);
    }

    public function test_user_cannot_delete_saving_without_permission(): void
    {
        $user = User::factory()->create();
        $saving = UserSaving::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->deleteJson('user-savings/' . $saving->id);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'You do not have permission to delete this saving.',
        ]);
    }

    public function test_user_can_delete_saving(): void
    {
        $user = User::factory()->create();
        $savingName = $this->faker->word;
        $savingAmount = 766.23;
        $savingGoal = 1230.23;

        $response = $this->actingAs($user)->postJson('user-savings', [
            'user_id' => $user->id,
            'saving_name' => $savingName,
            'saving_amount' => $savingAmount,
            'saving_goal' => $savingGoal,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_savings', [
            'user_id' => $user->id,
            'saving_name' => $savingName,
            'saving_amount' => $savingAmount,
            'saving_goal' => $savingGoal,
        ]);

        $savingId = $user->savings[0]['id'];

        $response = $this->actingAs($user)->deleteJson('user-savings/' . $savingId);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('user_savings', [
            'id' => $savingId,
            'user_id' => $user->id,
            'saving_name' => $savingName,
            'saving_amount' => $savingAmount,
            'saving_goal' => $savingGoal,
        ]);
    }

    public function test_user_cannot_update_non_existing_saving(): void
    {
        $user = User::factory()->create();
        $nonExistingSavingId = 9999;

        $response = $this->actingAs($user)->putJson('user-savings/' . $nonExistingSavingId, [
            'saving_name' => 'Updated Name',
            'saving_amount' => 100.00,
            'saving_goal' => 1005,
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Saving not found.',
        ]);
    }

    public function test_user_cannot_update_saving_without_permission(): void
    {
        $user = User::factory()->create();
        $saving = Usersaving::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->putJson('user-savings/' . $saving->id, [
            'saving_name' => 'Updated Name',
            'saving_amount' => 100.00,
            'saving_goal' => 321.54,
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'You do not have permission to update this saving.',
        ]);
    }

    public function test_generic_exception_handling(): void
    {
        $user = User::factory()->create();
        $saving = Usersaving::factory()->create();

        $this->app->bind(UserSavingsServiceInterface::class, function () {
            throw new \Exception('An unexpected error occurred.');
        });

        $response = $this->actingAs($user)->putJson('user-savings/' . $saving->id, [
            'saving_name' => 'Updated Name',
            'saving_amount' => 100.00,
            'saving_goal' => 1000,
        ]);

        $response->assertStatus(500);
        $response->assertJson([
            'message' => 'An unexpected error occurred.',
        ]);
    }

    public function test_user_cannot_update_an_saving_if_amount_is_missing(): void
    {
        $user = User::factory()->create();
        $originalSavingName = $this->faker->word;
        $originalSavingAmount = 100.54;
        $originalSavingGoal = 2000;

        $originalSaving = Usersaving::factory()->create([
            'user_id' => $user->id,
            'saving_name' => $originalSavingName,
            'saving_amount' => $originalSavingAmount,
            'saving_goal' => $originalSavingGoal,
        ]);

        $savingId = $originalSaving->id;
        $newSavingName = $this->faker->word;
        $newSavingAmount = null;
        $newSavingGoal = 1500;

        $response = $this->actingAs($user)->putJson('user-savings/' . $savingId, [
            'id' => $savingId,
            'saving_name' => $newSavingName,
            'saving_amount' => $newSavingAmount,
            'saving_goal' => $newSavingGoal,
        ]);
        $response->assertStatus(422);
        $this->assertDatabaseMissing('user_savings', [
            'id' => $savingId,
            'user_id' => $user->id,
            'saving_name' => $newSavingName,
            'saving_amount' => $newSavingAmount,
            'saving_goal' => $newSavingGoal,
        ]);
    }

    public function test_user_saving_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $saving = Usersaving::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $saving->user->id);
        $this->assertInstanceOf(User::class, $saving->user);
    }

    public function test_user_has_savings_total_amount_attribute(): void
    {
        $user = User::factory()->create();
        UserSaving::factory(3)->create([
            'user_id' => $user->id,
            'saving_amount' => 5000,
            'saving_goal' => 10000,
        ]);

        $this->assertEquals(15000, $user->savings_total_amount);
    }
}
