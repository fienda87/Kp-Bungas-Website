<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderGalleryPhotosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photos' => ['required', 'array', 'min:1'],
            'photos.*.id' => ['required', 'integer', 'exists:gallery_photos,id'],
            'photos.*.order' => ['required', 'integer', 'min:1'],
        ];
    }
}
