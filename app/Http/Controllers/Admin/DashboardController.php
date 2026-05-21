<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Program;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'news_count' => News::count(),
                'programs_count' => Program::count(),
                'galleries_count' => Gallery::count(),
            ],
            'recentNews' => News::latest()->take(3)->get()
        ]);
    }
}
