<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GalleryService
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    public function createGallery(array $data, ?UploadedFile $coverImage): Gallery
    {
        if ($coverImage) {
            $data['cover_image'] = $this->imageService->processAndStore($coverImage, 'galleries', 1200, 80);
        }
        return Gallery::create($data);
    }

    public function updateGallery(Gallery $gallery, array $data, ?UploadedFile $coverImage): Gallery
    {
        if ($coverImage) {
            if ($gallery->cover_image) {
                Storage::disk('public')->delete($gallery->cover_image);
            }
            $data['cover_image'] = $this->imageService->processAndStore($coverImage, 'galleries', 1200, 80);
        }
        $gallery->update($data);
        return $gallery;
    }

    public function deleteGallery(Gallery $gallery): void
    {
        $deletedPaths = [];

        foreach ($gallery->photos as $photo) {
            if (!in_array($photo->image_path, $deletedPaths, true)) {
                Storage::disk('public')->delete($photo->image_path);
                $deletedPaths[] = $photo->image_path;
            }
        }

        if ($gallery->cover_image && !in_array($gallery->cover_image, $deletedPaths, true)) {
            Storage::disk('public')->delete($gallery->cover_image);
        }

        $gallery->delete();
    }

    public function uploadPhotos(Gallery $gallery, array $photos): void
    {
        $nextOrder = ((int) $gallery->photos()->max('order')) + 1;

        foreach ($photos as $photo) {
            $path = $this->imageService->processAndStore($photo, 'galleries/photos', 1200, 80);
            $gallery->photos()->create([
                'image_path' => $path,
                'order' => $nextOrder,
            ]);
            $nextOrder++;
        }
    }

    public function deletePhoto(GalleryPhoto $photo): void
    {
        $gallery = $photo->gallery;
        $wasCover = $gallery?->cover_image === $photo->image_path;

        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();

        if ($gallery && $wasCover) {
            $replacement = $gallery->photos()->orderBy('order')->orderBy('id')->first();
            $gallery->update(['cover_image' => $replacement?->image_path]);
        }
    }

    public function updatePhotoCaption(GalleryPhoto $photo, ?string $caption): GalleryPhoto
    {
        $photo->update(['caption' => $caption]);

        return $photo;
    }

    public function reorderPhotos(Gallery $gallery, array $orderData): void
    {
        foreach ($orderData as $item) {
            $gallery->photos()
                ->where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }
    }

    public function setCoverFromPhoto(Gallery $gallery, GalleryPhoto $photo): Gallery
    {
        abort_unless($photo->gallery_id === $gallery->id, 404);

        $gallery->update(['cover_image' => $photo->image_path]);

        return $gallery;
    }
}
