<!-- Modal Konfirmasi Hapus Produk -->
<div
    x-show="deleteModalOpen"
    x-cloak
    @keydown.escape.window="deleteModalOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <!-- Overlay Backdrop -->
    <div
        x-show="deleteModalOpen"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
        @click="deleteModalOpen = false"
    ></div>

    <!-- Modal Dialog -->
    <div class="flex min-h-full items-center justify-center p-4 text-center">
        <div
            x-show="deleteModalOpen"
            class="relative w-full max-w-md transform overflow-hidden rounded-xl bg-surface border border-border text-left shadow-xl transition-all"
        >
            <div class="p-6">
                <!-- Icon Warning -->
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                <h3 class="text-base font-bold text-center text-textPrimary">Konfirmasi Hapus Produk</h3>

                <p class="mt-2 text-sm text-center text-textSecondary">
                    Apakah Anda yakin ingin menghapus produk <strong class="text-textPrimary" x-text="deleteTarget.name"></strong> (<span class="font-mono text-xs" x-text="deleteTarget.id"></span>)?
                </p>
                <p class="mt-1 text-xs text-center text-textSecondary">
                    Tindakan ini tidak dapat dibatalkan.
                </p>

                <!-- Alert Error jika ada constraint FK -->
                <div x-show="deleteErrorMessage" class="mt-4 p-3 rounded-lg bg-red-50 border border-danger/30 text-danger text-xs font-medium" x-text="deleteErrorMessage"></div>
            </div>

            <!-- Modal Actions -->
            <div class="flex items-center justify-end space-x-3 border-t border-border px-6 py-4 bg-slate-50/50">
                <button
                    type="button"
                    @click="deleteModalOpen = false"
                    class="px-4 py-2 text-sm font-medium text-textSecondary bg-white border border-border rounded-lg hover:bg-slate-50 transition"
                >
                    Batal
                </button>
                <button
                    type="button"
                    @click="confirmDelete"
                    :disabled="deleting"
                    class="px-4 py-2 text-sm font-medium text-white bg-danger hover:opacity-90 rounded-lg shadow-xs transition disabled:opacity-50 flex items-center space-x-2"
                >
                    <span x-text="deleting ? 'Menghapus...' : 'Hapus Produk'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
