<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## Deploy demo gratis di Render

Proyek ini menyediakan `render.yaml` dan `Dockerfile` untuk deployment demo:

1. Masuk atau daftar di Render, lalu buat **Blueprint** dari repository GitHub `bosveall/pelindo-aset`.
2. Isi environment variable yang diminta Blueprint:
   - `APP_URL`: URL HTTPS aplikasi setelah dibuat, misalnya `https://pelindo-aset-demo.onrender.com`.
   - `INITIAL_ADMIN_NAME` dan `INITIAL_ADMIN_EMAIL`: nama dan email admin awal.
   - `INITIAL_ADMIN_PASSWORD`: password kuat minimal 16 karakter.
3. Biarkan Render membuat `APP_KEY` secara otomatis dan tunggu deployment selesai.

Layanan gratis Render dapat tidur saat tidak aktif dan memiliki filesystem sementara. SQLite dan gambar yang diunggah dapat hilang saat layanan restart, tidur, atau deploy ulang. Gunakan deployment ini hanya untuk demo, bukan untuk menyimpan data aset penting.

## Deploy demo eksperimental di Vercel

Vercel tidak menyediakan runtime PHP resmi. Proyek ini menggunakan runtime PHP komunitas, sehingga deployment dapat berubah atau gagal jika runtime tersebut tidak lagi kompatibel.

1. Hubungkan repository `bosveall/pelindo-aset` ke Vercel dan pilih Framework Preset **Other**.
2. Buat database PostgreSQL di Neon Free. Salin connection string pooled dari dashboard Neon, lalu tambahkan ke project Vercel sebagai `DATABASE_URL` untuk Production dan Preview.
   - Untuk koneksi Neon, konfigurasi menambahkan endpoint ID agar kompatibel dengan runtime PHP yang belum mendukung SNI. Jika connection string tidak menentukan `sslmode` dan `DB_SSLMODE` tidak diatur, koneksi menggunakan `verify-full`.
3. Tambahkan environment variables:
   - `APP_KEY`: hasil `php artisan key:generate --show` dari komputer lokal.
   - `APP_URL`: URL HTTPS project, misalnya `https://pelindo-aset-demo.vercel.app`.
   - `APP_ENV=production`, `APP_DEBUG=false`, `DB_CONNECTION=pgsql`.
   - `SESSION_DRIVER=cookie`, `CACHE_STORE=array`, `QUEUE_CONNECTION=sync`, `FILESYSTEM_DISK=local`, `LOG_CHANNEL=stderr`.
   - `INITIAL_ADMIN_NAME`, `INITIAL_ADMIN_EMAIL`, dan `INITIAL_ADMIN_PASSWORD` (minimal 16 karakter).
4. Deploy branch `main`. Composer menjalankan migrasi, membuat admin hanya jika database belum memiliki pengguna, lalu membangun aset frontend.

Vercel Hobby hanya untuk penggunaan pribadi non-komersial. Neon Free dan filesystem serverless memiliki batasan serta dapat tidur/berhenti saat tidak aktif; unggahan gambar pada Vercel tidak persisten. Gunakan hanya untuk demo magang dengan data contoh, bukan data aset sungguhan.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
