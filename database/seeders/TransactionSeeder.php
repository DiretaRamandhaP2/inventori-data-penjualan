<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Seed 10 transaksi historis ke tabel transactions.
     *
     * Catatan penting:
     * ─────────────────────────────────────────────────────────────────────────
     * 1. Kolom `total` adalah SNAPSHOT: dihitung dari quantity × harga produk
     *    pada SAAT seeding (bukan dihitung ulang secara dinamis dari harga saat ini).
     *    Jika harga produk diubah di kemudian hari, nilai `total` di sini tetap
     *    mencerminkan harga pada waktu transaksi terjadi.
     *
     * 2. Seeder ini TIDAK mengurangi kolom `stock` di tabel inventories.
     *    Data transaksi ini adalah riwayat historis; stok di tabel inventories
     *    dianggap sudah final (stok saat ini), tidak perlu dihitung mundur.
     * ─────────────────────────────────────────────────────────────────────────
     */
    public function run(): void
    {
        // Muat semua produk ke dalam map [name => model] agar pencarian O(1)
        // dan harga bisa diambil langsung dari objek Inventory (bukan hardcode).
        $inventoryMap = Inventory::all()->keyBy('name');

        $transactions = [
            ['id' => 'TRX001', 'transaction_date' => '2026-09-01', 'product_name' => 'Kaos Oversize Basic', 'quantity' => 2],
            ['id' => 'TRX002', 'transaction_date' => '2026-09-01', 'product_name' => 'Celana Jogger',        'quantity' => 1],
            ['id' => 'TRX003', 'transaction_date' => '2026-09-02', 'product_name' => 'Hoodie Premium',       'quantity' => 2],
            ['id' => 'TRX004', 'transaction_date' => '2026-09-02', 'product_name' => 'Totebag Canvas',       'quantity' => 3],
            ['id' => 'TRX005', 'transaction_date' => '2026-09-03', 'product_name' => 'Tumbler Stainless',    'quantity' => 2],
            ['id' => 'TRX006', 'transaction_date' => '2026-09-03', 'product_name' => 'Kaos Polo',            'quantity' => 1],
            ['id' => 'TRX007', 'transaction_date' => '2026-09-04', 'product_name' => 'Topi Baseball',        'quantity' => 2],
            ['id' => 'TRX008', 'transaction_date' => '2026-09-04', 'product_name' => 'Sandal Casual',        'quantity' => 2],
            ['id' => 'TRX009', 'transaction_date' => '2026-09-05', 'product_name' => 'Tas Selempang',        'quantity' => 1],
            ['id' => 'TRX010', 'transaction_date' => '2026-09-05', 'product_name' => 'Botol Minum Sport',    'quantity' => 3],
        ];

        foreach ($transactions as $trx) {
            /** @var Inventory $inventory */
            $inventory = $inventoryMap->get($trx['product_name']);

            // Snapshot total = quantity × harga produk pada saat transaksi dibuat
            $snapshotTotal = $trx['quantity'] * $inventory->price;

            Transaction::create([
                'id'               => $trx['id'],
                'inventory_id'     => $inventory->id,
                'quantity'         => $trx['quantity'],
                'total'            => $snapshotTotal, // nilai disimpan langsung, tidak dihitung ulang
                'transaction_date' => $trx['transaction_date'],
            ]);
        }
    }
}
