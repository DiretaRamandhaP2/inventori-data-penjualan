<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    // ─── Primary Key Configuration ────────────────────────────────────────────

    /**
     * Primary key menggunakan string custom (TRX001, TRX002, dst),
     * bukan integer auto-increment.
     */
    public $incrementing = false;

    /** @var string */
    protected $keyType = 'string';

    // ─── Mass Assignment ──────────────────────────────────────────────────────

    /** @var list<string> */
    protected $fillable = [
        'id',
        'inventory_id',
        'quantity',
        'total',
        'transaction_date',
    ];

    // ─── Attribute Casting ────────────────────────────────────────────────────

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity'         => 'integer',
            'total'            => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Setiap transaksi merujuk ke satu produk di inventaris.
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    // ─── ID Generation ───────────────────────────────────────────────────────

    /**
     * Generate ID berikutnya secara otomatis berdasarkan ID terakhir di database.
     *
     * Logika:
     * - Ambil ID terakhir yang tersimpan (diurutkan DESC agar mendapat nilai tertinggi).
     * - Ekstrak bagian angkanya (strip prefix "TRX"), tambah 1, lalu pad kembali ke 3 digit.
     * - Jika tabel masih kosong, mulai dari TRX001.
     *
     * Contoh: TRX010 → angka 10 → +1 → 11 → pad → TRX011
     */
    public static function generateNextId(): string
    {
        // Ambil ID terakhir (tertinggi secara leksikal) di tabel
        $lastId = static::orderBy('id', 'desc')->value('id');

        if ($lastId === null) {
            // Tabel masih kosong, mulai dari TRX001
            return 'TRX001';
        }

        // Ekstrak angka dari ID: "TRX010" → "010" → (int) 10
        $numericPart = (int) substr($lastId, 3);

        // Tambah 1 lalu format ulang dengan padding 3 digit
        return 'TRX' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
    }
}

