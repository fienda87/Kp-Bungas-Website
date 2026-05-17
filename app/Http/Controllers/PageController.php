<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function home()
    {
        $featuredArticles = Article::with(['user', 'category'])
            ->where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        return Inertia::render('Home', [
            'featuredArticles' => ArticleResource::collection($featuredArticles),
        ]);
    }

    public function about()
    {
        return Inertia::render('About');
    }

    public function program()
    {
        return Inertia::render('Program');
    }

    public function perjalanan()
    {
        return Inertia::render('Perjalanan');
    }

    public function dampak()
    {
        return Inertia::render('Dampak');
    }

    public function galeri()
    {
        return Inertia::render('Galeri');
    }

    public function kunjungiKami()
    {
        return Inertia::render('KunjungiKami');
    }
}
