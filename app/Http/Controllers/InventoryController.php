<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Inventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    /**
     * Tampilkan daftar produk inventaris dengan pencarian dan paginasi.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $inventories = Inventory::query()
            ->when($search !== '', function ($query) use ($search) {
                // Pencarian case-insensitive berdasarkan nama produk (LIKE %search%)
                $query->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('category', 'LIKE', '%' . $search . '%')
                      ->orWhere('id', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Kategori yang tersedia untuk dropdown form
        $categories = ['Fashion', 'Aksesoris', 'Lifestyle'];


        return view('inventory.index', compact('inventories', 'search', 'categories'));
    }

    /**
     * Simpan produk baru ke database.
     */
    public function store(StoreInventoryRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        // Generate ID otomatis (PRD001, PRD002, dst)
        $validated['id'] = Inventory::generateNextId();

        $inventory = Inventory::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Produk '{$inventory->name}' ({$inventory->id}) berhasil ditambahkan.",
                'data'    => $inventory,
            ], 201);
        }

        return redirect()
            ->route('inventory.index')
            ->with('success', "Produk '{$inventory->name}' ({$inventory->id}) berhasil ditambahkan.");
    }

    /**
     * Tampilkan detail produk (JSON untuk modal edit).
     */
    public function show(string $id): JsonResponse
    {
        $inventory = Inventory::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $inventory,
        ]);
    }

    /**
     * Update data produk yang sudah ada.
     */
    public function update(UpdateInventoryRequest $request, string $id): JsonResponse|RedirectResponse
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->update($request->validated());

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Produk '{$inventory->name}' ({$inventory->id}) berhasil diperbarui.",
                'data'    => $inventory,
            ]);
        }

        return redirect()
            ->route('inventory.index')
            ->with('success', "Produk '{$inventory->name}' ({$inventory->id}) berhasil diperbarui.");
    }

    /**
     * Hapus produk dari database.
     * TOLAK penghapusan jika produk masih memiliki relasi transaksi.
     */
    public function destroy(Request $request, string $id): JsonResponse|RedirectResponse
    {
        $inventory = Inventory::findOrFail($id);

        // Cek apakah produk memiliki relasi ke tabel transactions
        if ($inventory->transactions()->exists()) {
            $errorMessage = "Produk '{$inventory->name}' ({$inventory->id}) tidak dapat dihapus karena sudah memiliki riwayat transaksi penjualan.";

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 422);
            }

            return redirect()
                ->route('inventory.index')
                ->with('error', $errorMessage);
        }

        $productName = $inventory->name;
        $productId   = $inventory->id;
        $inventory->delete();

        $successMessage = "Produk '{$productName}' ({$productId}) berhasil dihapus.";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return redirect()
            ->route('inventory.index')
            ->with('success', $successMessage);
    }
}
