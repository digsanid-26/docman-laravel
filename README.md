# DMS Docman - Document Management System

> **Versi:** Laravel 11.x  
> **Production:** https://docman.digsan.id  
> **GitHub:** https://github.com/digsanid-26/docman-laravel

---

## Fitur Utama

- **User Registration & Login** - Autentikasi dengan role (user/admin)
- **Submit Dokumen** - Upload file dengan judul, jenis, deskripsi, tanggal
- **Admin Review** - Approval workflow dengan catatan revisi
- **Email Notifications** - Notifikasi otomatis ke user & admin
- **Export Excel** - Export daftar dokumen dengan Maatwebsite Excel
- **File Storage** - Dokumen approved tersimpan di direktori terpisah

---

## Tech Stack

- **Framework:** Laravel 11.x (PHP 8.2+)
- **Database:** MySQL 8.0 / SQLite (dev)
- **Frontend:** Blade + TailwindCSS
- **Auth:** Laravel Breeze
- **Excel Export:** Maatwebsite/Laravel-Excel
- **Queue:** Database driver dengan Supervisor (production)

---

## Quick Start (Development)

### 1. Clone & Install

```bash
git clone https://github.com/digsanid-26/docman-laravel.git
cd docman-laravel
composer install
npm install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` dan sesuaikan database credentials.

### 3. Database & Seed

```bash
php artisan migrate --seed
```

Default admin: `admin@docman.local` / `admin123` (ganti setelah login!)

### 4. Storage Link

```bash
php artisan storage:link
```

### 5. Run Development Server

```bash
# Terminal 1: Laravel dev server
php artisan serve

# Terminal 2: Vite dev server (auto-reload CSS/JS)
npm run dev
```

Akses: http://localhost:8000

---

## Struktur Aplikasi

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # Login, Register, Logout
│   │   ├── DocumentController.php    # User: CRUD dokumen
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── DocumentReviewController.php
│   │       └── ExportController.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── User.php
│   ├── Document.php
│   └── DocumentReview.php
├── Mail/                   # Email notifications
└── Exports/                # Excel export classes

resources/views/
├── auth/                   # Login, Register
├── documents/              # User views
└── admin/                  # Admin panel views

storage/app/
├── documents/              # Temporary uploads
└── approved/               # Approved documents
```

---

## Status Dokumen

| Status | Keterangan |
|--------|------------|
| `SUBMITTED` | Dokumen baru diupload user |
| `UNDER_REVIEW` | Admin sedang mereview |
| `NEEDS_REVISION` | Perlu revisi dari user |
| `APPROVED` | Disetujui, file dipindah ke /approved |
| `REJECTED` | Ditolak |

---

## Deployment

Lihat panduan lengkap di [`../docs/deployment-guide.md`](../docs/deployment-guide.md) untuk setup production di Ubuntu 22.04 dengan:
- Nginx + PHP 8.2-FPM
- MySQL 8.0
- SSL Let's Encrypt
- Queue Worker (Supervisor)
- Scheduler (Cron)

---

## Testing Checklist

### Manual Testing Flow

1. **Register** sebagai user baru
2. **Login** sebagai user
3. **Submit dokumen** dengan file upload
4. **Logout**, **login** sebagai admin
5. **Review dokumen** - beri catatan & approve/reject
6. **Logout**, **login** kembali sebagai user
7. Cek **notifikasi email** (gunakan Mailtrap untuk dev)
8. **Export Excel** dari admin panel

---

## Dokumentasi Spesifikasi

- [Spec Laravel](../docs/spec-laravel.md) - Spesifikasi teknis lengkap
- [Progress Tracker](../docs/progress-laravel.md) - Status pengembangan
- [Deployment Guide](../docs/deployment-guide.md) - Panduan deployment Ubuntu 22.04

---

## License

MIT License - Proyek internal Digisanti.
