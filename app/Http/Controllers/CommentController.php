<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:2|max:1000',
        ]);

        Comment::create([
            'user_id'    => Auth::id(),
            'article_id' => $article->id,
            'content'    => $validated['content']
        ]);

        return back()->with('success', 'تم إضافة تعليقك بنجاح ✨');
    }
}