# SI-Pedia

Ensiklopedia digital untuk Program Studi Sistem Informasi, Universitas Indraprasta PGRI. Platform ini memungkinkan pengelolaan artikel, data dosen, kategori, review, dan komentar — dengan panel admin lengkap dan dashboard statistik.

## Fitur

- **Artikel** — CRUD lengkap dengan kategori, gambar, slug otomatis, dan scheduled publishing
- **Komentar** — Pengunjung terautentikasi bisa berkomentar di setiap artikel
- **Dosen** — Data dosen dengan foto, NIDN, dan proses approval
- **Kategori** — Kelompokkan artikel berdasarkan topik
- **Review** — Sistem review untuk artikel/proyek yang masuk
- **Admin Panel** — Dashboard dengan grafik, statistik, fast action, dan activity log
- **Profil** — Profil user dan admin dengan upload avatar
- **Bulk Action** — Publish, draft, atau hapus artikel secara massal
- **Activity Log** — Jejak aktivitas admin tersimpan otomatis
- **Role-based Access** — Admin dan user dengan akses berbeda

## Stack

- Laravel 13 · PHP 8.3
- Tailwind CSS v4
- SQLite (default) / MySQL
- Vite 6
- Alpine.js

## Persyaratan

- PHP >= 8.3 dengan ekstensi: `pdo_sqlite`, `mbstring`, `openssl`, `curl`, `gd`, `fileinfo`
- Composer >= 2.x
- Node.js >= 18.x & npm >= 9.x

## Instalasi

```bash
# Clone repo
git clone https://github.com/ronggothelast/si-pedia.git
cd si-pedia

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database sudah disertakan (pre-seeded). Tidak perlu langkah tambahan.
# Jika ingin reset dari awal:
# php artisan migrate:fresh --seed

# Build assets
npm run build

# Jalankan server
php artisan serve
```

Buka `http://localhost:8000` di browser.

Panduan instalasi lengkap (termasuk Windows, macOS, dan deploy ke server): lihat [`tutorial.md`](tutorial.md).

## Akun Default

| Role  | Email                      | Password  |
|-------|----------------------------|-----------|
| Admin | adminSIPedia@gmail.com     | `password` |
| User  | ucupganteng@gmail.com      | `password` |

## Struktur Project

```
app/
├── Http/
│   ├── Controllers/    ← 11 controller (Article, Auth, Category, Comment, Dosen, Homepage, Page, Profile, Review, User)
│   └── Middleware/     ← AdminMiddleware
└── Models/             ← 8 model + HasSlug trait

database/
├── migrations/         ← 13 file migrasi
└── seeders/            ← DatabaseSeeder (user, kategori, artikel, dosen, review)

resources/
├── css/app.css         ← Tailwind v4 + design tokens
├── js/app.js           ← Axios setup
└── views/
    ├── auth/           ← 5 halaman autentikasi
    ├── components/     ← 6 komponen reusable
    ├── errors/         ← 3 halaman error (403, 404, 500)
    ├── layouts/        ← Layout utama
    └── pages/          ← 17 halaman (publik + admin)

routes/
└── web.php             ← Semua routing
```

## Routing

### Publik
| Method | URI | Fungsi |
|--------|-----|--------|
| GET | `/` | Homepage |
| GET | `/about` | Tentang prodi |
| GET | `/catalog` | Katalog artikel |
| GET | `/articles/{slug}` | Detail artikel |
| GET | `/review` | Daftar review |

### Admin (`/admin`)
| Method | URI | Fungsi |
|--------|-----|--------|
| GET | `/admin` | Dashboard |
| GET/POST | `/admin/articles` | Kelola artikel |
| GET/POST | `/admin/categories` | Kelola kategori |
| GET/POST | `/admin/dosen` | Kelola dosen |
| GET | `/admin/users` | Kelola user |
| GET | `/admin/report` | Laporan |

## Database

8 tabel utama:

- **users** — Akun dengan role (admin/user)
- **categories** — Kategori artikel
- **articles** — Konten artikel (soft delete, slug, scheduled publishing)
- **lecturers** — Data dosen dengan status approval
- **reviews** — Review artikel/proyek
- **comments** — Komentar pada artikel
- **pages** — Halaman CMS (homepage editable)
- **activity_logs** — Jejak aktivitas (polymorphic)

Detail lengkap schema, relasi, controller, dan view: lihat [`docs.md`](docs.md).

## Konfigurasi Database MySQL

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipedia
DB_USERNAME=root
DB_PASSWORD=your_password
```

## Deployment

```bash
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Konfigurasi Nginx dan Apache tersedia di [`tutorial.md`](tutorial.md#14-deploy-ke-server-produksi).

## Dokumentasi

- [`tutorial.md`](tutorial.md) — Panduan instalasi lengkap untuk semua platform
- [`docs.md`](docs.md) — Dokumentasi teknis (database, routing, controller, view, middleware)

## License

MIT
