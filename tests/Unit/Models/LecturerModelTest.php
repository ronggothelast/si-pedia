<?php

namespace Tests\Unit\Models;

use App\Models\Lecturer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturerModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_lecturer(): void
    {
        $lecturer = Lecturer::factory()->create(['full_name' => 'Dr. Budi']);
        $this->assertDatabaseHas('lecturers', ['full_name' => 'Dr. Budi']);
    }

    /** @test */
    public function it_has_correct_fillable_fields(): void
    {
        $lecturer = new Lecturer();
        $expected = [
            'full_name', 'nidn', 'nip', 'username', 'email', 'phone',
            'address', 'place_of_birth', 'date_of_birth', 'gender',
            'study_program', 'expertise', 'photo', 'status',
        ];
        $this->assertEquals($expected, $lecturer->getFillable());
    }

    /** @test */
    public function it_defaults_status_to_waiting(): void
    {
        $lecturer = new Lecturer(['full_name' => 'Dr. Test', 'nidn' => '999999999', 'username' => 'testuser', 'address' => 'Jakarta']);
        $lecturer->save();
        $this->assertDatabaseHas('lecturers', ['id' => $lecturer->id, 'status' => 'waiting']);
    }

    /** @test */
    public function it_can_be_active(): void
    {
        $lecturer = Lecturer::factory()->active()->create();
        $this->assertEquals('active', $lecturer->status);
    }

    /** @test */
    public function it_can_be_waiting(): void
    {
        $lecturer = Lecturer::factory()->waiting()->create();
        $this->assertEquals('waiting', $lecturer->status);
    }
}
