# Inventory & Data Penjualan

Aplikasi web untuk mengelola inventaris produk dan transaksi penjualan sederhana. Aplikasi menyediakan dashboard ringkasan bisnis, pengelolaan produk, pencatatan penjualan, validasi stok, serta tampilan responsif untuk desktop dan perangkat mobile.

## Informasi Proyek

| Item | Detail |
|---|---|
| Nama aplikasi | Web App Inventory & Data Penjualan |
| Developer | Direta Ramandha Pratama |
| Framework | Laravel `^13.17` |
| Bahasa antarmuka | Bahasa Indonesia |
| Status | Aplikasi pengelolaan inventory dan penjualan |

## Teknologi yang Digunakan

| Teknologi | Versi/konfigurasi | Kegunaan |
|---|---|---|
| PHP | `^8.3` | Runtime aplikasi |
| Laravel | `^13.17` | Framework backend dan routing |
| Blade | Laravel | Template halaman |
| Tailwind CSS | `^4.3.3` | Styling dan responsive layout |
| Alpine.js | 3.x CDN | Interaksi modal, sidebar, form, dan notifikasi |
| Chart.js | CDN | Grafik penjualan dashboard |
| Vite | `^8.0.0` | Build asset frontend |
| SQLite | Default `.env.example` | Database default development |
| Composer | Sesuai kebutuhan Laravel 13 | Dependency PHP |
| Node.js/NPM | Sesuai kebutuhan Vite 8 | Dependency dan build frontend |

## Fitur Utama

- **Dashboard**: menampilkan total produk, total stok, jumlah transaksi, total pendapatan, grafik penjualan tujuh hari, stok menipis, dan transaksi terbaru.
- **Inventory CRUD**: melihat, mencari, menambah, mengedit, dan menghapus produk.
- **Kategori produk**: kategori yang diterima adalah `Fashion`, `Aksesoris`, dan `Lifestyle`.
- **Status stok**: produk ditampilkan sebagai aman, menipis, atau habis berdasarkan jumlah stok.
- **Transaksi penjualan**: memilih produk, menentukan jumlah dan tanggal transaksi, serta menghitung total berdasarkan harga produk saat transaksi dibuat.
- **Filter penjualan**: mencari transaksi/produk dan menyaring berdasarkan rentang tanggal.
- **Validasi stok**: transaksi ditolak jika jumlah pembelian melebihi stok yang tersedia.
- **Pengurangan dan pengembalian stok**: stok berkurang ketika transaksi berhasil dibuat dan dikembalikan ketika transaksi dihapus.
- **Modal interaktif**: modal tambah/edit produk, tambah transaksi, dan konfirmasi penghapusan.
- **Notifikasi dan validasi**: flash message serta pesan validasi server dan frontend dalam Bahasa Indonesia.
- **Responsive design**: sidebar mobile, tabel/list responsif, modal yang dapat discroll, dan form yang menyesuaikan desktop, tablet, serta mobile.

## Struktur Folder Penting

```text
app/
├── Http/Controllers/       # Dashboard, inventory, dan transaksi
├── Http/Requests/          # Validasi request inventory dan transaksi
├── Models/                 # Inventory dan Transaction
└── Services/               # Logika transaksi dan pengelolaan stok
database/
├── migrations/             # Struktur tabel aplikasi
└── seeders/                # Data awal inventory dan transaksi
resources/
├── views/                  # Layout, dashboard, inventory, dan sales Blade
├── css/app.css             # Tailwind dan design token
└── js/app.js               # Entry point Vite
routes/web.php              # Route aplikasi
vite.config.js              # Konfigurasi Vite
tests/                      # Unit dan feature test bawaan
```

## Persyaratan Sistem

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan NPM yang mendukung Vite 8
- SQLite (default) atau database lain yang dikonfigurasi sesuai dukungan Laravel
- Extension PHP yang dibutuhkan Laravel, termasuk PDO dan driver SQLite jika menggunakan SQLite

## Instalasi dan Menjalankan Project

### Clone repository

URL repository belum tercantum pada project ini. Ganti placeholder berikut dengan URL yang benar:

```bash
git clone <URL_REPOSITORY>
cd <NAMA_FOLDER>
```

### Instalasi manual

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Konfigurasi database default menggunakan SQLite:

```env
DB_CONNECTION=sqlite
```

Pastikan file database tersedia. Pada instalasi baru, file tersebut dapat dibuat dengan:

```bash
touch database/database.sqlite
```

Jalankan migration dan seeder:

```bash
php artisan migrate --seed
```

Install serta build asset frontend:

```bash
npm install
npm run build
```

Jalankan aplikasi:

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000`. Selama pengembangan frontend, gunakan dua terminal:

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

## Quick Start

Script setup yang tersedia menjalankan instalasi dependency, pembuatan `.env`, pembuatan key, migration, instalasi NPM, dan build asset:

```bash
composer setup
php artisan serve
```

Jika database SQLite belum ada, buat `database/database.sqlite` sebelum migration.

## Konfigurasi Environment

Variabel utama pada `.env`:

| Variabel | Fungsi |
|---|---|
| `APP_NAME` | Nama aplikasi |
| `APP_ENV` | Environment aplikasi, misalnya `local` |
| `APP_KEY` | Kunci enkripsi Laravel; dibuat dengan `php artisan key:generate` |
| `APP_DEBUG` | Mengaktifkan detail error saat development |
| `APP_URL` | URL aplikasi |
| `DB_CONNECTION` | Driver database, default `sqlite` |
| `DB_DATABASE` | Lokasi/nama database jika diperlukan oleh driver |
| `VITE_APP_NAME` | Nama aplikasi untuk asset Vite |

## Database

Tabel bisnis utama:

- `inventories`: menyimpan `id` produk berbentuk string seperti `PRD001`, nama, kategori, harga, stok, dan timestamps.
- `transactions`: menyimpan `id` transaksi seperti `TRX001`, `inventory_id`, quantity, total, tanggal transaksi, dan timestamps.

Satu inventory memiliki banyak transaksi, sedangkan setiap transaksi merujuk pada satu inventory. Foreign key menggunakan `restrict`, sehingga produk yang masih memiliki transaksi terkait tidak dapat dihapus.

Saat transaksi dibuat, sistem mengunci produk, memvalidasi stok, menyimpan snapshot total `quantity × harga saat ini`, lalu mengurangi stok. Saat transaksi dihapus, quantity dikembalikan ke stok dalam database transaction.

## Alur Penggunaan

1. Buka dashboard untuk melihat ringkasan bisnis.
2. Buka Inventory dan tambah produk dengan nama, kategori, harga, serta stok.
3. Pantau status stok pada daftar produk.
4. Buka Data Penjualan lalu pilih produk.
5. Isi jumlah pembelian dan tanggal transaksi.
6. Periksa perkiraan total dan stok yang tersedia.
7. Simpan transaksi dan periksa stok yang berkurang.
8. Gunakan pencarian/filter untuk menemukan transaksi.
9. Hapus transaksi jika diperlukan; stok transaksi akan dikembalikan.

## Route atau Halaman

| Method | URL | Nama route | Fungsi |
|---|---|---|---|
| GET | `/` | - | Redirect ke dashboard |
| GET | `/dashboard` | `dashboard` | Dashboard |
| GET/POST/PUT/PATCH/DELETE | `/inventory` | `inventory.*` | CRUD inventory |
| GET/POST/DELETE | `/sales` | `sales.*` | Daftar, tambah, dan hapus transaksi |

Route sales tidak menyediakan `edit`, `update`, atau `show`.

## Validasi dan Aturan Bisnis

- Nama produk wajib, berupa teks, 3–255 karakter.
- Kategori wajib salah satu dari tiga kategori yang tersedia.
- Harga wajib berupa angka minimal 1 dengan maksimal dua angka desimal.
- Stok harus integer dan tidak boleh negatif.
- Produk transaksi harus tersedia di database.
- Quantity transaksi harus integer minimal 1.
- Tanggal transaksi tidak boleh melewati hari ini.
- Quantity tidak boleh melebihi stok tersedia.
- Total transaksi disimpan sebagai snapshot agar tidak berubah ketika harga produk berubah.

## Responsive Design

Layout menggunakan Tailwind CSS dengan breakpoint `sm`, `md`, dan `lg`. Sidebar permanen digunakan pada desktop dan dapat dibuka melalui hamburger pada mobile. Tabel desktop memiliki wrapper horizontal scroll dan halaman mobile menyediakan tampilan list/card. Form menggunakan lebar penuh pada layar kecil, sementara modal memiliki scroll vertikal ketika konten melebihi tinggi viewport.

## Pengujian

Folder `tests/` berisi test contoh Unit dan Feature Laravel. Jalankan dengan:

```bash
php artisan test
```

Pengujian manual yang disarankan mencakup CRUD inventory, transaksi dengan stok cukup/tidak cukup, penghapusan transaksi, filter, data kosong, serta tampilan pada desktop dan mobile.

## Perintah Artisan Berguna

```bash
php artisan migrate
php artisan migrate --seed
php artisan migrate:fresh --seed
php artisan db:seed
php artisan route:list
php artisan view:clear
php artisan view:cache
php artisan config:clear
php artisan cache:clear
php artisan test
```

## Troubleshooting

- **APP_KEY kosong**: jalankan `php artisan key:generate`.
- **Database gagal dibuka**: pastikan `DB_CONNECTION=sqlite` dan file `database/database.sqlite` tersedia.
- **Migration gagal**: periksa koneksi database, lalu gunakan `php artisan migrate:status`.
- **Asset Vite tidak ditemukan**: jalankan `npm install` lalu `npm run build` atau `npm run dev`.
- **Port 8000 terpakai**: jalankan `php artisan serve --port=8001`.
- **Cache atau view bermasalah**: jalankan `php artisan optimize:clear`.
- **Data tidak muncul**: jalankan `php artisan db:seed` dan pastikan migration telah selesai.
- **Stok tidak mencukupi**: kurangi quantity atau tambahkan stok produk.
- **Build frontend gagal**: periksa versi Node.js/NPM dan jalankan kembali `npm install`.

## Keamanan

- Jangan commit file `.env`.
- Jangan membagikan `APP_KEY` atau kredensial database.
- Validasi input tetap dilakukan di server melalui Form Request.
- Laravel CSRF protection digunakan untuk request aplikasi.
- Gunakan `APP_DEBUG=false` pada environment production.

## Pengembangan Lanjutan

Fitur yang sudah tersedia dijelaskan pada bagian [Fitur Utama](#fitur-utama). Rekomendasi pengembangan berikut belum dianggap sebagai fitur aktif:

- Authentication dan role management.
- Export laporan dan laporan per periode.
- Pagination untuk data dalam jumlah besar.
- Audit log, notifikasi stok minimum, dan import produk.
- API serta unit/feature test yang lebih lengkap.

## Kontributor

Direta Ramandha Pratama

## Lisensi

Lisensi belum ditentukan.