# Tutorial Lengkap Menjalankan Project Kp-Bungas-Website (Setelah Clone)

Panduan ini dibuat untuk pemula agar bisa menjalankan website sampai benar-benar tampil dan bisa diakses semua halaman.

Project ini menggunakan:
- Laravel 13 (PHP)
- Inertia + Vue 3
- Vite
- MySQL (default)

## 1. Persiapan Software Wajib

Pastikan software berikut sudah terpasang:

1. Git
2. PHP `8.3` atau lebih baru
3. Composer terbaru
4. Node.js `20 LTS` atau `22 LTS` (disarankan)
5. npm (ikut dari Node.js)
6. MySQL Server (atau MariaDB)

Cek versi di terminal:

```bash
git --version
php -v
composer -V
node -v
npm -v
```

Target minimum:
- PHP: `8.3+`
- Node: `20+`

## 2. Clone Repository

```bash
git clone <URL_REPO_GITHUB>
cd Kp-Bungas-Website
```

## 3. Install Dependency Backend (Laravel)

```bash
composer install
```

Jika muncul error ekstensi PHP belum aktif (contoh `openssl`, `pdo_mysql`, `mbstring`, `fileinfo`), aktifkan dulu extension itu di konfigurasi PHP, lalu ulangi `composer install`.

## 4. Buat File Environment

```bash
cp .env.example .env
```

Jika di Windows `cp` tidak tersedia:

```powershell
copy .env.example .env
```

## 5. Konfigurasi Database di `.env`

Buka file `.env`, ubah bagian ini sesuai database lokal kamu:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bungas_db
DB_USERNAME=root
DB_PASSWORD=
```

Lalu buat database baru (contoh nama `bungas_db`) di MySQL.

Contoh SQL:

```sql
CREATE DATABASE bungas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 6. Set Session Driver Agar Aman untuk Setup Awal

Di `.env`, ubah ini supaya tidak perlu tabel session tambahan:

```env
SESSION_DRIVER=file
```

Catatan:
- Default project memakai `SESSION_DRIVER=database`.
- Jika tetap mau pakai `database`, kamu harus membuat migration session table sendiri (`php artisan session:table`) lalu migrate lagi.

## 7. Generate App Key

```bash
php artisan key:generate
```

## 8. Jalankan Migration Database

```bash
php artisan migrate
```

Pastikan command ini sukses tanpa error.

## 9. Install Dependency Frontend

```bash
npm install
```

## 10. Jalankan Website (Mode Development)

Paling mudah gunakan command bawaan project:

```bash
composer run dev
```

Command ini otomatis menjalankan:
- Laravel server
- Queue listener
- Vite dev server

Setelah running, buka browser ke:

- `http://127.0.0.1:8000`
- atau `http://localhost:8000`

## 11. Cek Semua Halaman Utama

Pastikan halaman-halaman berikut bisa dibuka:

1. `/` (Home)
2. `/about`
3. `/program`
4. `/perjalanan`
5. `/dampak`
6. `/galeri`
7. `/kunjungi-kami`

Contoh cek cepat:

```text
http://127.0.0.1:8000/
http://127.0.0.1:8000/kunjungi-kami
```

Jika semua bisa dibuka tanpa error, berarti website sudah berjalan 100% untuk mode development.

---

## Troubleshooting (Masalah Umum)

### A. `Vite manifest not found` atau tampilan tidak muncul CSS/JS

Penyebab: frontend belum jalan.

Solusi:
1. Pastikan `npm install` sudah sukses.
2. Jalankan `composer run dev` (atau minimal `npm run dev`).

### B. Error Node/Vite terkait optional dependency (`@rolldown/binding-win32-x64-msvc`)

Biasanya terjadi pada kombinasi versi Node tertentu.

Solusi yang direkomendasikan:

1. Gunakan Node LTS (`20` atau `22`).
2. Hapus install lama lalu install ulang:

```bash
rm -rf node_modules package-lock.json
npm install --include=optional
```

Di Windows PowerShell:

```powershell
rmdir /s /q node_modules
del package-lock.json
npm install --include=optional
```

Lalu jalankan lagi:

```bash
composer run dev
```

### C. Error koneksi database saat migrate

Cek ulang `.env`:
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

Pastikan MySQL service benar-benar aktif.

### D. Error `Base table or view not found: sessions`

Penyebab: `SESSION_DRIVER=database` tapi tabel sessions belum ada.

Solusi paling cepat:
- Ubah `.env` jadi:

```env
SESSION_DRIVER=file
```

Lalu bersihkan cache config:

```bash
php artisan config:clear
```

### E. Port bentrok (server tidak bisa start)

Jalankan server di port lain:

```bash
php artisan serve --port=8080
```

Lalu akses:

```text
http://127.0.0.1:8080
```

---

## Menjalankan Manual (Opsional)

Kalau tidak pakai `composer run dev`, jalankan 3 terminal terpisah:

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
php artisan queue:listen --tries=1 --timeout=0
```

Terminal 3:

```bash
npm run dev
```

---

## Checklist Sukses

- [ ] `composer install` sukses
- [ ] `.env` sudah dibuat dan benar
- [ ] database sudah dibuat
- [ ] `php artisan key:generate` sukses
- [ ] `php artisan migrate` sukses
- [ ] `npm install` sukses
- [ ] `composer run dev` berjalan
- [ ] Website terbuka di browser
- [ ] Semua halaman utama bisa diakses

Jika semua checklist sudah centang, project sudah berhasil dijalankan dengan benar.
