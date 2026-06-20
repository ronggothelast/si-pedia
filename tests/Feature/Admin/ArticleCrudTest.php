<?php

namespace Tests\Feature\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Admin CRUD Tests: Full create/read/update/delete operations for Articles, Dosen, Categories.
 */
class ArticleCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->admin = User::factory()->admin()->create();
    }

    // ============================================================
    // CREATE ARTICLE
    // ============================================================

    /** @test */
    public function admin_can_create_article(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/articles', [
            'title'       => 'New Article',
            'category_id' => $category->id,
            'writer'      => 'Admin',
            'status'      => 'active',
            'content'     => 'Article content here.',
            'created_at'  => '2026-06-20',
        ]);

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertDatabaseHas('articles', ['title' => 'New Article']);
    }

    /** @test */
    public function admin_can_create_article_with_image(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension not installed');
        }
        $category = Category::factory()->create();
        $image = UploadedFile::fake()->image('article.jpg', 800, 600);

        $this->actingAs($this->admin)->post('/admin/articles', [
            'title'       => 'Article With Image',
            'category_id' => $category->id,
            'writer'      => 'Admin',
            'status'      => 'active',
            'content'     => 'Content with image.',
            'image'       => $image,
            'created_at'  => '2026-06-20',
        ]);

        $this->assertDatabaseHas('articles', ['title' => 'Article With Image']);
        $article = Article::where('title', 'Article With Image')->first();
        Storage::disk('public')->assertExists($article->image);
    }

    /** @test */
    public function article_creation_requires_title(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/articles', [
            'category_id' => $category->id,
            'writer'      => 'Admin',
            'status'      => 'active',
            'content'     => 'Content.',
            'created_at'  => '2026-06-20',
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function article_creation_requires_valid_category(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/articles', [
            'title'       => 'Test',
            'category_id' => 99999,
            'writer'      => 'Admin',
            'status'      => 'active',
            'content'     => 'Content.',
            'created_at'  => '2026-06-20',
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function article_creation_requires_valid_status(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/articles', [
            'title'       => 'Test',
            'category_id' => $category->id,
            'writer'      => 'Admin',
            'status'      => 'invalid_status',
            'content'     => 'Content.',
            'created_at'  => '2026-06-20',
        ]);

        $response->assertSessionHasErrors('status');
    }

    /** @test */
    public function article_image_must_be_valid_image_file(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension not installed');
        }
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($this->admin)->post('/admin/articles', [
            'title'       => 'Test',
            'category_id' => $category->id,
            'writer'      => 'Admin',
            'status'      => 'active',
            'content'     => 'Content.',
            'image'       => $file,
            'created_at'  => '2026-06-20',
        ]);

        $response->assertSessionHasErrors('image');
    }

    // ============================================================
    // UPDATE ARTICLE
    // ============================================================

    /** @test */
    public function admin_can_update_article(): void
    {
        $article = Article::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->put("/admin/articles/{$article->id}", [
            'title'       => 'Updated Title',
            'category_id' => $category->id,
            'writer'      => 'Updated Writer',
            'status'      => 'draft',
            'content'     => 'Updated content.',
            'created_at'  => '2026-06-20',
        ]);

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertDatabaseHas('articles', [
            'id'     => $article->id,
            'title'  => 'Updated Title',
            'status' => 'draft',
        ]);
    }

    /** @test */
    public function admin_can_update_article_image(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension not installed');
        }
        $article = Article::factory()->create();
        $image = UploadedFile::fake()->image('new-image.jpg', 800, 600);

        $this->actingAs($this->admin)->put("/admin/articles/{$article->id}", [
            'title'       => $article->title,
            'category_id' => $article->category_id,
            'writer'      => $article->writer,
            'status'      => $article->status,
            'content'     => $article->content,
            'image'       => $image,
            'created_at'  => '2026-06-20',
        ]);

        $article->refresh();
        Storage::disk('public')->assertExists($article->image);
    }

    // ============================================================
    // DELETE ARTICLE
    // ============================================================

    /** @test */
    public function admin_can_delete_article(): void
    {
        $article = Article::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/articles/{$article->id}");

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertSoftDeleted('articles', ['id' => $article->id]);
    }

    // ============================================================
    // ARTICLE SEARCH
    // ============================================================

    /** @test */
    public function admin_can_search_articles(): void
    {
        Article::factory()->create(['title' => 'Laravel Tutorial']);
        Article::factory()->create(['title' => 'PHP Basics']);

        $response = $this->actingAs($this->admin)->get('/admin/articles?q=Laravel');
        $response->assertStatus(200);
        $response->assertSee('Laravel Tutorial');
        $response->assertDontSee('PHP Basics');
    }
}
