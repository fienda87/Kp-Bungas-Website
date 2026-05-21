<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Http\Resources\NewsResource;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::where('status', 'published');

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $news = $query->orderBy('published_at', 'desc')->paginate(10);

        return NewsResource::collection($news);
    }

    public function show(News $news)
    {
        if ($news->status !== 'published') {
            abort(404);
        }

        return new NewsResource($news);
    }

    public function categories()
    {
        $categories = News::where('status', 'published')
            ->distinct()
            ->pluck('category');

        return response()->json([
            'data' => $categories
        ]);
    }
}
