<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('articles')->latest()->get();
        return view('pages.manage_category', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories']);
        $category = Category::create(['name' => $request->name]);

        $this->logActivity('create', "Created category: {$category->name}", $category);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function destroy(Category $category)
    {
        $this->logActivity('delete', "Deleted category: {$category->name}", $category);

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
