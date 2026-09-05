<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan pemanggilan seeder PENTING:
     * 1. InventorySeeder harus dijalankan lebih dulu karena tabel transactions
     *    memiliki foreign key constraint yang merujuk ke tabel inventories.
     *    Jika TransactionSeeder dijalankan lebih dulu, insert akan gagal karena
     *    inventory_id belum ada di tabel inventories.
     * 2. TransactionSeeder dijalankan setelah inventories sudah terisi.
     */
    public function run(): void
    {
        $this->call([
            InventorySeeder::class,
            TransactionSeeder::class,
        ]);
    }
}

