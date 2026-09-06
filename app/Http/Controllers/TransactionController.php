<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Inventory;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Tampilkan daftar transaksi penjualan (dengan pencarian, filter tanggal, dan paginasi).
     */
    public function index(Request $request): View
    {
        $search = trim($request->input('search', ''));
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = Transaction::with('inventory');

        // Search: nama produk (relasi) atau ID transaksi
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('inventory', function ($invQuery) use ($search) {
                      $invQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('category', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter Rentang Tanggal
        if (!empty($dateFrom)) {
            $query->whereDate('transaction_date', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('transaction_date', '<=', $dateTo);
        }

        // Urutkan default: transaksi terbaru
        $transactions = $query->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Ambil list seluruh produk untuk dropdown modal tambah transaksi
        $inventories = Inventory::orderBy('name', 'asc')
            ->get(['id', 'name', 'category', 'price', 'stock']);

        return view('sales.index', compact('transactions', 'inventories', 'search', 'dateFrom', 'dateTo'));
    }

    /**
     * Simpan transaksi penjualan baru.
     */
    public function store(StoreTransactionRequest $request): JsonResponse|RedirectResponse
    {
        $result = $this->transactionService->createTransaction($request->validated());

        if ($request->wantsJson() || $request->ajax()) {
            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data'    => $result['data'],
            ], 201);
        }

        if (!$result['success']) {
            return back()->withInput()->with('error', $result['message']);
        }

        return redirect()->route('sales.index')->with('success', $result['message']);
    }

    /**
     * Hapus transaksi penjualan (dan kembalikan stok produk).
     */
    public function destroy(string $id, Request $request): JsonResponse|RedirectResponse
    {
        $result = $this->transactionService->deleteTransaction($id);

        if ($request->wantsJson() || $request->ajax()) {
            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
            ], 200);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('sales.index')->with('success', $result['message']);
    }
}
