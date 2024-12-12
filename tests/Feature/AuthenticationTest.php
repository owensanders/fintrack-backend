<?php

namespace Tests\Feature;

use App\Models\User;
use App\Exceptions\AuthenticationException;
use App\Services\AuthenticationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user',
                'token',
            ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_login_error_handling_with_unexpected_error(): void
    {
        $this->mock(AuthenticationService::class)
            ->shouldReceive('login')
            ->andThrow(new AuthenticationException('An unexpected error occurred during login.', 500));

        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'error' => 'An unexpected error occurred during login.',
            ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $user->createToken('test-token')->plainTextToken;
        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/logout');
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logout successful.',
            ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => get_class($user),
        ]);
    }

    public function test_logout_error_handling_with_unexpected_error(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->mock(AuthenticationService::class)
            ->shouldReceive('logout')
            ->andThrow(new AuthenticationException('An error occurred while logging out.', 500));

        $response = $this->post('/logout');

        $response->assertStatus(500)
            ->assertJson([
                'error' => 'An error occurred while logging out.',
            ]);
    }


    public function test_new_users_can_register(): void
    {
        $email = $this->faker->email;

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user',
                'token',
            ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => $email,
        ]);
    }

    public function test_registration_error_handling_with_unexpected_error(): void
    {
        $this->mock(AuthenticationService::class)
            ->shouldReceive('register')
            ->andThrow(new AuthenticationException('An error occurred during registration.', 500));

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'error' => 'An error occurred during registration.',
            ]);
    }
}
