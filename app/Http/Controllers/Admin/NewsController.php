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
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $categories = News::distinct()->pluck('category');

        return Inertia::render('Admin/News/Index', [
            'news' => $news,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category'])
        ]);
    }

    public function create()
    {
        $categories = News::distinct()->pluck('category');
        return Inertia::render('Admin/News/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(1000, 9999);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('news/thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        if ($request->hasFile('pdf_file')) {
            $path = $request->file('pdf_file')->store('news/pdfs', 'public');
            $validated['pdf_file'] = $path;
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }

    public function edit(News $news)
    {
        $categories = News::distinct()->pluck('category');
        return Inertia::render('Admin/News/Edit', [
            'news' => $news,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $path = $request->file('thumbnail')->store('news/thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        if ($request->hasFile('pdf_file')) {
            if ($news->pdf_file) {
                Storage::disk('public')->delete($news->pdf_file);
            }
            $path = $request->file('pdf_file')->store('news/pdfs', 'public');
            $validated['pdf_file'] = $path;
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }

        if ($news->pdf_file) {
            Storage::disk('public')->delete($news->pdf_file);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }
}
