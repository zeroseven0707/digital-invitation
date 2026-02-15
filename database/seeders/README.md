# Database Seeders

Dokumentasi untuk database seeders Platform Management Undangan Pernikahan Digital.

## Daftar Seeders

### 1. TemplateSeeder
Membuat template undangan default (Classic Elegant, Modern Minimalist, Romantic Floral).

### 2. UserSeeder
Membuat akun admin dan pengguna regular.

**Admin:**
- Email: `admin@undangan.com`
- Password: `admin123`

**Pengguna Regular:**
- Email: `budi@example.com` / Password: `password`
- Email: `siti@example.com` / Password: `password`
- Email: `ahmad@example.com` / Password: `password`
- Email: `dewi@example.com` / Password: `password`

### 3. SampleDataSeeder
Membuat data sample untuk testing:
- 3 undangan dengan status berbeda (2 published, 1 draft)
- Daftar tamu untuk setiap undangan (family, friend, colleague)
- View statistics untuk undangan yang published

## Cara Menggunakan

### Seed Semua Data
```bash
php artisan migrate:fresh --seed
```

### Seed Spesifik
```bash
# Seed hanya templates
php artisan db:seed --class=TemplateSeeder

# Seed hanya users
php artisan db:seed --class=UserSeeder

# Seed hanya sample data
php artisan db:seed --class=SampleDataSeeder
```

### Reset dan Seed Ulang
```bash
php artisan migrate:fresh --seed
```

## Catatan
- Jalankan TemplateSeeder terlebih dahulu sebelum SampleDataSeeder
- Jalankan UserSeeder sebelum SampleDataSeeder
- SampleDataSeeder membutuhkan minimal 1 user dan 1 template
