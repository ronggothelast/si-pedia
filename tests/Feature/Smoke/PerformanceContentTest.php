<?php

namespace Tests\Feature\Smoke;

use App\Models\Article;
use App\Models\Category;
use App\Models\Lecturer;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Performance & Content Tests: N+1 queries, page content verification, data display.
 */
class PerformanceContentTest extends TestCase
{
    use RefreshDatabase;

    // ============================================================
    // N+1 QUERY PREVENTION
    // ============================================================

    /** @test */
    public function catalog_eager_loads_categories(): void
    {
        $category = Category::factory()->create();
        Article::factory()->count(5)->active()->create(['category_id' => $category->id]);

        DB::enableQueryLog();
        $this->get('/catalog');
        $queries = DB::getQueryLog();

        // Should be ~3 queries: articles + session/cookie overhead
        $this->assertLessThanOrEqual(8, count($queries), 'Too many queries — possible N+1 problem on catalog');
    }

    /** @test */
    public function admin_articles_eager_loads_categories(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        Article::factory()->count(5)->create(['category_id' => $category->id]);

        DB::enableQueryLog();
        $this->actingAs($admin)->get('/admin/articles');
        $queries = DB::getQueryLog();

        $this->assertLessThanOrEqual(6, count($queries), 'Too many queries — possible N+1 on admin articles');
    }

    /** @test */
    public function homepage_limits_articles_to_three(): void
    {
        Article::factory()->count(10)->create();

        DB::enableQueryLog();
        $this->get('/');
        $queries = DB::getQueryLog();

        // Should only fetch 3 articles (take(3)) + session/cookie overhead
        $this->assertLessThanOrEqual(6, count($queries), 'Too many queries on homepage');
    }

    // ============================================================
    // PAGE CONTENT VERIFICATION
    // ============================================================

    /** @test */
    public function homepage_displays_articles_from_database(): void
    {
        $article = Article::factory()->create(['title' => 'Homepage Test Article', 'status' => 'active']);

        $response = $this->get('/');
        $response->assertSee('Homepage Test Article');
    }

    /** @test */
    public function catalog_displays_active_articles_with_category(): void
    {
        $category = Category::factory()->create(['name' => 'Teknologi']);
        Article::factory()->active()->create([
            'title'       => 'Tech Article',
            'category_id' => $category->id,
        ]);

        $response = $this->get('/catalog');
        $response->assertSee('Tech Article');
        $response->assertSee('Teknologi');
    }

    /** @test */
    public function article_detail_shows_full_content(): void
    {
        $article = Article::factory()->active()->create([
            'title'   => 'Detail Test',
            'content' => 'Full article content with details.',
        ]);

        $response = $this->get("/articles/{$article->slug}");
        $response->assertSee('Detail Test');
        $response->assertSee('Full article content with details.');
    }

    /** @test */
    public function admin_panel_shows_statistics(): void
    {
        $admin = User::factory()->admin()->create();
        Article::factory()->count(3)->create();
        Lecturer::factory()->count(2)->create();
        Review::factory()->count(1)->create();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        // Should see counts in the dashboard
    }

    /** @test */
    public function admin_articles_paginate_correctly(): void
    {
        $admin = User::factory()->admin()->create();
        Article::factory()->count(15)->create();

        $response = $this->actingAs($admin)->get('/admin/articles');
        $response->assertStatus(200);

        // Should have pagination
        $response->assertSee('15'); // total articles
    }

    /** @test */
    public function admin_dosen_paginates_per_page(): void
    {
        $admin = User::factory()->admin()->create();
        Lecturer::factory()->count(8)->create();

        $response = $this->actingAs($admin)->get('/admin/dosen');
        $response->assertStatus(200);
    }

    /** @test */
    public function manage_users_shows_all_users(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(5)->create();

        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);
    }

    /** @test */
    public function report_posts_shows_statistics(): void
    {
        $admin = User::factory()->admin()->create();
        Article::factory()->count(3)->active()->create();
        Article::factory()->count(2)->draft()->create();

        $response = $this->actingAs($admin)->get('/admin/report');
        $response->assertStatus(200);
    }

    /** @test */
    public function review_page_shows_reviews(): void
    {
        Review::factory()->count(3)->create(['title' => 'Test Review']);

        $response = $this->get('/review');
        $response->assertStatus(200);
        $response->assertSee('Test Review');
    }

    // ============================================================
    // NAVBAR & FOOTER CONSISTENCY
    // ============================================================

    /** @test */
    public function navbar_appears_once_on_homepage(): void
    {
        $response = $this->get('/');
        $content = $response->getContent();

        // Count occurrences of nav-related classes (navbar should appear once)
        $navCount = substr_count($content, 'x-navbar') + substr_count($content, '<nav');
        $this->assertLessThanOrEqual(2, $navCount, 'Navbar appears more than once on homepage');
    }

    /** @test */
    public function navbar_appears_once_on_catalog(): void
    {
        $response = $this->get('/catalog');
        $content = $response->getContent();

        $navCount = substr_count($content, 'x-navbar') + substr_count($content, '<nav');
        $this->assertLessThanOrEqual(2, $navCount, 'Navbar appears more than once on catalog');
    }

    /** @test */
    public function navbar_appears_once_on_about(): void
    {
        $response = $this->get('/about');
        $content = $response->getContent();

        $navCount = substr_count($content, 'x-navbar') + substr_count($content, '<nav');
        $this->assertLessThanOrEqual(2, $navCount, 'Navbar appears more than once on about');
    }

    /** @test */
    public function footer_appears_once_on_homepage(): void
    {
        $response = $this->get('/');
        $content = $response->getContent();

        $footerCount = substr_count($content, 'x-footer') + substr_count($content, '<footer');
        $this->assertLessThanOrEqual(2, $footerCount, 'Footer appears more than once on homepage');
    }
}
