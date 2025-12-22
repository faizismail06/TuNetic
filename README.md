
<p align="center">
<img src="public\assets\images\logo\logo-web.png" width="360" alt="Polines Logo">
<img src="public\assets\images\logo\laravel-logo.png" width="220" alt="Laravel Logo">
</p>

# TuNetic - Sistem Manajemen Pengelolaan Sampah

Aplikasi berbasis web untuk manajemen pengelolaan sampah yang dikembangkan sebagai bagian dari <i><b>Project-Based Learning</b></i> Jurusan Teknik Elektro, Politeknik Negeri Semarang.

🌐 **Live Demo**: [https://pbl24250213.informatikapolines.id/](https://pbl24250213.informatikapolines.id/)

## Fitur Utama

- 📍 Manajemen Lokasi TPS (Tempat Pembuangan Sampah)
- 🚛 Tracking Armada Pengangkut Sampah
- 📋 Laporan dari Warga dan TPS
- 🗓️ Penjadwalan Operasional
- 👥 Manajemen Petugas dan Penugasan
- 🗺️ Manajemen Rute Pengangkutan
- 📊 Dashboard Monitoring

## Requirements

- PHP 8.2 atau lebih tinggi
- Laravel 11
- MySQL 8.0 / MariaDB 10.4 atau lebih tinggi
- Composer
- Node.js & NPM (untuk frontend assets)

## Instalasi

1. Clone repository ini:
```bash
git clone <repository-url> tunetic
cd tunetic
```

2. Install dependency PHP menggunakan Composer:
```bash
composer install
```

3. Install dependency frontend:
```bash
npm install
```

4. Copy file ``.env.example`` menjadi ``.env``:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Buat database baru, kemudian sesuaikan konfigurasi pada file ``.env``:
6. Buat database baru, kemudian sesuaikan konfigurasi pada file ``.env``:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tunetic
DB_USERNAME=root
DB_PASSWORD=
```

7. Jalankan migrasi database dan seeder:
```bash
php artisan migrate
php artisan db:seed
```

8. Build frontend assets:
```bash
npm run build
```

9. Jalankan aplikasi:
```bash
php artisan serve
```

Atau dengan custom port:
```bash
php artisan serve --port=8080
```

10. Akses aplikasi di browser: `http://localhost:8000`

## Login Default

```
Email: superadmin@gmail.com
Password: adminadmin
```

## Teknologi yang Digunakan

- **Backend**: Laravel 11, PHP 8.2
- **Database**: MySQL/MariaDB
- **Frontend**: Blade Templates, Vite
- **Authentication**: Laravel Sanctum
- **Authorization**: Spatie Laravel Permission
- **Notifications**: PHP Flasher

## Struktur Aplikasi

- `app/Models/` - Model database (Armada, Sampah, LaporanTps, dll)
- `app/Http/Controllers/` - Controller untuk handling request
- `app/Http/Middleware/` - Custom middleware
- `resources/views/` - Blade templates
- `routes/web.php` - Route definitions
- `database/migrations/` - Database migrations
- `database/seeders/` - Database seeders

## Kontribusi

Proyek ini dikembangkan oleh mahasiswa D3 Teknik Informatika & S.Tr. Teknologi Rekayasa Komputer, Politeknik Negeri Semarang.

## Lisensi

Project ini dibuat untuk keperluan pembelajaran dalam program Project-Based Learning.

---

**Politeknik Negeri Semarang**  
Jurusan Teknik Elektro  
Program Studi D3 Teknik Informatika & S.Tr. Teknologi Rekayasa Komputer
