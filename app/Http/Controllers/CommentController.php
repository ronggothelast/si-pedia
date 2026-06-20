<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $article->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $data['content'],
            'status'  => 'approved', // auto-approve for now
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }
}
