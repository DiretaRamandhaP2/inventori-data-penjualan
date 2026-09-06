# PROMPT PENGERJAAN FITUR DASHBOARD

Tolong buatkan dan selesaikan **Fitur Dashboard (Statistik Ringkasan Bisnis & Penjualan)** pada Web App Inventory & Data Penjualan Laravel 13 ini dengan spesifikasi lengkap berikut:

---

## A. DESKRIPSI & SPESIFIKASI FITUR

Buatkan controller `DashboardController` dan view `resources/views/dashboard/index.blade.php` yang menampilkan 4 kartu statistik utama secara dinamis langsung dari database, serta grafik ringkasan penjualan 7 hari terakhir (atau statistik kategori produk):

1. **4 Kartu Ringkasan Statistik (Stat Cards - Dynamic DB Queries)**:
   - **Total Produk**: Menghitung jumlah seluruh varian produk yang terdaftar (`Inventory::count()`).
   - **Total Stok**: Menghitung akumulasi seluruh stok produk yang tersedia di gudang (`Inventory::sum('stock')`).
   - **Total Transaksi**: Menghitung jumlah riwayat transaksi penjualan yang telah terjadi (`Transaction::count()`).
   - **Total Penjualan**: Menghitung akumulasi pendapatan dari seluruh transaksi (`Transaction::sum('total')`) dikonversi ke format Rupiah (`Rp X.XXX.XXX`).

2. **Grafik / Ringkasan Penjualan & Produk (Visual Chart / Summary)**:
   - Grafik tren penjualan mingguan (7 hari terakhir) atau distribusi produk per kategori menggunakan Chart.js atau visual bar/progress indikator.
   - Tabel ringkasan 5 transaksi terbaru (Recent Transactions).
   - Tabel/list 5 produk dengan stok menipis (< 10) atau habis (0) sebagai peringatan dini inventaris (Low Stock Alert).

---

## B. BACKEND IMPLEMENTATION

1. **`DashboardController.php`**:
   - Method `index()`:
     - Query agregat database dinamis untuk 4 stat card (`totalProducts`, `totalStock`, `totalTransactions`, `totalRevenue`).
     - Query 5 transaksi terbaru (`Transaction::with('inventory')->orderBy('transaction_date', 'desc')->orderBy('id', 'desc')->take(5)->get()`).
     - Query 5 produk dengan stok menipis/habis (`Inventory::orderBy('stock', 'asc')->take(5)->get()`).
     - Data agregat penjualan per hari selama 7 hari terakhir untuk chart.
     - Merender view `dashboard.index` dengan data pendukung di atas.

2. **Routing & Sidebar Navigation**:
   - Daftarkan route `Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');` di `routes/web.php`.
   - Ubah redirect halaman root (`/`) agar mengarah ke `dashboard` (atau buatkan switcher navigasi yang mulus).
   - Update menu **Dashboard** di `resources/views/layouts/app.blade.php` agar aktif dan mengarah ke `route('dashboard')`.

---

## C. FRONTEND IMPLEMENTATION (Blade + Tailwind CSS + Chart.js / Alpine.js)

Struktur view:
`resources/views/dashboard/index.blade.php`

1. **Grid 4 Kartu Statistik (Responsive Grid `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`)**:
   - Card 1: Total Produk (Ikon Box/Cube, Warna Biru `#2563EB`)
   - Card 2: Total Stok Inventaris (Ikon Archive/Layers, Warna Hijau `#16A34A`)
   - Card 3: Total Transaksi (Ikon Shopping Cart/Receipt, Warna Ungu `#8B5CF6`)
   - Card 4: Total Pendapatan Penjualan (Ikon Wallet/Banknotes, Warna Kuning-Emas `#F59E0B`)

2. **Section Ringkasan Visual**:
   - Panel Grafik Penjualan (Chart.js / Canvas responsive).
   - Panel Alert Produk Stok Menipis/Habis dengan badge status warna yang konsisten (`success` ≥ 15, `warning` 1-14, `danger` 0).
   - Panel 5 Transaksi Penjualan Terbaru.

---

## D. DESIGN SYSTEM & TOKEN WARNA (WAJIB KONSISTEN)

Gunakan token warna yang telah dikonfigurasi di `tailwind.config.js`:
- Background Halaman: `bg-background` (`#F8F9FA`)
- Card & Surface: `bg-surface` (`#FFFFFF`) dengan `border-border` (`#E5E7EB`)
- Teks Utama: `text-textPrimary` (`#1A1D23`)
- Teks Sekunder: `text-textSecondary` (`#6B7280`)
- Aksesibilitas Status:
  - Stok Aman / Sukses: `success` (`#16A34A`)
  - Stok Menipis / Warning: `warning` (`#F59E0B`)
  - Stok Habis / Danger: `danger` (`#DC2626`)
  - Warna Primary: `primary` (`#2563EB`), `primary-dark` (`#1E40AF`)

---

## E. SKENARIO TESTING YANG WAJIB LOLOS

1. Angka pada 4 kartu statistik berubah secara otomatis dan presisi ketika data produk/transaksi ditambah atau dihapus dari DB.
2. Navigasi sidebar "Dashboard" berpindah halaman secara lancar dengan status link aktif yang sesuai.
3. Halaman responsif 100% pada tampilan desktop (`md:`, `lg:`) maupun mobile (`sm:`).
4. Tidak ada error saat database kosong (handle kasus `null` / `0` secara aman).
