<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Category CRUD Tests: Create and delete categories.
 */
class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    /** @test */
    public function admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'Teknologi',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Teknologi']);
    }

    /** @test */
    public function category_creation_requires_name(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', []);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function category_name_must_be_unique(): void
    {
        Category::factory()->create(['name' => 'Berita']);

        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'Berita',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function admin_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/categories/{$category->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function admin_can_view_categories_list(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get('/admin/categories');
        $response->assertStatus(200);
    }

    /** @test */
    public function categories_show_article_count(): void
    {
        $category = Category::factory()->create();
        \App\Models\Article::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('3'); // article count
    }
}
