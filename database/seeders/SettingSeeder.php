<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Kp Bungas', 'type' => 'text', 'description' => 'Nama Website'],
            ['key' => 'contact_email', 'value' => 'info@kpbungas.com', 'type' => 'text', 'description' => 'Email Kontak Utama'],
            ['key' => 'contact_phone', 'value' => '+62 812-3456-7890', 'type' => 'text', 'description' => 'Nomor WhatsApp / Telepon'],
            ['key' => 'contact_address', 'value' => 'Jl. Contoh Alamat No. 123, Kota, Provinsi', 'type' => 'textarea', 'description' => 'Alamat Lengkap'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/kpbungas', 'type' => 'text', 'description' => 'Link Instagram'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/kpbungas', 'type' => 'text', 'description' => 'Link Facebook'],
            ['key' => 'social_tiktok', 'value' => 'https://www.tiktok.com/@kpbungas', 'type' => 'text', 'description' => 'Link TikTok'],
            ['key' => 'social_youtube', 'value' => 'https://www.youtube.com/@kpbungas', 'type' => 'text', 'description' => 'Link YouTube'],
            ['key' => 'about_footer', 'value' => 'Kp Bungas adalah website portal berita dan galeri yang peduli dengan komunitas dan lingkungan.', 'type' => 'textarea', 'description' => 'Teks singkat tentang kami di Footer'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
