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

        return Inertia::render('Home', [
            'featuredNews' => NewsResource::collection($featuredNews)
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
}
