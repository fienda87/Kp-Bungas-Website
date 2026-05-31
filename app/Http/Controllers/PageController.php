<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Program;
use App\Models\Gallery;
use App\Http\Resources\NewsResource;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    public function home()
    {
        $featuredNews = Cache::remember('home.featured_news', 3600, function () {
            $news = News::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->take(3)
                ->get();
            return NewsResource::collection($news)->resolve();
        });

        $programs = Cache::remember('home.programs', 86400, function () {
            return Program::where('is_active', true)
                ->orderBy('order')
                ->take(4)
                ->get()
                ->toArray();
        });

        return Inertia::render('Home', [
            'featuredNews' => $featuredNews,
            'programs' => $programs
        ]);
    }

    public function about()
    {
        return Inertia::render('About');
    }

    public function program()
    {
        $programs = Cache::remember('page.programs', 86400, function () {
            return Program::where('is_active', true)->orderBy('order')->get()->toArray();
        });
        
        return Inertia::render('Program', [
            'programs' => $programs
        ]);
    }

    public function dampak()
    {
        return Inertia::render('Dampak');
    }

    public function galeri()
    {
        $galleries = Cache::remember('page.galleries', 3600, function () {
            return Gallery::with('photos')->latest()->get()->toArray();
        });
        
        return Inertia::render('Galeri', [
            'galleries' => $galleries
        ]);
    }

    public function kunjungiKami()
    {
        return Inertia::render('KunjungiKami');
    }

    public function perjalanan()
    {
        return Inertia::render('Perjalanan');
    }

    public function newsIndex(Request $request)
    {
        $query = News::where('status', 'published');

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $categories = array_filter(explode(',', (string) $request->category));
            $query->whereIn('category', $categories);
        }

        $news = $query->orderBy('published_at', 'desc')->paginate(100)->withQueryString();

        return Inertia::render('News/Index', [
            'news' => NewsResource::collection($news),
            'categories' => News::where('status', 'published')
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->orderBy('category')
                ->distinct()
                ->pluck('category')
                ->values(),
            'filters' => $request->only(['search', 'category'])
        ]);
    }

    public function newsShow(News $news)
    {
        if ($news->status !== 'published') {
            abort(404);
        }

        $news->incrementViewCount();

        $recentNews = Cache::remember('news.recent.' . $news->id, 3600, function () use ($news) {
            $recent = News::where('status', 'published')
                ->where('id', '!=', $news->id)
                ->latest('published_at')
                ->take(3)
                ->get();
            
            return NewsResource::collection($recent)->resolve();
        });

        return Inertia::render('News/Show', [
            'news' => new NewsResource($news),
            'recentNews' => $recentNews
        ]);
    }
}
