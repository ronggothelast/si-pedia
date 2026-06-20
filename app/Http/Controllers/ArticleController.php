<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::with('category')
            ->when($request->q, fn ($query, $q) => $query->where('title', 'like', "%{$q}%"))
            ->latest()->paginate(10);

        return view('pages.article_index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('pages.edit_article', ['article' => new Article(), 'categories' => $categories, 'mode' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'writer'       => 'required|string',
            'status'       => 'required|in:active,draft',
            'content'      => 'required|string',
            'image'        => 'nullable|image|max:10240',
            'created_at'   => 'required|date',
            'scheduled_at' => 'nullable|date',
        ]);

        $data['slug'] = $this->generateUniqueSlug($data['title']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }
        $data['views'] = 0;

        $article = Article::create($data);

        $this->logActivity('create', "Created article: {$article->title}", $article);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        return view('pages.edit_article', compact('article', 'categories'))->with('mode', 'edit');
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'writer'       => 'required|string',
            'status'       => 'required|in:active,draft',
            'content'      => 'required|string',
            'image'        => 'nullable|image|max:10240',
            'created_at'   => 'required|date',
            'scheduled_at' => 'nullable|date',
        ]);

        if ($data['title'] !== $article->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $article->id);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        $this->logActivity('update', "Updated article: {$article->title}", $article);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diupdate.');
    }

    public function destroy(Article $article)
    {
        $this->logActivity('delete', "Deleted article: {$article->title}", $article);

        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:articles,id',
            'action' => 'required|in:publish,draft,delete',
        ]);

        $articles = Article::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'publish':
                $articles->update(['status' => 'active']);
                $desc = 'Bulk published ' . count($request->ids) . ' articles';
                break;
            case 'draft':
                $articles->update(['status' => 'draft']);
                $desc = 'Bulk drafted ' . count($request->ids) . ' articles';
                break;
            case 'delete':
                Article::whereIn('id', $request->ids)->delete();
                $desc = 'Bulk deleted ' . count($request->ids) . ' articles';
                break;
        }

        $this->logActivity('bulk_action', $desc ?? 'Bulk action performed');

        return redirect()->route('admin.articles.index')->with('success', 'Bulk action completed successfully.');
    }

    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Article::where('slug', $slug)
            ->when($excludeId, fn ($q, $id) => $q->where('id', '!=', $id))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
