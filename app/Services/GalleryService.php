<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GalleryService
{
    public function createGallery(array $data, ?UploadedFile $coverImage): Gallery
    {
        if ($coverImage) {
            $data['cover_image'] = $coverImage->store('galleries', 'public');
        }
        return Gallery::create($data);
    }

    public function updateGallery(Gallery $gallery, array $data, ?UploadedFile $coverImage): Gallery
    {
        if ($coverImage) {
            if ($gallery->cover_image) {
                Storage::disk('public')->delete($gallery->cover_image);
            }
            $data['cover_image'] = $coverImage->store('galleries', 'public');
        }
        $gallery->update($data);
        return $gallery;
    }

    public function deleteGallery(Gallery $gallery): void
    {
        foreach ($gallery->photos as $photo) {
            Storage::disk('public')->delete($photo->image_path);
        }
        if ($gallery->cover_image) {
            Storage::disk('public')->delete($gallery->cover_image);
        }
        $gallery->delete();
    }

    public function uploadPhotos(Gallery $gallery, array $photos): void
    {
        foreach ($photos as $photo) {
            $path = $photo->store('galleries/photos', 'public');
            $gallery->photos()->create(['image_path' => $path]);
        }
    }

    public function deletePhoto(GalleryPhoto $photo): void
    {
        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();
    }
}
