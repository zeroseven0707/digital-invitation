# Mobile API Setup Guide

Panduan untuk setup backend API untuk mobile application.

## 📋 Prerequisites

- Laravel 11.x sudah terinstall
- Database MySQL/PostgreSQL running
- Composer installed

## 🚀 Installation Steps

### 1. Install Laravel Sanctum

Sanctum sudah terinstall. Jika belum:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 2. Run Migrations

```bash
php artisan migrate
```

Ini akan membuat tables:
- `personal_access_tokens` (untuk Sanctum tokens)
- Update `users` table dengan kolom `role`

### 3. Configure CORS

Edit `config/cors.php`:

```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Untuk development
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

### 4. Update .env

Pastikan konfigurasi database benar:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=digital_invitation
DB_USERNAME=root
DB_PASSWORD=

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

### 5. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## 📡 API Endpoints

Base URL: `http://your-domain.com/api`

### Authentication

#### Register
```
POST /api/register
Body: {
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login
```
POST /api/login
Body: {
  "email": "john@example.com",
  "password": "password123"
}
Response: {
  "success": true,
  "user": {...},
  "token": "1|xxxxx..."
}
```

#### Logout
```
POST /api/logout
Headers: {
  "Authorization": "Bearer {token}"
}
```

#### Get User
```
GET /api/user
Headers: {
  "Authorization": "Bearer {token}"
}
```

### Invitations

#### Get All Invitations
```
GET /api/invitations
Headers: {
  "Authorization": "Bearer {token}"
}
```

#### Get Single Invitation
```
GET /api/invitations/{id}
Headers: {
  "Authorization": "Bearer {token}"
}
```

#### Create Invitation
```
POST /api/invitations
Headers: {
  "Authorization": "Bearer {token}"
}
Body: {
  "template_id": 1,
  "bride_name": "Jane Doe",
  "groom_name": "John Smith",
  "akad_date": "2026-12-25",
  "reception_date": "2026-12-25",
  ...
}
```

#### Update Invitation
```
PUT /api/invitations/{id}
Headers: {
  "Authorization": "Bearer {token}"
}
Body: {
  "bride_name": "Jane Doe Updated",
  ...
}
```

#### Delete Invitation
```
DELETE /api/invitations/{id}
Headers: {
  "Authorization": "Bearer {token}"
}
```

#### Publish Invitation
```
POST /api/invitations/{id}/publish
Headers: {
  "Authorization": "Bearer {token}"
}
```

#### Unpublish Invitation
```
POST /api/invitations/{id}/unpublish
Headers: {
  "Authorization": "Bearer {token}"
}
```

### Guests

#### Get All Guests
```
GET /api/invitations/{invitation_id}/guests
Headers: {
  "Authorization": "Bearer {token}"
}
```

#### Add Guest
```
POST /api/invitations/{invitation_id}/guests
Headers: {
  "Authorization": "Bearer {token}"
}
Body: {
  "name": "Guest Name",
  "category": "family",
  "whatsapp_number": "08123456789"
}
```

#### Update Guest
```
PUT /api/invitations/{invitation_id}/guests/{guest_id}
Headers: {
  "Authorization": "Bearer {token}"
}
Body: {
  "name": "Updated Name",
  ...
}
```

#### Delete Guest
```
DELETE /api/invitations/{invitation_id}/guests/{guest_id}
Headers: {
  "Authorization": "Bearer {token}"
}
```

### RSVPs

#### Get All RSVPs
```
GET /api/invitations/{invitation_id}/rsvps
Headers: {
  "Authorization": "Bearer {token}"
}
```

### Statistics

#### Get Statistics
```
GET /api/invitations/{invitation_id}/statistics
Headers: {
  "Authorization": "Bearer {token}"
}
```

## 🔐 Security

### Rate Limiting

API routes sudah dilindungi dengan rate limiting default Laravel (60 requests per minute).

### Authentication

- Menggunakan Laravel Sanctum untuk token-based authentication
- Token disimpan di `personal_access_tokens` table
- Token dikirim via `Authorization: Bearer {token}` header

### Authorization

- Setiap endpoint memeriksa ownership (user hanya bisa akses data mereka sendiri)
- Admin tidak bisa login ke mobile app (hanya user biasa)

## 🧪 Testing API

### Using cURL

```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Get Invitations (replace TOKEN with actual token)
curl -X GET http://localhost:8000/api/invitations \
  -H "Authorization: Bearer TOKEN"
```

### Using Postman

1. Import collection dari file `postman_collection.json` (jika ada)
2. Set environment variable `base_url` = `http://localhost:8000/api`
3. Set environment variable `token` setelah login
4. Test semua endpoints

## 🐛 Troubleshooting

### Error: "Unauthenticated"
- Pastikan token valid dan dikirim di header
- Check token belum expired atau deleted

### Error: "CORS"
- Update `config/cors.php`
- Clear config cache: `php artisan config:clear`

### Error: "Route not found"
- Check `routes/api.php` sudah benar
- Run `php artisan route:list` untuk lihat semua routes
- Clear route cache: `php artisan route:clear`

### Error: "Database connection"
- Check `.env` database configuration
- Pastikan MySQL/PostgreSQL running
- Test connection: `php artisan migrate:status`

## 📝 Notes

- API prefix: `/api`
- All responses dalam format JSON
- Timestamps menggunakan format ISO 8601
- Pagination belum diimplementasikan (akan ditambahkan jika diperlukan)

## 🔄 Updates

### Version 1.0.0 (2026-05-06)
- Initial API release
- Authentication endpoints
- Invitation CRUD
- Guest management
- RSVP viewing
- Statistics

---

**Last Updated**: 2026-05-06  
**API Version**: 1.0.0
