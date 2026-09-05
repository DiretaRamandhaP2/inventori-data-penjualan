<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Membuat tabel inventories dengan primary key string custom (PRD001, PRD002, dst).
     * Primary key bukan auto-increment integer — karena format ID bersifat domain-specific.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            // Primary key: string custom, bukan auto-increment
            $table->string('id')->primary();

            $table->string('name');

            // Contoh: "Fashion", "Aksesoris", "Lifestyle"
            $table->string('category');

            // Harga satuan produk
            $table->decimal('price', 12, 2);

            // Stok saat ini — default 0, tidak boleh negatif (dijaga di level aplikasi & DB)
            $table->unsignedInteger('stock')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
