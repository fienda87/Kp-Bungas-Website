# KP Bungas Website

Website resmi **Kelompok Pemuda Bungas (KP Bungas)** — dibangun menggunakan Laravel, PHP, dan Vue.js.

## Teknologi yang Digunakan

- **Backend**: [Laravel](https://laravel.com/) (PHP)
- **Frontend**: [Vue.js 3](https://vuejs.org/) (Composition API)
- **CSS**: [Tailwind CSS](https://tailwindcss.com/)
- **Bundler**: [Vite](https://vitejs.dev/)

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js >= 18
- npm >= 9

## Instalasi

### 1. Clone repositori

```bash
git clone https://github.com/fienda87/Kp-Bungas-Website.git
cd Kp-Bungas-Website
```

### 2. Install dependensi PHP

```bash
composer install
```

### 3. Install dependensi Node.js

```bash
npm install
```

### 4. Konfigurasi lingkungan

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Jalankan migrasi database

```bash
php artisan migrate
```

### 6. Jalankan server pengembangan

Di dua terminal terpisah:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (hot reload)
npm run dev
```

Buka [http://localhost:8000](http://localhost:8000) di browser Anda.

## Build untuk Produksi

```bash
npm run build
php artisan serve
```

## Fitur Website

- 🏠 **Beranda** — Halaman utama dengan hero section
- ℹ️ **Tentang Kami** — Informasi visi, misi, dan statistik organisasi
- 📋 **Program** — Daftar program unggulan KP Bungas
- 📅 **Kegiatan** — Agenda dan kegiatan terbaru
- 📞 **Kontak** — Formulir kontak dan informasi organisasi

## Lisensi

© 2025 KP Bungas. Semua hak dilindungi.
