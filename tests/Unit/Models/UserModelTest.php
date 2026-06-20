<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user(): void
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $this->assertDatabaseHas('users', ['name' => 'John Doe']);
    }

    /** @test */
    public function it_has_correct_fillable_fields(): void
    {
        $user = new User();
        $expected = ['name', 'username', 'email', 'password', 'role', 'study_program', 'force', 'avatar'];
        $this->assertEquals($expected, $user->getFillable());
    }

    /** @test */
    public function it_hides_password_and_remember_token(): void
    {
        $user = new User();
        $this->assertContains('password', $user->getHidden());
        $this->assertContains('remember_token', $user->getHidden());
    }

    /** @test */
    public function it_defaults_role_to_user(): void
    {
        $user = new User(['name' => 'Test', 'email' => 'test@example.com', 'password' => bcrypt('password')]);
        $user->save();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'user']);
    }

    /** @test */
    public function it_can_be_admin(): void
    {
        $user = User::factory()->admin()->create();
        $this->assertEquals('admin', $user->role);
    }

    /** @test */
    public function it_hashes_password_on_creation(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);
        $this->assertNotEquals('secret123', $user->password);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    /** @test */
    public function it_casts_email_verified_at_to_datetime(): void
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }

    /** @test */
    public function it_can_be_created_as_regular_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->assertEquals('user', $user->role);
        $this->assertNotEquals('admin', $user->role);
    }
}
