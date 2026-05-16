<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Program;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'articles_count' => Article::count(),
                'categories_count' => Category::count(),
                'programs_count' => Program::count(),
                'galleries_count' => Gallery::count(),
            ]
        ]);
    }
}
