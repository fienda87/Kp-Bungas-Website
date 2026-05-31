<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        if (!Setting::where('key', 'hero_slides')->exists()) {
            $originalSlides = [
                [ 
                    'image' => '/images/impact/hero-image.png', 
                    'alt' => 'Kebun Kampung Bungas',
                    'tagline' => 'Balikpapan, Indonesia',
                    'titlePart1' => 'Merawat Pertiwi dari',
                    'titleHighlight' => 'Halaman Rumah Sendiri',
                    'description' => 'Kampung hijau berbasis komunitas yang mengubah daerah perkotaan menjadi ruang hijau produktif melalui pertanian perkotaan dan hidroponik.',
                    'ctaText' => 'Jelajahi Kampung',
                    'ctaLink' => '/program'
                ],
                [ 
                    'image' => '/images/program/berkebun.jpg', 
                    'alt' => 'Kegiatan Berkebun Komunitas',
                    'tagline' => 'Aktivitas Warga Kampung',
                    'titlePart1' => 'Membangun Kebersamaan',
                    'titleHighlight' => 'Lewat Gotong Royong',
                    'description' => 'Setiap sudut RT di Kampung Bungas dikelola bersama oleh warga untuk menghasilkan bahan pangan sehat dan lingkungan yang asri.',
                    'ctaText' => 'Lihat Perjalanan',
                    'ctaLink' => '/perjalanan'
                ],
                [ 
                    'image' => '/images/program/hidroponik.jpg', 
                    'alt' => 'Pertanian Hidroponik Modern',
                    'tagline' => 'Teknologi Pertanian Kota',
                    'titlePart1' => 'Pertanian Hidroponik',
                    'titleHighlight' => 'Modern & Berkelanjutan',
                    'description' => 'Memaksimalkan lahan sempit perkotaan dengan sistem hidroponik efisien, menghasilkan sayuran segar berkualitas tinggi bebas pestisida.',
                    'ctaText' => 'Program Hidroponik',
                    'ctaLink' => '/program'
                ],
                [ 
                    'image' => '/images/program/pendidikan.jpg', 
                    'alt' => 'Edukasi Pertanian Perkotaan',
                    'tagline' => 'Edukasi Warga & Pemuda',
                    'titlePart1' => 'Menanam Benih Kebaikan',
                    'titleHighlight' => 'Bagi Generasi Penerus',
                    'description' => 'Menjadi pusat pembelajaran lingkungan bagi anak-anak dan pemuda untuk menumbuhkan cinta lingkungan sejak usia dini.',
                    'ctaText' => 'Dampak Sosial Kami',
                    'ctaLink' => '/dampak'
                ],
                [ 
                    'image' => '/images/program/sampah.jpg', 
                    'alt' => 'Pengelolaan Sampah Terpadu',
                    'tagline' => 'Eco-Friendly & Zero Waste',
                    'titlePart1' => 'Mengolah Sampah Menjadi',
                    'titleHighlight' => 'Berkah Lingkungan',
                    'description' => 'Sistem pengelolaan sampah organik terpadu yang didaur ulang menjadi pupuk alami penyubur aneka tanaman buah dan sayur.',
                    'ctaText' => 'Dampak Lingkungan',
                    'ctaLink' => '/dampak'
                ]
            ];

            Setting::create([
                'key' => 'hero_slides',
                'value' => json_encode($originalSlides),
                'type' => 'json',
                'description' => 'Pengaturan Slide Hero Carousel'
            ]);
        }

        $settings = Setting::all();
        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string'
        ]);

        foreach ($data['settings'] as $key => $value) {
            if ($key === 'hero_slides') {
                continue;
            }
            Setting::where('key', $key)->update(['value' => $value]);
        }

        // Handle hero_slides JSON updates and slide image uploads
        if (isset($data['settings']['hero_slides'])) {
            $slides = json_decode($data['settings']['hero_slides'], true) ?: [];

            for ($i = 0; $i < 5; $i++) {
                if ($request->hasFile("slide_image_$i")) {
                    $file = $request->file("slide_image_$i");
                    $path = $file->store('hero', 'public');
                    if (isset($slides[$i])) {
                        $slides[$i]['image'] = \Illuminate\Support\Facades\Storage::url($path);
                    }
                }
            }

            Setting::where('key', 'hero_slides')->update(['value' => json_encode($slides)]);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
