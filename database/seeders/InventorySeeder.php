<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Seed 10 produk awal ke tabel inventories.
     *
     * ID di-hardcode sesuai spesifikasi (PRD001–PRD010).
     * Stok yang diisi adalah stok SAAT INI (final) — tidak perlu
     * dikurangi berdasarkan transaksi historis karena seeder transaksi
     * hanya mencatat riwayat, bukan memotong stok secara retroaktif.
     */
    public function run(): void
    {
        $products = [
            ['id' => 'PRD001', 'name' => 'Kaos Oversize Basic', 'category' => 'Fashion',    'price' => 75000,  'stock' => 25],
            ['id' => 'PRD002', 'name' => 'Celana Jogger',        'category' => 'Fashion',    'price' => 120000, 'stock' => 15],
            ['id' => 'PRD003', 'name' => 'Hoodie Premium',       'category' => 'Fashion',    'price' => 185000, 'stock' => 10],
            ['id' => 'PRD004', 'name' => 'Totebag Canvas',       'category' => 'Aksesoris',  'price' => 65000,  'stock' => 30],
            ['id' => 'PRD005', 'name' => 'Topi Baseball',        'category' => 'Aksesoris',  'price' => 55000,  'stock' => 20],
            ['id' => 'PRD006', 'name' => 'Tumbler Stainless',    'category' => 'Lifestyle',  'price' => 95000,  'stock' => 18],
            ['id' => 'PRD007', 'name' => 'Botol Minum Sport',    'category' => 'Lifestyle',  'price' => 70000,  'stock' => 12],
            ['id' => 'PRD008', 'name' => 'Tas Selempang',        'category' => 'Aksesoris',  'price' => 135000, 'stock' => 8],
            ['id' => 'PRD009', 'name' => 'Sandal Casual',        'category' => 'Fashion',    'price' => 85000,  'stock' => 14],
            ['id' => 'PRD010', 'name' => 'Kaos Polo',            'category' => 'Fashion',    'price' => 110000, 'stock' => 20],
        ];

        foreach ($products as $product) {
            Inventory::create($product);
        }
    }
}
