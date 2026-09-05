<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionService
{
    /**
     * Membuat transaksi baru dengan pengecekan stok real-time (pessimistic locking)
     * dan pengelolaan database transaction secara atomik.
     *
     * @param array{inventory_id: string, quantity: int, transaction_date: string} $data
     * @return array{success: bool, message: string, data?: Transaction}
     */
    public function createTransaction(array $data): array
    {
        try {
            return DB::transaction(function () use ($data) {
                /**
                 * CONCEPUAL NOTE - lockForUpdate():
                 * Menggunakan SELECT ... FOR UPDATE (Pessimistic Locking).
                 * Hal ini mengunci baris produk di basis data sehingga transaksi konkuren lain
                 * tidak dapat membaca/mengubah nilai stok produk yang sama sampai transaksi ini SELESAI.
                 * Ini mencegah rase condition di mana 2 pembeli membeli sisa stok secara bersamaan.
                 */
                $product = Inventory::where('id', $data['inventory_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    return [
                        'success' => false,
                        'message' => 'Produk tidak ditemukan di inventaris.',
                    ];
                }

                $requestedQuantity = (int) $data['quantity'];

                // ─── LOGIKA VALIDASI STOK (PSEUDOCODE MATCH) ─────────────────────────
                // IF jumlah_pembelian > stok_produk:
                //     tolak transaksi
                //     tampilkan pesan error
                //     stok TIDAK berubah
                if ($requestedQuantity > $product->stock) {
                    return [
                        'success' => false,
                        'message' => "Stok tidak mencukupi. Stok tersedia: {$product->stock}, jumlah diminta: {$requestedQuantity}.",
                    ];
                }

                // ELSE:
                // 1. Calculate Snapshot Total (quantity * harga_produk_saat_ini)
                $snapshotPrice = $product->price;
                $totalAmount = $requestedQuantity * $snapshotPrice;

                // 2. Generate ID TRX
                $transactionId = Transaction::generateNextId();

                // 3. Simpan transaksi
                $transaction = Transaction::create([
                    'id'               => $transactionId,
                    'inventory_id'     => $product->id,
                    'quantity'         => $requestedQuantity,
                    'total'            => $totalAmount,
                    'transaction_date' => $data['transaction_date'],
                ]);

                // 4. Potong Stok Produk (stok_produk -= jumlah_pembelian)
                $product->decrement('stock', $requestedQuantity);

                // Load relasi inventory untuk kebutuhan response JSON/tampilan
                $transaction->load('inventory');

                return [
                    'success' => true,
                    'message' => "Transaksi {$transaction->id} berhasil dibuat. Total: Rp " . number_format($totalAmount, 0, ',', '.'),
                    'data'    => $transaction,
                ];
            });
        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses transaksi: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Membatalkan / Menghapus transaksi dan mengembalikan (restock) jumlah barang ke inventaris.
     *
     * PENDEKATAN RESTOCK:
     * Stok produk dikembalikan saat transaksi dihapus agar jumlah stok di inventaris tetap akurat
     * dan mencerminkan kondisi fisik barang yang sebenarnya (misal transaksi dibatalkan/salah input).
     *
     * @param string $id
     * @return array{success: bool, message: string}
     */
    public function deleteTransaction(string $id): array
    {
        try {
            return DB::transaction(function () use ($id) {
                $transaction = Transaction::where('id', $id)
                    ->lockForUpdate()
                    ->first();

                if (!$transaction) {
                    return [
                        'success' => false,
                        'message' => 'Transaksi tidak ditemukan.',
                    ];
                }

                // Ambil produk dan kunci row untuk restock
                $product = Inventory::where('id', $transaction->inventory_id)
                    ->lockForUpdate()
                    ->first();

                $quantityToRestock = $transaction->quantity;
                $productName = $transaction->inventory ? $transaction->inventory->name : 'Produk';

                // Kembalikan stok ke inventaris
                if ($product) {
                    $product->increment('stock', $quantityToRestock);
                }

                // Hapus transaksi
                $transaction->delete();

                return [
                    'success' => true,
                    'message' => "Transaksi {$id} berhasil dihapus. Stok {$productName} telah dikembalikan sebanyak {$quantityToRestock} unit.",
                ];
            });
        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage(),
            ];
        }
    }
}
