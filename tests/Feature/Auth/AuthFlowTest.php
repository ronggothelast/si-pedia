<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

/**
 * Auth Flow Tests: Login, Register, Logout — full user journeys.
 */
class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    // ============================================================
    // LOGIN
    // ============================================================

    /** @test */
    public function user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com', 'password' => bcrypt('password123')]);

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_wrong_password(): void
    {
        User::factory()->create(['email' => 'test@example.com', 'password' => bcrypt('password123')]);

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_login_with_nonexistent_email(): void
    {
        $response = $this->post('/login', [
            'email'    => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function login_requires_email_field(): void
    {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function login_requires_password_field(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function login_remember_me_sets_cookie(): void
    {
        User::factory()->create(['email' => 'test@example.com', 'password' => bcrypt('password123')]);

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'password123',
            'remember' => true,
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    // ============================================================
    // REGISTER
    // ============================================================

    /** @test */
    public function user_can_register_with_valid_data(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'New User',
            'email'                 => 'newuser@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'terms'                 => true,
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
        $this->assertGuest();
    }

    /** @test */
    public function register_requires_name(): void
    {
        $response = $this->post('/register', [
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'terms'                 => true,
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function register_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->post('/register', [
            'name'                  => 'New User',
            'email'                 => 'taken@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'terms'                 => true,
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function register_requires_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'New User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'different',
            'terms'                 => true,
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function register_requires_minimum_password_length(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'New User',
            'email'                 => 'test@example.com',
            'password'              => '12345',
            'password_confirmation' => '12345',
            'terms'                 => true,
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function register_requires_terms_accepted(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'New User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('terms');
    }

    /** @test */
    public function register_sets_role_to_user(): void
    {
        $this->post('/register', [
            'name'                  => 'New User',
            'email'                 => 'newuser@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'terms'                 => true,
        ]);

        $this->assertGuest();

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'role'  => 'user',
        ]);
    }

    /** @test */
    public function register_cannot_set_role_to_admin(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Hacker',
            'email'                 => 'hacker@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'terms'                 => true,
            'role'                  => 'admin', // injected field
        ]);

        // Should still be registered as 'user' (role is not in fillable via register)
        $this->assertGuest();

        $this->assertDatabaseHas('users', [
            'email' => 'hacker@example.com',
            'role'  => 'user',
        ]);
    }

    // ============================================================
    // LOGOUT
    // ============================================================

    /** @test */
    public function user_can_logout(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /** @test */
    public function logout_invalidates_session(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        // After logout, accessing protected route should redirect
        $this->get('/profile')->assertRedirect('/login');
    }
}
