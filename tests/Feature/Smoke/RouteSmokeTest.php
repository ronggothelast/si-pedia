<?php

namespace Tests\Feature\Smoke;

use App\Models\Article;
use App\Models\Category;
use App\Models\Lecturer;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Smoke Test: Verifies every route returns the correct HTTP status code.
 * Tests: public pages, auth-required pages, admin-only pages, 404 pages.
 */
class RouteSmokeTest extends TestCase
{
    use RefreshDatabase;

    // ============================================================
    // PUBLIC ROUTES — Guest can access (200 OK)
    // ============================================================

    /** @test */
    public function homepage_returns_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function about_returns_200(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    /** @test */
    public function catalog_returns_200(): void
    {
        $response = $this->get('/catalog');
        $response->assertStatus(200);
    }

    /** @test */
    public function review_returns_200(): void
    {
        $response = $this->get('/review');
        $response->assertStatus(200);
    }

    /** @test */
    public function article_detail_returns_200_for_active_article(): void
    {
        $article = Article::factory()->active()->create();
        $response = $this->get("/articles/{$article->slug}");
        $response->assertStatus(200);
    }

    /** @test */
    public function article_detail_returns_404_for_draft_article(): void
    {
        $article = Article::factory()->draft()->create();
        $response = $this->get("/articles/{$article->slug}");
        $response->assertStatus(404);
    }

    /** @test */
    public function article_detail_returns_404_for_nonexistent(): void
    {
        $response = $this->get('/articles/99999');
        $response->assertStatus(404);
    }

    // ============================================================
    // AUTH ROUTES — Guest can access login/register (200 OK)
    // ============================================================

    /** @test */
    public function login_page_returns_200(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function register_page_returns_200(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /** @test */
    public function forgot_password_page_returns_200(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    // ============================================================
    // AUTH REQUIRED ROUTES — Guest gets redirected (302)
    // ============================================================

    /** @test */
    public function profile_requires_auth(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function profile_edit_requires_auth(): void
    {
        $response = $this->get('/profile/edit');
        $response->assertRedirect('/login');
    }

    // ============================================================
    // AUTH REQUIRED ROUTES — Logged-in user can access (200)
    // ============================================================

    /** @test */
    public function profile_returns_200_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
    }

    /** @test */
    public function profile_edit_returns_200_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile/edit');
        $response->assertStatus(200);
    }

    // ============================================================
    // ADMIN ROUTES — Guest gets redirected to login (302)
    // ============================================================

    /** @test */
    public function admin_panel_requires_auth(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function admin_articles_requires_auth(): void
    {
        $response = $this->get('/admin/articles');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function admin_dosen_requires_auth(): void
    {
        $response = $this->get('/admin/dosen');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function admin_categories_requires_auth(): void
    {
        $response = $this->get('/admin/categories');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function admin_users_requires_auth(): void
    {
        $response = $this->get('/admin/users');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function admin_report_requires_auth(): void
    {
        $response = $this->get('/admin/report');
        $response->assertRedirect('/login');
    }

    // ============================================================
    // ADMIN ROUTES — Regular user gets 403
    // ============================================================

    /** @test */
    public function admin_panel_returns_403_for_regular_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_articles_returns_403_for_regular_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/admin/articles');
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_dosen_returns_403_for_regular_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/admin/dosen');
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_categories_returns_403_for_regular_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/admin/categories');
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_users_returns_403_for_regular_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/admin/users');
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_report_returns_403_for_regular_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/admin/report');
        $response->assertStatus(403);
    }

    // ============================================================
    // ADMIN ROUTES — Admin can access (200)
    // ============================================================

    /** @test */
    public function admin_panel_returns_200_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_articles_returns_200_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        Article::factory()->count(3)->create();
        $response = $this->actingAs($admin)->get('/admin/articles');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_articles_create_returns_200_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        Category::factory()->count(2)->create();
        $response = $this->actingAs($admin)->get('/admin/articles/create');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_dosen_returns_200_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        Lecturer::factory()->count(3)->create();
        $response = $this->actingAs($admin)->get('/admin/dosen');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_dosen_create_returns_200_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get('/admin/dosen/create');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_categories_returns_200_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        Category::factory()->count(3)->create();
        $response = $this->actingAs($admin)->get('/admin/categories');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_users_returns_200_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(5)->create();
        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_report_returns_200_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        Article::factory()->count(3)->create();
        $response = $this->actingAs($admin)->get('/admin/report');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_homepage_edit_returns_200_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get('/admin/homepage/edit');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_pages_create_returns_200_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get('/admin/pages/create');
        $response->assertStatus(200);
    }

    // ============================================================
    // GUEST ONLY ROUTES — Logged-in user gets redirected
    // ============================================================

    /** @test */
    public function login_redirects_authenticated_user(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/');
    }

    /** @test */
    public function register_redirects_authenticated_user(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/register');
        $response->assertRedirect('/');
    }
}
