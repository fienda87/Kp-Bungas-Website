<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Articles/Index', [
            'articles' => Article::with('category')->latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Articles/Create', [
            'categories' => Category::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|max:2048',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = $request->user()->id;

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('articles/images', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            $validated['pdf_file'] = $request->file('pdf_file')->store('articles/pdfs', 'public');
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        return Inertia::render('Admin/Articles/Edit', [
            'article' => $article,
            'categories' => Category::all()
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|max:2048',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('articles/images', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            if ($article->pdf_file) {
                Storage::disk('public')->delete($article->pdf_file);
            }
            $validated['pdf_file'] = $request->file('pdf_file')->store('articles/pdfs', 'public');
        }

        if ($validated['status'] === 'published' && !$article->published_at) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }
        if ($article->pdf_file) {
            Storage::disk('public')->delete($article->pdf_file);
        }
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully.');
    }
}
