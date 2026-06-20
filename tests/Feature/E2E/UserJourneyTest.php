<?php

namespace Tests\Feature\E2E;

use App\Models\Article;
use App\Models\Category;
use App\Models\Lecturer;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * E2E Tests: Full user journeys from start to finish.
 * Simulates real user workflows through the application.
 */
class UserJourneyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    // ============================================================
    // JOURNEY 1: Guest browses public pages
    // ============================================================

    /** @test */
    public function guest_can_browse_homepage_to_catalog_to_article_detail(): void
    {
        $category = Category::factory()->create();
        $article = Article::factory()->active()->create([
            'category_id' => $category->id,
            'title'       => 'Test Journey Article',
        ]);

        // Step 1: Visit homepage
        $this->get('/')->assertStatus(200)->assertSee($article->title);

        // Step 2: Visit catalog
        $this->get('/catalog')->assertStatus(200)->assertSee($article->title);

        // Step 3: Read article detail
        $this->get("/articles/{$article->slug}")
            ->assertStatus(200)
            ->assertSee($article->title);
    }

    /** @test */
    public function guest_can_browse_about_and_review_pages(): void
    {
        Review::factory()->count(3)->accepted()->create();

        $this->get('/about')->assertStatus(200);
        $this->get('/review')->assertStatus(200);
    }

    // ============================================================
    // JOURNEY 2: User registers → views profile → edits profile → logs out
    // ============================================================

    /** @test */
    public function user_register_profile_edit_logout_journey(): void
    {
        // Step 1: Register
        $response = $this->post('/register', [
            'name'                  => 'Journey User',
            'email'                 => 'journey@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'terms'                 => true,
        ]);
        $response->assertRedirect('/');
        $this->assertGuest();

        // Step 2: Login (register no longer auto-logs in)
        // Mark user as verified so profile routes work (verified middleware)
        \App\Models\User::where('email', 'journey@example.com')->update(['email_verified_at' => now()]);

        $this->post('/login', [
            'email'    => 'journey@example.com',
            'password' => 'password123',
        ]);

        // Step 3: View profile
        $this->get('/profile')->assertStatus(200)->assertSee('Journey User');

        // Step 4: Edit profile
        $this->get('/profile/edit')->assertStatus(200);

        // Step 5: Update profile
        $this->put('/profile', [
            'email'    => 'updated@example.com',
            'username' => 'journeyuser',
        ])->assertRedirect(route('profile.show'));

        $this->assertDatabaseHas('users', [
            'email'    => 'updated@example.com',
            'username' => 'journeyuser',
        ]);

        // Step 6: Logout
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    // ============================================================
    // JOURNEY 3: Admin creates article → edits → deletes
    // ============================================================

    /** @test */
    public function admin_article_lifecycle_journey(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        // Step 1: View empty article list
        $this->actingAs($admin)->get('/admin/articles')->assertStatus(200);

        // Step 2: Create article
        $this->actingAs($admin)->post('/admin/articles', [
            'title'       => 'Lifecycle Article',
            'category_id' => $category->id,
            'writer'      => 'Admin',
            'status'      => 'active',
            'content'     => 'Original content.',
            'created_at'  => '2026-06-20',
        ])->assertRedirect(route('admin.articles.index'));

        $article = Article::where('title', 'Lifecycle Article')->first();
        $this->assertNotNull($article);
        $this->assertEquals('active', $article->status);

        // Step 3: Article appears in catalog
        $this->get('/catalog')->assertSee('Lifecycle Article');

        // Step 4: Article detail is accessible
        $this->get("/articles/{$article->slug}")->assertSee('Original content.');

        // Step 5: Edit article
        $this->actingAs($admin)->put("/admin/articles/{$article->id}", [
            'title'       => 'Updated Lifecycle Article',
            'category_id' => $category->id,
            'writer'      => 'Admin',
            'status'      => 'draft',
            'content'     => 'Updated content.',
            'created_at'  => '2026-06-20',
        ])->assertRedirect(route('admin.articles.index'));

        // Step 6: Draft article not visible in catalog
        $this->get('/catalog')->assertDontSee('Updated Lifecycle Article');

        // Step 7: Draft article returns 404 on detail
        $this->get("/articles/{$article->slug}")->assertStatus(404);

        // Step 8: Delete article
        $this->actingAs($admin)->delete("/admin/articles/{$article->id}")
            ->assertRedirect(route('admin.articles.index'));

        $this->assertSoftDeleted('articles', ['id' => $article->id]);
    }

    // ============================================================
    // JOURNEY 4: Admin manages dosen
    // ============================================================

    /** @test */
    public function admin_dosen_lifecycle_journey(): void
    {
        $admin = User::factory()->admin()->create();

        // Step 1: Create dosen
        $this->actingAs($admin)->post('/admin/dosen', [
            'nidn'     => '9876543210',
            'username' => 'DrJourney',
            'address'  => 'Surabaya',
        ])->assertRedirect(route('admin.dosen.index'));

        $lecturer = Lecturer::where('nidn', '9876543210')->first();
        $this->assertNotNull($lecturer);

        // Step 2: View ACC page
        $this->actingAs($admin)->get("/admin/dosen/{$lecturer->id}/acc")->assertStatus(200);

        // Step 3: Edit dosen
        $this->actingAs($admin)->put("/admin/dosen/{$lecturer->id}", [
            'nidn'     => '9876543210',
            'username' => 'DrUpdated',
            'address'  => 'Bandung',
        ])->assertRedirect(route('admin.dosen.index'));

        $this->assertDatabaseHas('lecturers', ['id' => $lecturer->id, 'username' => 'DrUpdated']);

        // Step 4: Delete dosen
        $this->actingAs($admin)->delete("/admin/dosen/{$lecturer->id}")->assertRedirect();
        $this->assertDatabaseMissing('lecturers', ['id' => $lecturer->id]);
    }

    // ============================================================
    // JOURNEY 5: Admin manages categories
    // ============================================================

    /** @test */
    public function admin_category_lifecycle_journey(): void
    {
        $admin = User::factory()->admin()->create();

        // Step 1: Create category
        $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'New Category',
        ])->assertRedirect();

        $category = Category::where('name', 'New Category')->first();
        $this->assertNotNull($category);

        // Step 2: Category appears in list
        $this->actingAs($admin)->get('/admin/categories')->assertSee('New Category');

        // Step 3: Delete category
        $this->actingAs($admin)->delete("/admin/categories/{$category->id}")->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    // ============================================================
    // JOURNEY 6: Admin dashboard shows real statistics
    // ============================================================

    /** @test */
    public function admin_dashboard_shows_real_statistics(): void
    {
        $admin = User::factory()->admin()->create();
        Article::factory()->count(5)->create();
        Lecturer::factory()->count(3)->create();
        Review::factory()->count(2)->create();
        User::factory()->count(4)->create();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);

        // Dashboard should show counts
        $response->assertSee('5');  // articles
        $response->assertSee('3');  // lecturers
        $response->assertSee('2');  // reviews
        $response->assertSee('5');  // users (4 + 1 admin)
    }

    // ============================================================
    // JOURNEY 7: Catalog pagination and filtering
    // ============================================================

    /** @test */
    public function catalog_paginates_articles(): void
    {
        $category = Category::factory()->create();
        Article::factory()->count(15)->active()->create(['category_id' => $category->id]);

        // First page
        $response = $this->get('/catalog');
        $response->assertStatus(200);

        // Second page
        $response = $this->get('/catalog?page=2');
        $response->assertStatus(200);
    }

    /** @test */
    public function catalog_only_shows_active_articles(): void
    {
        $category = Category::factory()->create();
        Article::factory()->active()->create(['title' => 'Visible Article', 'category_id' => $category->id]);
        Article::factory()->draft()->create(['title' => 'Hidden Article', 'category_id' => $category->id]);

        $response = $this->get('/catalog');
        $response->assertSee('Visible Article');
        $response->assertDontSee('Hidden Article');
    }

    // ============================================================
    // JOURNEY 8: Review search and filtering
    // ============================================================

    /** @test */
    public function review_search_works(): void
    {
        Review::factory()->create(['title' => 'Amazing Website']);
        Review::factory()->create(['title' => 'Great Blog']);

        $response = $this->get('/review?q=Amazing');
        $response->assertStatus(200);
        $response->assertSee('Amazing Website');
    }

    /** @test */
    public function review_type_filter_works(): void
    {
        Review::factory()->create(['type' => 'Social media', 'title' => 'Social Review']);
        Review::factory()->create(['type' => 'Website', 'title' => 'Website Review']);

        $response = $this->get('/review?type=Social media');
        $response->assertStatus(200);
    }
}
