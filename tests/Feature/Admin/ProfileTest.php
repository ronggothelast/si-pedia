<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Profile Tests: User profile view, edit, and update operations.
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function user_can_view_own_profile(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    /** @test */
    public function admin_sees_admin_profile_view(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get('/profile');
        $response->assertStatus(200);
    }

    /** @test */
    public function regular_user_sees_user_profile_view(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_edit_profile_form(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile/edit');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_update_email(): void
    {
        $user = User::factory()->create(['email' => 'old@example.com']);

        $response = $this->actingAs($user)->put('/profile', [
            'email' => 'new@example.com',
        ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'new@example.com']);
    }

    /** @test */
    public function user_can_update_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile', [
            'email'    => $user->email,
            'password' => 'newpassword123',
        ]);

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /** @test */
    public function user_can_update_avatar(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension not installed');
        }
        $user = User::factory()->create();
        $avatar = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        $this->actingAs($user)->put('/profile', [
            'email'  => $user->email,
            'avatar' => $avatar,
        ]);

        $user->refresh();
        Storage::disk('public')->assertExists($user->avatar);
    }

    /** @test */
    public function user_can_update_username(): void
    {
        $user = User::factory()->create(['username' => 'oldname']);

        $this->actingAs($user)->put('/profile', [
            'email'    => $user->email,
            'username' => 'newname',
        ]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'username' => 'newname']);
    }

    /** @test */
    public function email_must_be_unique_on_update(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);
        $user = User::factory()->create(['email' => 'mine@example.com']);

        $response = $this->actingAs($user)->put('/profile', [
            'email' => 'taken@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function email_is_required_on_update(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile', []);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_is_optional_on_update(): void
    {
        $user = User::factory()->create();
        $originalPassword = $user->password;

        $this->actingAs($user)->put('/profile', [
            'email' => $user->email,
        ]);

        $user->refresh();
        $this->assertEquals($originalPassword, $user->password);
    }
}
