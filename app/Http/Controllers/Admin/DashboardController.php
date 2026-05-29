<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Gallery;
use App\Models\GalleryPhoto;
use App\Models\Program;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $draftNews = News::where('status', 'draft')->latest()->take(5)->get();
        $newsWithoutThumbnail = News::whereNull('thumbnail')->latest()->take(5)->get();
        $emptyGalleries = Gallery::withCount('photos')->doesntHave('photos')->latest()->take(5)->get();
        $galleriesWithoutCover = Gallery::whereNull('cover_image')->latest()->take(5)->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'news_count' => News::count(),
                'published_news_count' => News::where('status', 'published')->count(),
                'draft_news_count' => News::where('status', 'draft')->count(),
                'news_without_thumbnail_count' => News::whereNull('thumbnail')->count(),
                'programs_count' => Program::count(),
                'galleries_count' => Gallery::count(),
                'photos_count' => GalleryPhoto::count(),
                'empty_galleries_count' => Gallery::doesntHave('photos')->count(),
            ],
            'attentionItems' => [
                'draftNews' => $draftNews,
                'newsWithoutThumbnail' => $newsWithoutThumbnail,
                'emptyGalleries' => $emptyGalleries,
                'galleriesWithoutCover' => $galleriesWithoutCover,
            ],
            'recentNews' => News::latest()->take(5)->get(),
            'recentGalleries' => Gallery::withCount('photos')->latest()->take(5)->get(),
            'quickLinks' => [
                ['label' => 'Tambah Artikel', 'route' => 'admin.news.create'],
                ['label' => 'Lihat Draft', 'route' => 'admin.news.index', 'params' => ['status' => 'draft']],
                ['label' => 'Tambah Album', 'route' => 'admin.galleries.index'],
                ['label' => 'Kelola Galeri', 'route' => 'admin.galleries.index'],
                ['label' => 'Kelola Program', 'route' => 'admin.programs.index'],
            ],
        ]);
    }
}
