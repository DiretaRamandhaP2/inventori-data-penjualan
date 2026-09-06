<!-- Modal Konfirmasi Hapus Transaksi -->
<div x-show="deleteModalOpen" x-cloak @keydown.escape.window="deleteModalOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <!-- Overlay Backdrop -->
    <div x-show="deleteModalOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
        @click="deleteModalOpen = false"></div>

    <!-- Modal Dialog -->
    <div class="flex min-h-full items-center justify-center p-4 text-center">
        <div x-show="deleteModalOpen"
            class="relative w-full max-w-md max-h-[calc(100vh-2rem)] overflow-y-auto transform rounded-xl bg-surface border border-border text-left shadow-xl transition-all">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-border px-6 py-4 bg-slate-50/50">
                <h3 class="text-base font-bold text-danger flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Konfirmasi Hapus Transaksi</span>
                </h3>
                <button @click="deleteModalOpen = false" type="button"
                    class="text-textSecondary hover:text-textPrimary rounded-lg p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6 space-y-4">
                <div x-show="deleteErrorMessage"
                    class="p-3 rounded-lg bg-red-50 border border-danger/30 text-danger text-xs font-medium"
                    x-text="deleteErrorMessage"></div>

                <p class="text-sm text-textPrimary">
                    Apakah Anda yakin ingin menghapus riwayat transaksi berikut?
                </p>

                <!-- Detail Transaksi Target -->
                <div class="bg-slate-50 p-4 rounded-lg border border-border space-y-2 text-xs">
                    <div class="flex flex-wrap items-start justify-between gap-x-3 gap-y-1">
                        <span class="text-textSecondary font-medium">ID Transaksi:</span>
                        <span class="font-mono font-bold text-textPrimary" x-text="deleteTarget.id"></span>
                    </div>
                    <div class="flex flex-wrap items-start justify-between gap-x-3 gap-y-1">
                        <span class="text-textSecondary font-medium">Nama Produk:</span>
                        <span class="font-semibold text-textPrimary" x-text="deleteTarget.product_name"></span>
                    </div>
                    <div class="flex flex-wrap items-start justify-between gap-x-3 gap-y-1">
                        <span class="text-textSecondary font-medium">Jumlah Pembelian:</span>
                        <span class="font-semibold text-textPrimary" x-text="deleteTarget.quantity + ' item'"></span>
                    </div>
                    <div class="flex flex-wrap items-start justify-between gap-x-3 gap-y-1">
                        <span class="text-textSecondary font-medium">Tanggal:</span>
                        <span class="text-textPrimary" x-text="deleteTarget.date"></span>
                    </div>
                    <div class="flex flex-wrap items-start justify-between gap-x-3 gap-y-1 border-t border-slate-200 pt-2">
                        <span class="text-textSecondary font-medium">Total Pembayaran:</span>
                        <span class="font-bold text-primary text-sm" x-text="'Rp ' + Number(deleteTarget.total).toLocaleString('id-ID')"></span>
                    </div>
                </div>

                <!-- Informational Restock Banner -->
                <div class="p-3 bg-emerald-50 border border-success/30 rounded-lg text-xs text-success flex items-start space-x-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>
                        <strong>Catatan Restock:</strong> Stok produk akan dikembalikan sebanyak <strong x-text="deleteTarget.quantity"></strong> unit ke inventaris secara otomatis.
                    </span>
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="flex items-center justify-end space-x-3 border-t border-border px-6 py-4 bg-slate-50/50">
                <button type="button" @click="deleteModalOpen = false"
                    class="px-4 py-2 text-sm font-medium text-textSecondary bg-white border border-border rounded-lg hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="button" @click="confirmDeleteTransaction" :disabled="deleting"
                    class="px-4 py-2 text-sm font-medium text-white bg-danger hover:bg-red-700 rounded-lg shadow-xs transition disabled:opacity-50 flex items-center space-x-2">
                    <span x-text="deleting ? 'Menghapus...' : 'Hapus Transaksi'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
