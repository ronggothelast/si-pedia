<?php

namespace Tests\Unit\Models;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_review(): void
    {
        $review = Review::factory()->create(['title' => 'Great Site']);
        $this->assertDatabaseHas('reviews', ['title' => 'Great Site']);
    }

    /** @test */
    public function it_has_correct_fillable_fields(): void
    {
        $review = new Review();
        $expected = ['title', 'type', 'description', 'image', 'avatar', 'views', 'status', 'reviewed_at'];
        $this->assertEquals($expected, $review->getFillable());
    }

    /** @test */
    public function it_defaults_status_to_pending(): void
    {
        $review = new Review(['title' => 'Test Review', 'type' => 'Blog', 'description' => 'Test']);
        $review->save();
        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'status' => 'pending']);
    }

    /** @test */
    public function it_can_be_accepted(): void
    {
        $review = Review::factory()->accepted()->create();
        $this->assertEquals('accepted', $review->status);
    }

    /** @test */
    public function it_can_be_declined(): void
    {
        $review = Review::factory()->declined()->create();
        $this->assertEquals('declined', $review->status);
    }

    /** @test */
    public function it_defaults_views_to_zero(): void
    {
        $review = Review::factory()->create(['views' => 0]);
        $this->assertEquals(0, $review->views);
    }
}
