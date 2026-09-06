<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    // ─── Primary Key Configuration ────────────────────────────────────────────

    /**
     * Primary key menggunakan string custom (PRD001, PRD002, dst),
     * bukan integer auto-increment.
     */
    public $incrementing = false;

    /** @var string */
    protected $keyType = 'string';

    // ─── Mass Assignment ──────────────────────────────────────────────────────

    /** @var list<string> */
    protected $fillable = [
        'id',
        'name',
        'category',
        'price',
        'stock',
    ];

    // ─── Attribute Casting ────────────────────────────────────────────────────

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Satu produk bisa memiliki banyak transaksi.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'inventory_id');
    }

    // ─── ID Generation ───────────────────────────────────────────────────────

    /**
     * Generate ID berikutnya secara otomatis berdasarkan ID terakhir di database.
     *
     * Logika:
     * - Ambil ID terakhir yang tersimpan (diurutkan DESC agar mendapat nilai tertinggi).
     * - Ekstrak bagian angkanya (strip prefix "PRD"), tambah 1, lalu pad kembali ke 3 digit.
     * - Jika tabel masih kosong, mulai dari PRD001.
     *
     * Contoh: PRD010 → angka 10 → +1 → 11 → pad → PRD011
     */
    public static function generateNextId(): string
    {
        // Ambil ID terakhir (tertinggi secara leksikal) di tabel
        $lastId = static::orderBy('id', 'desc')->value('id');

        if ($lastId === null) {
            // Tabel masih kosong, mulai dari PRD001
            return 'PRD001';
        }

        // Ekstrak angka dari ID: "PRD010" → "010" → (int) 10
        $numericPart = (int) substr($lastId, 3);

        // Tambah 1 lalu format ulang dengan padding 3 digit
        return 'PRD' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
    }
}

