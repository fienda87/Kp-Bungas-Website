<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\NewsService;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

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

    public function store(StoreNewsRequest $request)
    {
        $this->newsService->createNews(
            $request->validated(),
            $request->file('thumbnail'),
            $request->file('pdf_file')
        );

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

    public function update(UpdateNewsRequest $request, News $news)
    {
        $this->newsService->updateNews(
            $news,
            $request->validated(),
            $request->file('thumbnail'),
            $request->file('pdf_file')
        );

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        $this->newsService->deleteNews($news);

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }
}
