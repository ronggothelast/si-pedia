<?php

namespace Tests\Feature\Admin;

use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Dosen CRUD Tests: Full create/read/update/delete operations for Lecturers.
 */
class DosenCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->admin = User::factory()->admin()->create();
    }

    /** @test */
    public function admin_can_create_lecturer(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/dosen', [
            'nidn'     => '1234567890',
            'username' => 'DrBudi',
            'address'  => 'Jakarta',
        ]);

        $response->assertRedirect(route('admin.dosen.index'));
        $this->assertDatabaseHas('lecturers', ['nidn' => '1234567890', 'username' => 'DrBudi']);
    }

    /** @test */
    public function admin_can_create_lecturer_with_photo(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension not installed');
        }
        $photo = UploadedFile::fake()->image('photo.jpg', 300, 300);

        $this->actingAs($this->admin)->post('/admin/dosen', [
            'nidn'     => '1234567890',
            'username' => 'DrBudi',
            'address'  => 'Jakarta',
            'photo'    => $photo,
        ]);

        $lecturer = Lecturer::where('nidn', '1234567890')->first();
        Storage::disk('public')->assertExists($lecturer->photo);
    }

    /** @test */
    public function lecturer_creation_requires_nidn(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/dosen', [
            'username' => 'DrBudi',
            'address'  => 'Jakarta',
        ]);

        $response->assertSessionHasErrors('nidn');
    }

    /** @test */
    public function lecturer_creation_requires_username(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/dosen', [
            'nidn'    => '1234567890',
            'address' => 'Jakarta',
        ]);

        $response->assertSessionHasErrors('username');
    }

    /** @test */
    public function lecturer_creation_requires_address(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/dosen', [
            'nidn'     => '1234567890',
            'username' => 'DrBudi',
        ]);

        $response->assertSessionHasErrors('address');
    }

    /** @test */
    public function admin_can_update_lecturer(): void
    {
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($this->admin)->put("/admin/dosen/{$lecturer->id}", [
            'nidn'     => '9999999999',
            'username' => 'UpdatedName',
            'address'  => 'Bandung',
        ]);

        $response->assertRedirect(route('admin.dosen.index'));
        $this->assertDatabaseHas('lecturers', [
            'id'       => $lecturer->id,
            'nidn'     => '9999999999',
            'username' => 'UpdatedName',
            'address'  => 'Bandung',
        ]);
    }

    /** @test */
    public function admin_can_delete_lecturer(): void
    {
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/dosen/{$lecturer->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('lecturers', ['id' => $lecturer->id]);
    }

    /** @test */
    public function admin_can_search_lecturers_by_nidn(): void
    {
        Lecturer::factory()->create(['nidn' => '111111111']);
        Lecturer::factory()->create(['nidn' => '222222222']);

        $response = $this->actingAs($this->admin)->get('/admin/dosen?q=111111111');
        $response->assertStatus(200);
        $response->assertSee('111111111');
    }

    /** @test */
    public function admin_can_view_lecturer_acc_page(): void
    {
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($this->admin)->get("/admin/dosen/{$lecturer->id}/acc");
        $response->assertStatus(200);
    }

    /** @test */
    public function dosen_pagination_works(): void
    {
        Lecturer::factory()->count(10)->create();

        $response = $this->actingAs($this->admin)->get('/admin/dosen');
        $response->assertStatus(200);
    }
}
