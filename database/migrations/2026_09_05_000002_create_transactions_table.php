<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Membuat tabel transactions dengan primary key string custom (TRX001, TRX002, dst).
     *
     * Catatan onDelete('restrict') vs cascade:
     * ─────────────────────────────────────────
     * Dipilih RESTRICT karena transaksi adalah riwayat keuangan yang tidak boleh
     * hilang hanya karena produk dihapus. Dengan RESTRICT, sistem mencegah
     * penghapusan produk selama masih ada transaksi yang merujuknya — memaksa
     * operator menyelesaikan/mengarsipkan data terlebih dahulu. Ini jauh lebih
     * aman dibanding CASCADE yang akan menghapus riwayat penjualan secara diam-diam.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            // Primary key: string custom, bukan auto-increment
            $table->string('id')->primary();

            // Foreign key ke tabel inventories
            $table->string('inventory_id');

            // Jumlah barang yang dibeli dalam satu transaksi
            $table->unsignedInteger('quantity');

            /**
             * SNAPSHOT total harga pada saat transaksi dibuat (quantity x harga saat itu).
             * Disimpan langsung — BUKAN dihitung ulang dari harga produk saat ini —
             * sehingga riwayat transaksi tetap akurat meskipun harga produk diubah
             * di kemudian hari.
             */
            $table->decimal('total', 12, 2);

            $table->date('transaction_date');

            $table->timestamps();

            // Foreign key constraint dengan RESTRICT:
            // produk tidak bisa dihapus selama masih ada transaksi terkait
            $table->foreign('inventory_id')
                ->references('id')
                ->on('inventories')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
