<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Program;
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

        $activePrograms = Program::where('is_active', true)
            ->orderBy('order')
            ->get();

        return Inertia::render('Home', [
            'featuredArticles' => ArticleResource::collection($featuredArticles),
            'programs' => $activePrograms,
        ]);
    }

    public function about()
    {
        return Inertia::render('About');
    }

    public function program()
    {
        $programs = Program::where('is_active', true)
            ->orderBy('order')
            ->get();

        return Inertia::render('Program', [
            'programs' => $programs,
        ]);
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
        $galleries = Gallery::with('photos')->latest()->get();

        return Inertia::render('Galeri', [
            'galleries' => $galleries,
        ]);
    }

    public function kunjungiKami()
    {
        return Inertia::render('KunjungiKami');
    }
}
