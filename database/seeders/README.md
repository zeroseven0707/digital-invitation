# Database Seeders

## Overview
This directory contains database seeders for the Digital Invitation application.

## Available Seeders

### 1. TemplateSeeder
Seeds 9 active invitation templates:
- Classic Elegant
- Modern Minimalist
- Romantic Floral
- Luxury Gold
- Rustic Vintage
- Ocean Breeze
- Garden Party
- Night Sky
- Parallax Mountain

### 2. ProductionSeeder
Seeds production-ready data:
- **Admin User**: admin@nikahin.com / password
- **Regular User**: user@nikahin.com / password
- **Sample Invitation**: Published and paid invitation for Anisa & Dian using Classic Elegant template

## Usage

### Fresh Installation
```bash
php artisan migrate:fresh --seed
```

### Run Specific Seeder
```bash
php artisan db:seed --class=TemplateSeeder
php artisan db:seed --class=ProductionSeeder
```

## Production Data Details

### Bride Information
- Name: Anisa Rismayanti
- Father: Mumuh Muhlisin (ALM)
- Mother: Siti Komariah

### Groom Information
- Name: Dian Nurdilan
- Father: Emuh Saepulloh
- Mother: Ii Jahroi

### Event Details
- **Akad**: Sunday, March 29, 2026, 08:00-10:00
- **Reception**: Sunday, March 29, 2026, 10:00-selesai
- **Location**: Rumah Mempelai Wanita
- **Address**: Kp. Sindangraja RT 11 RW 04 Desa Linggawangi Kec. Leuwisari Kab. Tasikmalaya

### Invitation Status
- Status: Published
- Payment: Paid
- Template: Classic Elegant
