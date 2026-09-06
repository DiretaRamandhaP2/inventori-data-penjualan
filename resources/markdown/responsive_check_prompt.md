# PROMPT RESPONSIVE CHECK — DESKTOP & MOBILE

Tolong lakukan **Responsive Check menyeluruh** pada Web App Inventory & Data Penjualan Laravel 13 ini. Fokus pemeriksaan adalah memastikan seluruh halaman nyaman digunakan, tidak overflow, tidak ada elemen terpotong, dan seluruh aksi tetap dapat diakses pada tampilan desktop maupun mobile.

## A. SCOPE HALAMAN

Periksa minimal halaman berikut:

1. Layout utama: `resources/views/layouts/app.blade.php`
2. Dashboard: `resources/views/dashboard/index.blade.php`
3. Inventory list: `resources/views/inventory/index.blade.php`
4. Form tambah/edit inventory jika tersedia
5. Sales list/form: `resources/views/sales/index.blade.php` dan view terkait

Jangan mengubah controller, query, route, migration, seeder, atau logika stok kecuali ditemukan masalah yang secara langsung menyebabkan UI tidak dapat digunakan. Prioritaskan perubahan pada Blade, Tailwind CSS, dan JavaScript frontend.

## B. TARGET VIEWPORT

Uji pada ukuran viewport berikut:

### Desktop

- 1440 × 900
- 1280 × 800
- 1024 × 768

### Mobile

- 390 × 844 — ponsel portrait
- 375 × 667 — ponsel portrait kecil
- 844 × 390 — ponsel landscape

Jika tersedia, uji juga tablet pada 768 × 1024.

## C. CHECKLIST LAYOUT UTAMA

Pastikan:

- Tidak ada horizontal scroll pada `body` atau halaman, kecuali tabel memang menggunakan wrapper `overflow-x-auto`.
- Sidebar tampil permanen pada desktop dan dapat dibuka/ditutup pada mobile.
- Tombol hamburger hanya tampil pada mobile menggunakan breakpoint yang sesuai.
- Overlay sidebar mobile menutup sidebar ketika diklik.
- Sidebar tidak menutupi konten utama secara permanen pada mobile.
- Header/navbar tidak menyebabkan judul, avatar, atau tombol keluar layar.
- Konten utama menggunakan padding responsif, misalnya `p-4 md:p-8`.
- Lebar konten tidak melebihi viewport dan elemen menggunakan `min-w-0` bila diperlukan.
- Tidak ada elemen fixed/sticky yang menutupi tombol atau isi halaman.
- Semua link dan tombol memiliki area klik yang cukup, minimal sekitar 44 × 44 px untuk elemen penting.

## D. CHECKLIST DASHBOARD

Periksa halaman dashboard pada desktop dan mobile:

- Empat kartu statistik tersusun 4 kolom di desktop, 2 kolom pada tablet, dan 1 kolom pada mobile.
- Isi kartu tidak terpotong ketika angka statistik panjang.
- Header greeting berubah menjadi layout vertikal pada mobile.
- Tombol “Catat Transaksi Penjualan” memenuhi lebar atau tetap mudah disentuh pada mobile.
- Grafik memiliki tinggi yang proporsional dan tidak keluar dari card.
- Panel grafik dan alert stok tersusun satu kolom pada mobile.
- Tabel transaksi terbaru berada dalam wrapper horizontal scroll yang jelas pada mobile.
- Kolom tabel tidak memaksa seluruh halaman melebar.
- Alert stok tidak menyebabkan card melebihi lebar viewport.
- Link “Lihat Semua Inventaris” dan “Lihat Semua Transaksi” tetap terlihat dan mudah diklik.

## E. CHECKLIST INVENTORY & DATA PENJUALAN

Periksa halaman inventory dan sales:

- Search, filter, dan tombol aksi tersusun vertikal atau membungkus dengan rapi pada mobile.
- Tombol tambah data tetap terlihat, tidak keluar layar, dan mudah diklik.
- Tabel menggunakan `overflow-x-auto`, atau diubah ke pola card/list pada mobile jika desain aplikasi mendukungnya.
- Aksi edit dan hapus tidak bertumpuk atau terlalu kecil.
- ID, tanggal, harga, jumlah, dan total tetap terbaca.
- Form input tersusun satu kolom pada mobile dan dua kolom hanya pada breakpoint yang sesuai.
- Label input tidak bertabrakan dengan field atau icon.
- Input harga, stok, jumlah, dan tanggal memiliki lebar penuh pada mobile.
- Tombol simpan dan batal tersusun rapi serta tetap terlihat tanpa perlu zoom.
- Modal tidak keluar viewport, memiliki padding yang cukup, dan dapat discroll jika kontennya tinggi.
- Konfirmasi hapus dapat ditutup melalui tombol batal dan tidak mengunci halaman.

## F. BREAKPOINT & TAILWIND AUDIT

Audit penggunaan class Tailwind:

- Gunakan breakpoint Tailwind yang sudah dipakai project (`sm:`, `md:`, `lg:`) secara konsisten.
- Hindari fixed width seperti `w-[...]` yang membuat konten overflow pada mobile.
- Gunakan kombinasi responsif seperti `w-full`, `max-w-*`, `grid-cols-1`, `sm:grid-cols-2`, dan `lg:grid-cols-4` jika sesuai.
- Pastikan class responsive tidak tertimpa oleh class non-responsive yang bertentangan.
- Pastikan teks panjang menggunakan `break-words`, `truncate`, atau wrapping yang sesuai.
- Pastikan flex container menggunakan `flex-wrap` atau berubah menjadi `flex-col` pada mobile jika diperlukan.
- Jangan menghapus warna, spacing, status badge, atau design token yang sudah konsisten hanya demi responsive.

## G. AKSESIBILITAS & UX

Pastikan:

- Tombol dan link memiliki teks atau label yang jelas.
- Tombol icon memiliki `aria-label` jika tidak memiliki teks visual.
- Focus state tetap terlihat pada keyboard.
- Kontras teks dan background tetap memadai.
- Tidak ada aksi yang hanya dapat dilakukan melalui hover, karena hover tidak tersedia di mobile.
- Flash message, alert, dan error validasi tidak terpotong pada layar kecil.
- Ukuran font tidak terlalu kecil untuk tabel, label, dan tombol.
- Touch target tidak saling berhimpitan.

## H. PROSEDUR VALIDASI

Lakukan langkah berikut setelah perbaikan:

1. Jalankan `php artisan view:clear`.
2. Jalankan `php artisan view:cache` untuk memastikan seluruh Blade valid.
3. Jalankan `npm run build` jika perubahan menyentuh aset frontend atau class Tailwind.
4. Buka route `/dashboard`, `/inventory`, dan `/sales` pada seluruh viewport target.
5. Uji buka/tutup sidebar mobile.
6. Uji horizontal scroll pada tabel tanpa horizontal scroll pada seluruh body.
7. Uji tombol tambah, edit, hapus, konfirmasi, search, filter, dan submit form.
8. Uji halaman ketika data kosong dan ketika teks/angka cukup panjang.
9. Pastikan tidak ada error di browser console.
10. Pastikan setiap route memberikan HTTP 200 atau response yang memang sesuai untuk aksi form.

## I. OUTPUT YANG DIHARAPKAN

Laporkan hasil dalam format berikut:

```text
Responsive Check Result

Desktop:
- 1440 × 900: PASS/FAIL — catatan
- 1280 × 800: PASS/FAIL — catatan
- 1024 × 768: PASS/FAIL — catatan

Mobile:
- 390 × 844: PASS/FAIL — catatan
- 375 × 667: PASS/FAIL — catatan
- 844 × 390: PASS/FAIL — catatan

Halaman yang diperiksa:
- Layout: PASS/FAIL
- Dashboard: PASS/FAIL
- Inventory: PASS/FAIL
- Sales: PASS/FAIL

Perubahan yang dibuat:
- daftar file dan ringkasan perubahan

Validasi teknis:
- Blade cache: PASS/FAIL
- Frontend build: PASS/FAIL/SKIPPED
- Browser console: PASS/FAIL
- Horizontal overflow: PASS/FAIL

Temuan yang belum terselesaikan:
- tuliskan jika ada; jika tidak, tulis “Tidak ada”.
```

## J. BATASAN PERUBAHAN

- Pertahankan Laravel 13, Blade, Tailwind CSS, Alpine.js, dan Chart.js yang sudah digunakan project.
- Jangan menambahkan library baru hanya untuk menyelesaikan responsive check.
- Pertahankan design system warna yang telah ditentukan.
- Jangan mengubah struktur database atau perilaku bisnis aplikasi.
- Setiap perubahan harus diuji ulang pada desktop dan mobile.