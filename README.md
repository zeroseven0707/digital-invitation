# Platform Management Undangan Pernikahan Digital

Platform web untuk membuat dan mengelola undangan pernikahan digital dengan berbagai template yang dapat disesuaikan.

## Fitur Utama

### Untuk User
- **Autentikasi & Manajemen Akun**
  - Registrasi dengan verifikasi email
  - Login/Logout
  - Reset password
  - Update profil

- **Manajemen Undangan**
  - Pilih dari berbagai template undangan
  - Buat undangan dengan informasi lengkap (mempelai, tanggal, lokasi)
  - Edit dan hapus undangan
  - Preview sebelum publikasi
  - Publikasi/unpublish undangan
  - Generate unique URL untuk setiap undangan

- **Manajemen Galeri**
  - Upload foto untuk galeri undangan
  - Reorder foto dengan drag & drop
  - Hapus foto

- **Manajemen Tamu**
  - Tambah, edit, dan hapus data tamu
  - Kategorisasi tamu (keluarga, teman, kolega)
  - Filter tamu berdasarkan kategori
  - Export daftar tamu ke CSV
  - Import tamu dari CSV

- **Statistik & Analytics**
  - Total views undangan
  - Views per hari (chart)
  - Device breakdown (desktop, mobile, tablet)
  - Browser breakdown

### Untuk Admin
- **Dashboard Admin**
  - Platform statistics (total users, invitations, views)
  - User growth chart
  - Invitation growth chart
  - View growth chart
  - Top users by invitations
  - Top invitations by views

- **User Management**
  - List semua users
  - View user detail
  - Activate/deactivate users
  - Search dan filter users

- **Template Management**
  - List semua templates
  - Upload template baru (HTML, CSS, JS, thumbnail)
  - Delete template (jika tidak digunakan)

## Teknologi

- **Framework**: Laravel 11.x
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Admin Panel**: AdminLTE 3 (jeroennoten/laravel-adminlte)
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Laravel Breeze
- **Charts**: Chart.js
- **Testing**: Pest PHP

## Instalasi

### Requirements
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL/PostgreSQL/SQLite

### Langkah Instalasi

1. Clone repository
```bash
git clone <repository-url>
cd undangan-digital-laravel
```

2. Install dependencies
```bash
composer install
npm install
```

3. Copy file environment
```bash
copy .env.example .env
```

4. Generate application key
```bash
php artisan key:generate
```

5. Konfigurasi database di file `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=undangan_digital
DB_USERNAME=root
DB_PASSWORD=
```

6. Konfigurasi mail driver di file `.env` (untuk email verification)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@undangan.test
MAIL_FROM_NAME="${APP_NAME}"
```

7. (Opsional) Konfigurasi Google Maps API Key
Untuk menampilkan peta di undangan, tambahkan Google Maps API key:
```env
GOOGLE_MAPS_API_KEY=your_google_maps_api_key
```
Lihat [GOOGLE_MAPS_SETUP.md](GOOGLE_MAPS_SETUP.md) untuk panduan lengkap cara mendapatkan API key.

8. Run migrations dan seeders
```bash
php artisan migrate --seed
```

**Seeder akan membuat:**
- 9 template undangan aktif
- 1 akun admin: `admin@nikahin.com` / `password`
- 1 akun user regular: `user@nikahin.com` / `password`
- 1 undangan sample yang sudah published dan paid

**Untuk seed ulang data:**
```bash
php artisan migrate:fresh --seed
```

**Untuk seed spesifik:**
```bash
# Seed hanya templates
php artisan db:seed --class=TemplateSeeder

# Seed hanya production data (admin, user, dan 1 undangan)
php artisan db:seed --class=ProductionSeeder
```

9. Create storage link
```bash
php artisan storage:link
```

10. Build assets
```bash
npm run build
```

11. Start development server
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Konfigurasi

### File Storage
Aplikasi menggunakan local storage untuk menyimpan:
- Template files (HTML, CSS, JS, thumbnails) di `storage/app/templates/`
- Gallery photos di `storage/app/public/galleries/`

Untuk production, Anda dapat mengkonfigurasi S3 atau cloud storage lainnya di `config/filesystems.php`.

### Email Configuration
Untuk production, gunakan SMTP provider seperti:
- Mailgun
- SendGrid
- Amazon SES
- Postmark

### Rate Limiting
Rate limiting sudah dikonfigurasi untuk:
- Public invitation views: 60 requests per minute
- Authentication routes: 3-5 requests per minute
- Email verification: 6 requests per minute

## Default Credentials

### Admin Account
Setelah menjalankan seeder, Anda dapat login sebagai admin dengan:
- Email: `admin@nikahin.com`
- Password: `password`

### User Account (untuk testing)
- Email: `user@nikahin.com`
- Password: `password`

## Template Structure

Template undangan terdiri dari 4 file:
1. `template.html` - HTML structure dengan placeholders
2. `style.css` - Styling untuk template
3. `script.js` - JavaScript untuk interaktivity
4. `thumbnail.svg/png/jpg` - Preview thumbnail

### Placeholders yang tersedia:
- `{{bride_name}}` - Nama mempelai wanita
- `{{groom_name}}` - Nama mempelai pria
- `{{bride_father_name}}` - Nama ayah mempelai wanita
- `{{bride_mother_name}}` - Nama ibu mempelai wanita
- `{{groom_father_name}}` - Nama ayah mempelai pria
- `{{groom_mother_name}}` - Nama ibu mempelai pria
- `{{akad_date}}` - Tanggal akad
- `{{akad_time_start}}` - Waktu mulai akad
- `{{akad_time_end}}` - Waktu selesai akad
- `{{akad_location}}` - Lokasi akad
- `{{reception_date}}` - Tanggal resepsi
- `{{reception_time_start}}` - Waktu mulai resepsi
- `{{reception_time_end}}` - Waktu selesai resepsi
- `{{reception_location}}` - Lokasi resepsi
- `{{full_address}}` - Alamat lengkap
- `{{google_maps_url}}` - URL Google Maps
- `{{music_url}}` - URL musik background

## Testing

Run tests dengan Pest:
```bash
php artisan test
```

Run specific test:
```bash
php artisan test --filter=InvitationControllerTest
```

## Security Features

- **Input Sanitization**: Semua input user di-sanitize untuk mencegah XSS
- **Authorization Policies**: User hanya dapat mengakses resource milik mereka
- **Rate Limiting**: Mencegah abuse pada authentication dan public routes
- **CSRF Protection**: Laravel CSRF protection enabled
- **Password Hashing**: Bcrypt password hashing
- **Email Verification**: Required untuk aktivasi akun

## Deployment

### Production Checklist

1. Set `APP_ENV=production` dan `APP_DEBUG=false` di `.env`
2. Generate production key: `php artisan key:generate`
3. Optimize configuration: `php artisan config:cache`
4. Optimize routes: `php artisan route:cache`
5. Optimize views: `php artisan view:cache`
6. Build production assets: `npm run build`
7. Setup proper file permissions untuk `storage/` dan `bootstrap/cache/`
8. Configure production database
9. Configure production mail driver
10. Setup SSL certificate
11. Configure backup strategy
12. Setup monitoring dan logging

### Environment Variables untuk Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls

# Google Maps (Optional - untuk menampilkan peta di undangan)
GOOGLE_MAPS_API_KEY=your-google-maps-api-key

# Optional: S3 for file storage
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```

## Troubleshooting

### Storage Link Error
Jika `php artisan storage:link` gagal, buat symbolic link manual:
```bash
# Windows (run as administrator)
mklink /D public\storage ..\storage\app\public

# Linux/Mac
ln -s ../storage/app/public public/storage
```

### Permission Issues
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Database Connection Error
- Pastikan database sudah dibuat
- Cek credentials di `.env`
- Pastikan database service berjalan

## License

This project is open-sourced software licensed under the MIT license.

## Support

Untuk pertanyaan atau issue, silakan buat issue di repository ini.
