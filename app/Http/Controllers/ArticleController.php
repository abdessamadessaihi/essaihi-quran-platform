<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');

        $articles = Article::where('status', 'published')
            ->when($category, fn($q) => $q->where('category', $category))
            ->with('author')
            ->latest()
            ->paginate(9);

        $myArticles = Article::where('user_id', Auth::id())
            ->latest()->take(5)->get();

        $categories = Article::CATEGORIES;

        return view('articles.index', compact(
            'articles', 'myArticles', 'categories', 'category'
        ));
    }

    public function show(Article $article)
    {
        abort_if($article->status !== 'published', 404);
        $article->incrementViews();
        $article->load('author');

        $article->load(['author', 'comments.user']);

        $related = Article::where('status', 'published')
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->take(3)->get();

        return view('articles.show', compact('article', 'related'));
    }

    public function create()
    {
        $categories = Article::CATEGORIES;
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:200',
            'excerpt'  => 'nullable|string|max:500',
            'content'  => 'required|string|min:50',
            'category' => 'required|in:tafsir,tadabbur,fiqh,seerah,general',
            'status'   => 'nullable|in:draft,published',
            'cover'    => 'nullable|image|max:3072',
        ]);

        $coverUrl = null;
        if ($request->hasFile('cover')) {
            $path     = $request->file('cover')->store('articles','public');
            $coverUrl = 'storage/'.$path;
        }

        $article = Article::create([
            'user_id'   => Auth::id(),
            'title'     => $validated['title'],
            'excerpt'   => $validated['excerpt'],
            'content'   => $validated['content'],
            'category'  => $validated['category'],
            'status'    => $request->get('status', 'draft'),
            'cover_url' => $coverUrl,
        ]);

        $msg = $article->status === 'published'
            ? 'تم نشر المقال بنجاح 🎉'
            : 'تم حفظ المسودة';

                
            if ($article->status === 'published') {
                    NotificationService::onArticlePublished($article);
            }
        return redirect()->route('articles.show', $article)
                         ->with('success', $msg);

    }

    public function edit(Article $article)
    {
        abort_unless($article->user_id === Auth::id(), 403);
        $categories = Article::CATEGORIES;
        return view('articles.edit', compact('article','categories'));
    }

    public function update(Request $request, Article $article)
    {
        abort_unless($article->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'title'    => 'required|string|max:200',
            'excerpt'  => 'nullable|string|max:500',
            'content'  => 'required|string|min:50',
            'category' => 'required|in:tafsir,tadabbur,fiqh,seerah,general',
            'status'   => 'nullable|in:draft,published',
            'cover'    => 'nullable|image|max:3072',
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('articles','public');
            $validated['cover_url'] = 'storage/'.$path;
        }

        $validated['status'] = $request->get('status','draft');
        $article->update($validated);

        return redirect()->route('articles.show', $article)
                         ->with('success','تم تحديث المقال ✅');
    }

    public function destroy(Article $article)
    {
        abort_unless($article->user_id === Auth::id(), 403);
        $article->delete();
        return redirect()->route('articles.index')
                         ->with('success','تم حذف المقال');
    }
}