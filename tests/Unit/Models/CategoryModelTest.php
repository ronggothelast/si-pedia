<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_category(): void
    {
        $category = Category::factory()->create(['name' => 'Berita']);
        $this->assertDatabaseHas('categories', ['name' => 'Berita']);
    }

    /** @test */
    public function it_has_correct_fillable_fields(): void
    {
        $category = new Category();
        $expected = ['name', 'color'];
        $this->assertEquals($expected, $category->getFillable());
    }

    /** @test */
    public function it_has_many_articles(): void
    {
        $category = Category::factory()->create();
        Article::factory()->count(3)->create(['category_id' => $category->id]);
        $this->assertCount(3, $category->articles);
    }

    /** @test */
    public function it_defaults_color_to_8cbcff(): void
    {
        $category = new Category(['name' => 'TestCategory']);
        $category->save();
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'color' => '#8cbcff']);
    }

    /** @test */
    public function it_can_count_articles(): void
    {
        $category = Category::factory()->create();
        Article::factory()->count(5)->create(['category_id' => $category->id]);
        $category->loadCount('articles');
        $this->assertEquals(5, $category->articles_count);
    }
}
