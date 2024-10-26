<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login with valid credentials.
     *
     * @return void
     */
    public function test_login_with_valid_credentials()
    {

        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'user',
                'token',
            ]);
    }

    /**
     * Test login with invalid credentials.
     *
     * @return void
     */
    public function test_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'error' => 'Invalid Email and Password',
            ]);
    }

    /**
     * Test login validation error (missing fields).
     *
     * @return void
     */
    public function test_login_validation_error()
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'Validation error',
            ]);
    }

    /**
     * Test logout.
     *
     * @return void
     */
    public function test_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson(route('auth.logout'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'User logged out successfully',
                'success' => true,
            ]);
    }

    /**
     * Test user registration successfully.
     *
     * @return void
     */
    public function test_register_creates_new_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'message' => trans('User registered successfully'),
                'user' => [
                    'name' => 'John Doe',
                    'email' => 'johndoe@example.com',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    /**
     * Test registration validation fails.
     *
     * @return void
     */
    public function test_register_validation_fails()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors',
            ]);
    }

    /**
     * Test registration fails if user already exists.
     *
     * @return void
     */
    public function test_register_fails_if_user_exists()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $userData = [
            'name' => 'Jane Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'Validation error',
                'errors' => [
                    'The email has already been taken.',
                ],
            ]);
    }


    /**
     * Test forgot password sends email.
     *
     * @return void
     */
    public function test_forgot_password_sends_email()
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson(route('auth.password.forget'), [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password reset link sent.']);

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    /**
     * Test forgot password returns user not found error.
     *
     * @return void
     */
    public function test_forgot_password_user_not_found()
    {
        $response = $this->postJson(route('auth.password.forget'), [
            'email' => 'notfound@example.com',
        ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'User not found']);
    }

    /**
     * Test reset password successfully changes the user's password.
     *
     * @return void
     */
    public function test_reset_password()
    {
        $user = User::factory()->create(['password' => bcrypt('oldpassword')]);
        $token = Password::broker()->createToken($user);

        $response = $this->postJson(route('auth.password.reset'), [
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => $token,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => trans(Password::PASSWORD_RESET)]);

        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }

    /**
     * Test reset password returns validation error for invalid input.
     *
     * @return void
     */
    public function test_reset_password_validation_error()
    {
        $response = $this->postJson(route('auth.password.reset'), [
            'email' => 'invalidemail',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);
    }
}
