# PLAN PENGERJAAN — WEB APP INVENTORY & DATA PENJUALAN
**Framework:** Laravel 13
**Deadline:** Maksimal 24 jam
**Sumber:** Brief Tes Web Developer (Revisi)

---

## 1. RINGKASAN PROJECT

Membangun web app sederhana untuk mengelola:
- Dashboard (ringkasan statistik)
- Inventory (CRUD produk)
- Data Penjualan (transaksi + logika stok otomatis)

Tanpa fitur login. Data dummy sudah disediakan brief dan wajib dipakai sebagai seed awal.

---

## 2. TECH STACK

| Layer | Teknologi |
|---|---|
| Backend | Laravel 13 (PHP 8.2+) |
| Database | MySQL / SQLite (SQLite lebih cepat untuk tes 24 jam) |
| Frontend | Blade + Tailwind CSS |
| Interaktivitas | Alpine.js (opsional, untuk modal/konfirmasi tanpa reload penuh) |
| Chart (bonus) | Chart.js |
| Export CSV (bonus) | Laravel Excel atau native `fputcsv` |
| Validasi | Laravel Form Request / validasi controller |
| Data storage | Database (Migration + Eloquent) — lebih aman dari Local Storage untuk demo teknis |

> Rekomendasi: pakai **SQLite** supaya reviewer tinggal `php artisan migrate --seed` tanpa setup MySQL.

---

## 3. SKEMA DATABASE

### Tabel `products`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | string/PK | PRD001, PRD002, dst |
| name | string | Nama produk |
| category | string | Fashion / Aksesoris / Lifestyle |
| price | decimal | Harga satuan |
| stock | integer | Stok saat ini |
| timestamps | | |

### Tabel `transactions`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | string/PK | TRX001, dst |
| product_id | FK → products | |
| quantity | integer | Jumlah dibeli |
| total | decimal | quantity × price (snapshot harga saat transaksi) |
| transaction_date | date | |
| timestamps | | |

> **Penting:** simpan `total` sebagai snapshot (bukan dihitung ulang dari harga produk saat ini), supaya riwayat transaksi tidak berubah jika harga produk diedit di kemudian hari.

---

## 4. STRUKTUR FITUR & URUTAN PENGERJAAN

Prioritaskan sesuai urutan ini (fitur wajib dulu, bonus terakhir):

1. **Setup project** — Laravel install, konfigurasi DB, migration, seeder data dummy
2. **Model & Relasi** — Product, Transaction (relasi belongsTo/hasMany)
3. **Inventory CRUD** — tambah, edit, hapus, list, search
4. **Validasi input Inventory** — nama wajib, harga & stok numerik ≥ 0
5. **Data Penjualan** — tambah transaksi, list, search/filter
6. **Logika stok otomatis** — kurangi stok saat transaksi berhasil, tolak jika stok kurang
7. **Dashboard** — total produk, total stok, total transaksi, total penjualan (query dinamis dari DB)
8. **Hapus data + konfirmasi** — modal/alert konfirmasi sebelum hapus (Inventory & Transaksi)
9. **Feedback UI** — notifikasi sukses/gagal (alert/toast) di semua aksi
10. **Responsive check** — desktop & mobile
11. **Bonus (jika waktu cukup)** — filter kategori, sorting harga, pagination, export CSV, grafik penjualan, dark mode

---

## 5. LOGIKA VALIDASI STOK (WAJIB, JANGAN SAMPAI SALAH)

```
IF jumlah_pembelian > stok_produk:
    tolak transaksi
    tampilkan pesan error
    stok TIDAK berubah
ELSE:
    simpan transaksi
    stok_produk -= jumlah_pembelian
    tampilkan pesan sukses
```

Gunakan **DB Transaction** (`DB::transaction()`) saat proses simpan transaksi + update stok, supaya kalau salah satu proses gagal, keduanya di-rollback (data tidak setengah-setengah).

### Skenario wajib dites (sesuai brief):
- Beli 3 Kaos Oversize Basic (stok 30 → 27, total Rp225.000)
- Beli 20 Tas Selempang (stok hanya 8) → **ditolak**, stok tetap 8
- Search "Hoodie" → muncul Hoodie Premium
- Hapus data → harus ada konfirmasi dulu

---

## 6. WARNA / DESIGN SYSTEM

Gunakan Tailwind CSS dengan custom color token berikut (skema netral + aksen biru, sesuai diskusi sebelumnya):

```css
/* tailwind.config.js — extend.colors */
colors: {
  background: '#F8F9FA',
  surface: '#FFFFFF',
  border: '#E5E7EB',
  textPrimary: '#1A1D23',
  textSecondary: '#6B7280',
  primary: {
    DEFAULT: '#2563EB',
    dark: '#1E40AF',
  },
  sidebar: '#1E293B',
  success: '#16A34A',
  warning: '#F59E0B',
  danger: '#DC2626',
  chart: {
    1: '#2563EB',
    2: '#16A34A',
    3: '#F59E0B',
    4: '#8B5CF6',
  }
}
```

### Penerapan warna per elemen:

| Elemen UI | Warna |
|---|---|
| Background halaman | `#F8F9FA` |
| Card/Panel | `#FFFFFF` dengan border `#E5E7EB` |
| Sidebar/Navbar | `#1E293B` (navy gelap), teks putih |
| Tombol utama (Tambah, Simpan) | `#2563EB`, hover `#1E40AF` |
| Badge stok aman | `#16A34A` (hijau) |
| Badge stok menipis (misal < 10) | `#F59E0B` (kuning) |
| Badge stok habis (0) | `#DC2626` (merah) |
| Teks judul/heading | `#1A1D23` |
| Teks sekunder/label | `#6B7280` |
| Grafik penjualan (bonus) | Kombinasi biru, hijau, kuning, ungu |

> Konsisten pakai warna status yang sama untuk **stok** dan **untung/rugi** di seluruh aplikasi agar user langsung paham tanpa baca teks.

---

## 7. STRUKTUR HALAMAN (BLADE VIEWS)

```
resources/views/
├── layouts/
│   └── app.blade.php        (sidebar navy + konten, responsive)
├── dashboard/

│   └── index.blade.php      (4 kartu statistik + grafik bonus)
├── inventory/
│   ├── index.blade.php      (tabel produk + search + tombol CRUD)
│   ├── create.blade.php / modal
│   └── edit.blade.php / modal
└── sales/
    ├── index.blade.php      (tabel transaksi + search/filter)
    └── create.blade.php / modal
```

Gunakan modal (Alpine.js) untuk tambah/edit/hapus supaya UX lebih cepat tanpa reload halaman penuh — nilai plus untuk "UI/UX rapi".

---

## 8. CHECKLIST SESUAI BRIEF

- [ ] Dashboard: Total Produk, Total Stok, Total Transaksi, Total Penjualan (dinamis)
- [ ] CRUD Inventory lengkap (tambah, edit, hapus, list)
- [ ] Search produk
- [ ] Tambah transaksi
- [ ] Total otomatis per transaksi
- [ ] Stok berkurang otomatis
- [ ] Validasi stok (tolak jika beli > stok)
- [ ] Hapus data + konfirmasi
- [ ] Responsive desktop & mobile
- [ ] Feedback sukses/gagal di setiap aksi
- [ ] Seed data dummy sesuai lampiran brief
- [ ] README lengkap (nama, teknologi, cara run, penjelasan fitur)

---

## 9. RULE / PANTANGAN — HAL YANG TIDAK BOLEH DILAKUKAN

### A. Rule Teknis (Laravel)
1. **Jangan** hitung ulang `total` transaksi dari harga produk terbaru — gunakan snapshot harga saat transaksi dibuat.
2. **Jangan** update stok tanpa dibungkus `DB::transaction()` — race condition bisa bikin stok minus atau data korup.
3. **Jangan** taruh logika bisnis (pengurangan stok, validasi stok) di Controller yang menumpuk — pisahkan ke Service/Action class atau minimal method rapi di Model, supaya mudah dites dan dijelaskan.
4. **Jangan** query N+1 (misal loop transaksi lalu query produk satu-satu) — gunakan Eloquent `with()` eager loading.
5. **Jangan** validasi hanya di frontend (JS) tanpa validasi di backend (Form Request) — data bisa dikirim langsung via request tanpa lewat form.
6. **Jangan** hardcode data dummy di Blade — gunakan Seeder & Migration supaya data konsisten setelah `migrate:fresh --seed`.
7. **Jangan** gunakan `id` auto-increment default kalau reviewer minta format ID custom (PRD001, TRX001) — buat generator ID sendiri atau kolom kode terpisah.
8. **Jangan** biarkan mass assignment terbuka (`$fillable` harus didefinisikan jelas di tiap Model).
9. **Jangan** commit file `.env` ke repository — gunakan `.env.example`.

### B. Rule Logika Bisnis
10. **Jangan** izinkan transaksi tersimpan jika `quantity > stock` — ini requirement paling kritis di brief, jadi harus ditest ulang manual sebelum submit.
11. **Jangan** kurangi stok inventory berdasarkan data transaksi awal (dummy) — brief menegaskan stok inventory dianggap stok **saat ini**, transaksi awal hanya riwayat.
12. **Jangan** izinkan input negatif atau non-angka di field harga/stok/jumlah.
13. **Jangan** hapus data tanpa konfirmasi (modal/alert) — wajib sesuai checklist brief.

### C. Rule UI/UX
14. **Jangan** buat UI yang hanya bagus di desktop tapi rusak di mobile — cek breakpoint Tailwind (`sm:`, `md:`, `lg:`) di semua halaman.
15. **Jangan** biarkan aksi (tambah/edit/hapus/transaksi) tanpa feedback visual (toast/alert sukses atau gagal) — brief eksplisit minta ini.
16. **Jangan** pakai warna status secara tidak konsisten (misal hijau di satu halaman berarti "aman", tapi di halaman lain berarti "baru") — samakan makna warna di seluruh app.
17. **Jangan** biarkan tabel panjang tanpa pagination/scroll yang jelas jika data mulai banyak (minimal siapkan pagination dasar Laravel `paginate()`).

### D. Rule Pengumpulan
18. **Jangan** submit tanpa README (nama kandidat, teknologi, cara jalankan, penjelasan fitur, fitur tambahan).
19. **Jangan** submit tanpa mengetes ulang 6 skenario testing di brief (tambah produk, edit stok, transaksi normal, validasi stok gagal, search, hapus+konfirmasi).
20. **Jangan** lupa jelaskan kode sendiri — brief menekankan kandidat harus memahami dan bisa menjelaskan teknologi yang dipakai (kemungkinan ada sesi wawancara/review kode).

---

## 10. TIMELINE SARAN (24 JAM)

| Waktu | Aktivitas |
|---|---|
| Jam 0–2 | Setup Laravel, migration, seeder, model & relasi |
| Jam 2–6 | Inventory CRUD + validasi + search |
| Jam 6–10 | Data Penjualan + logika stok + validasi stok |
| Jam 10–13 | Dashboard (statistik dinamis) |
| Jam 13–15 | Styling (Tailwind + warna sesuai design system) + responsive |
| Jam 15–17 | Feedback UI, konfirmasi hapus, testing skenario brief |
| Jam 17–20 | Bonus (kalau waktu cukup): filter, sorting, pagination, export CSV, grafik |
| Jam 20–22 | Testing ulang menyeluruh + perbaikan bug |
| Jam 22–24 | README + push ke GitHub / zip project |

---

## 11. CATATAN PENJELASAN KODE (untuk persiapan wawancara)

Siapkan penjelasan singkat untuk:
- Kenapa pakai Laravel 13 + Blade/Tailwind (bukan SPA) → lebih cepat untuk scope kecil dalam 24 jam
- Bagaimana logika pengurangan stok bekerja (DB transaction)
- Bagaimana validasi stok mencegah overselling
- Struktur Model/Migration/Seeder
- Bagaimana dashboard mengambil angka secara dinamis (query aggregate: `count()`, `sum()`)
