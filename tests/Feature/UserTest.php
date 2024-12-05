<?php

namespace Tests\Feature;

use App\Models\User;
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

        $response = $this->actingAs($user)->patchJson('my-profile/update', [
            'id' => $user->id,
            'name' => $newName,
            'email' => $newEmail,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $newName,
            'email' => $newEmail,
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => $oldName,
            'email' => $oldEmail,
        ]);
    }
}
