<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryPhoto;
use App\Services\GalleryService;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Requests\UploadGalleryPhotosRequest;
use App\Http\Requests\ReorderGalleryPhotosRequest;
use App\Http\Requests\UpdateGalleryPhotoCaptionRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GalleryController extends Controller
{
    protected $galleryService;

    public function __construct(GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    public function index(Request $request)
    {
        $query = Gallery::withCount('photos')->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('album_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return Inertia::render('Admin/Galleries/Index', [
            'galleries' => $query->get(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function show(Gallery $gallery)
    {
        return Inertia::render('Admin/Galleries/Show', [
            'gallery' => $gallery->load(['photos' => fn ($query) => $query->orderBy('order')->orderBy('id')])
        ]);
    }

    public function store(StoreGalleryRequest $request)
    {
        $this->galleryService->createGallery(
            $request->validated(),
            $request->file('cover_image')
        );

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery album created successfully.');
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery)
    {
        $this->galleryService->updateGallery(
            $gallery,
            $request->validated(),
            $request->file('cover_image')
        );

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        $this->galleryService->deleteGallery($gallery);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery deleted successfully.');
    }

    public function uploadPhotos(UploadGalleryPhotosRequest $request, Gallery $gallery)
    {
        $this->galleryService->uploadPhotos($gallery, $request->file('photos') ?? []);

        return redirect()->back()->with('success', 'Photos uploaded successfully.');
    }

    public function deletePhoto(GalleryPhoto $photo)
    {
        $this->galleryService->deletePhoto($photo);

        return redirect()->back()->with('success', 'Photo deleted successfully.');
    }

    public function updatePhotoCaption(UpdateGalleryPhotoCaptionRequest $request, GalleryPhoto $photo)
    {
        $this->galleryService->updatePhotoCaption($photo, $request->validated('caption'));

        return redirect()->back()->with('success', 'Caption updated successfully.');
    }

    public function reorderPhotos(ReorderGalleryPhotosRequest $request, Gallery $gallery)
    {
        $this->galleryService->reorderPhotos($gallery, $request->validated('photos'));

        return redirect()->back()->with('success', 'Photo order updated successfully.');
    }

    public function setCoverFromPhoto(Gallery $gallery, GalleryPhoto $photo)
    {
        $this->galleryService->setCoverFromPhoto($gallery, $photo);

        return redirect()->back()->with('success', 'Gallery cover updated successfully.');
    }
}
