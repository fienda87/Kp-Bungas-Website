<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Program;
use App\Models\Gallery;
use App\Http\Resources\NewsResource;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $featuredNews = News::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $programs = Program::where('is_active', true)->orderBy('order')->take(4)->get();

        return Inertia::render('Home', [
            'featuredNews' => NewsResource::collection($featuredNews),
            'programs' => $programs
        ]);
    }

    public function about()
    {
        return Inertia::render('About');
    }

    public function program()
    {
        $programs = Program::where('is_active', true)->orderBy('order')->get();
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
        $galleries = Gallery::with('photos')->latest()->get();
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

        $news = $query->orderBy('published_at', 'desc')->paginate(9)->withQueryString();

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

        return Inertia::render('News/Show', [
            'news' => new NewsResource($news),
            'recentNews' => NewsResource::collection(
                News::where('status', 'published')
                    ->where('id', '!=', $news->id)
                    ->latest('published_at')
                    ->take(3)
                    ->get()
            )
        ]);
    }
}
