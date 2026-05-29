# Plan Task - Kp Bungas Website

## Status Saat Ini
- Halaman publik berita sudah ada (index dan detail).
- Login UI sudah dirapikan dan memakai layout khusus auth.
- Migrasi database sudah berhasil setelah perbaikan constraint.

## Rencana Tugas Berikutnya

### 1) Stabilitas Runtime
- Pastikan konfigurasi Inertia sesuai versi dan tidak ada cache lama tersisa.
- Tambahkan guard untuk role agar tidak error saat user tidak punya role.
- Tambahkan error boundary sederhana untuk menangani data kosong di komponen penting.

### 2) Public News (Penyempurnaan)
- Tambah pencarian dan filter kategori di halaman berita.
- Tambah pagination dengan desain yang konsisten.
- Pastikan fallback gambar/thumbnail rapi untuk konten tanpa gambar.

### 3) Admin Panel
- Tambah route untuk reorder program.
- Tambah feedback loading dan error untuk aksi CRUD penting.
- Pastikan upload file (thumbnail/pdf) validasi sisi klien dan preview.

### 4) Konten & UX
- Rapikan konsistensi tipografi dan spacing antar halaman publik.
- Audit semua link navbar dan CTA agar tidak ada dead link.
- Tambahkan empty state yang lebih komunikatif untuk data kosong.

### 5) Build & Deploy
- Pastikan build produksi `npm run build` dan `php artisan config:cache` sukses.
- Pastikan storage symlink untuk file upload.
- Tinjau env untuk production (APP_ENV, APP_DEBUG, APP_URL).

## Output yang Diharapkan
- Halaman publik dan admin berjalan stabil tanpa error runtime.
- UX konsisten dan profesional di semua halaman utama.
- Alur admin (login -> dashboard -> CRUD) lancar.
- Build produksi aman dan siap deploy.
