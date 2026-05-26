<?php

namespace App\Services;

use App\Models\News;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsService
{
    public function createNews(array $data, ?UploadedFile $thumbnail, ?UploadedFile $pdfFile): News
    {
        $data['slug'] = Str::slug($data['title']) . '-' . rand(1000, 9999);
        
        if ($thumbnail) {
            $data['thumbnail'] = $thumbnail->store('news/thumbnails', 'public');
        }
        if ($pdfFile) {
            $data['pdf_file'] = $pdfFile->store('news/pdfs', 'public');
        }
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        
        return News::create($data);
    }

    public function updateNews(News $news, array $data, ?UploadedFile $thumbnail, ?UploadedFile $pdfFile): News
    {
        if ($thumbnail) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $data['thumbnail'] = $thumbnail->store('news/thumbnails', 'public');
        }
        if ($pdfFile) {
            if ($news->pdf_file) {
                Storage::disk('public')->delete($news->pdf_file);
            }
            $data['pdf_file'] = $pdfFile->store('news/pdfs', 'public');
        }
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        
        $news->update($data);
        return $news;
    }

    public function deleteNews(News $news): void
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }
        if ($news->pdf_file) {
            Storage::disk('public')->delete($news->pdf_file);
        }
        $news->delete();
    }
}
