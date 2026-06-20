<?php

namespace Tests\Unit\Models;

use App\Models\Article;
use App\Models\Category;
use App\Models\Lecturer;
use App\Models\Page;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_article(): void
    {
        $article = Article::factory()->create(['title' => 'Test Article']);
        $this->assertDatabaseHas('articles', ['title' => 'Test Article']);
    }

    /** @test */
    public function it_belongs_to_a_category(): void
    {
        $category = Category::factory()->create();
        $article = Article::factory()->create(['category_id' => $category->id]);
        $this->assertTrue($article->category->is($category));
    }

    /** @test */
    public function it_has_correct_fillable_fields(): void
    {
        $article = new Article();
        $expected = ['title', 'slug', 'category_id', 'writer', 'status', 'content', 'image', 'views', 'scheduled_at'];
        $this->assertEquals($expected, $article->getFillable());
    }

    /** @test */
    public function it_defaults_status_to_active(): void
    {
        $article = new Article(['title' => 'Test', 'writer' => 'Admin', 'content' => 'Test']);
        $article->save();
        $this->assertDatabaseHas('articles', ['id' => $article->id, 'status' => 'active']);
    }

    /** @test */
    public function it_can_be_draft(): void
    {
        $article = Article::factory()->draft()->create();
        $this->assertEquals('draft', $article->status);
    }

    /** @test */
    public function it_defaults_views_to_zero(): void
    {
        $article = Article::factory()->create(['views' => 0]);
        $this->assertEquals(0, $article->views);
    }
}
