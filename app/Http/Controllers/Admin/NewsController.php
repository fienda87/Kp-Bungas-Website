<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        $news = $query->latest()->paginate(10)->withQueryString();

        $categories = News::distinct()->pluck('category')->filter()->values();

        return Inertia::render('Admin/News/Index', [
            'news' => $news,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    /**
     * Show the form for creating a new news article.
     */
    public function create()
    {
        // Fetch existing categories to provide suggestions
        $categories = News::distinct()->pluck('category')->filter()->values();

        return Inertia::render('Admin/News/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created news article in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'thumbnail' => 'nullable|image|max:2048',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('news/thumbnails', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            $validated['pdf_file'] = $request->file('pdf_file')->store('news/pdfs', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        News::create($validated);

        return redirect()->route('news.index')
            ->with('success', 'News created successfully.');
    }

    /**
     * Show the form for editing the specified news article.
     */
    public function edit(News $news)
    {
        // Fetch existing categories to provide suggestions
        $categories = News::distinct()->pluck('category')->filter()->values();

        return Inertia::render('Admin/News/Edit', [
            'news' => $news,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified news article in storage.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'thumbnail' => 'nullable|image|max:2048',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('news/thumbnails', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            if ($news->pdf_file) {
                Storage::disk('public')->delete($news->pdf_file);
            }
            $validated['pdf_file'] = $request->file('pdf_file')->store('news/pdfs', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        $news->update($validated);

        return redirect()->route('news.index')
            ->with('success', 'News updated successfully.');
    }

    /**
     * Remove the specified news article from storage.
     */
    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }

        if ($news->pdf_file) {
            Storage::disk('public')->delete($news->pdf_file);
        }

        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'News deleted successfully.');
    }
}
