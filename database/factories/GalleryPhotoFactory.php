<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GalleryPhoto>
 */
class GalleryPhotoFactory extends Factory
{
    protected $model = GalleryPhoto::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gallery_id' => Gallery::factory(),
            'image_path' => 'galleries/photos/fake.jpg',
            'caption' => $this->faker->sentence(),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
