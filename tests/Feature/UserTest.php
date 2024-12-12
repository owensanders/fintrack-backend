<?php

namespace Tests\Feature;

use App\Exceptions\UnauthorisedUpdateException;
use App\Models\User;
use App\Interfaces\UserServiceInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function test_user_can_update_profile(): void
    {
        $oldName = $this->faker->name;
        $oldEmail = $this->faker->unique()->safeEmail;
        $newName = $this->faker->name;
        $newEmail = $this->faker->unique()->safeEmail;

        $user = User::factory()->create([
            'name' => $oldName,
            'email' => $oldEmail,
        ]);

        $response = $this->actingAs($user)->putJson('my-profile', [
            'id' => $user->id,
            'name' => $newName,
            'email' => $newEmail,
            'monthly_income' => 2568.34,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $newName,
            'email' => $newEmail,
            'monthly_income' => 2568.34,
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => $oldName,
            'email' => $oldEmail,
        ]);
    }

    public function test_user_update_profile_fails(): void
    {
        $oldName = $this->faker->name;
        $oldEmail = $this->faker->unique()->safeEmail;
        $newName = $this->faker->name;
        $newEmail = $this->faker->unique()->safeEmail;

        $user = User::factory()->create([
            'name' => $oldName,
            'email' => $oldEmail,
        ]);

        $this->mock(UserServiceInterface::class)
            ->shouldReceive('update')
            ->once()
            ->andReturn(null);

        $response = $this->actingAs($user)->putJson('my-profile', [
            'id' => $user->id,
            'name' => $newName,
            'email' => $newEmail,
            'monthly_income' => 2568.34,
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment(['message' => 'The updated user could no be found.']);
    }

    public function test_user_update_profile_validation_failure(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->putJson('my-profile', [
            'id' => $user->id,
            'email' => $this->faker->unique()->safeEmail,
        ]);
        $response->assertStatus(422);
        $response = $this->actingAs($user)->putJson('my-profile', [
            'id' => $user->id,
            'name' => $this->faker->name,
        ]);
        $response->assertStatus(422);
    }

    public function test_user_update_profile_user_not_found(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('my-profile', [
            'id' => 9999,  // Non-existent user ID
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'monthly_income' => 2568.34,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The selected id is invalid.",
            "errors" => [
                "id" => [
                    "The selected id is invalid."
                ]
            ]
        ]);
    }

    public function test_user_cannot_update_other_users_profile(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($user)->putJson('my-profile', [
            'id' => $otherUser->id,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'monthly_income' => 2568.34,
        ]);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_update_profile(): void
    {
        $response = $this->putJson('my-profile', [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'monthly_income' => 2568.34,
        ]);

        $response->assertStatus(401);
    }

    public function test_user_update_profile_missing_required_name_field(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('my-profile', [
            'name' => null,
            'email' => $this->faker->unique()->safeEmail,
            'monthly_income' => 2568.34,
        ]);

        $response->assertStatus(422);
    }

    public function test_user_cannot_update_others_profile_due_to_unauthorised_update(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->mock(UserServiceInterface::class)
            ->shouldReceive('update')
            ->once()
            ->andThrow(new UnauthorisedUpdateException());

        $response = $this->actingAs($user)->putJson('my-profile', [
            'id' => $otherUser->id,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'monthly_income' => 2568.34,
        ]);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'You are not authorised to update this profile.']);
    }

}
