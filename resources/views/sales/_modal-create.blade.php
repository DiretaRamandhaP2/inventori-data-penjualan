<!-- Modal Tambah Transaksi Penjualan -->
<div x-show="createModalOpen" x-cloak @keydown.escape.window="createModalOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <!-- Overlay Backdrop -->
    <div x-show="createModalOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
        @click="createModalOpen = false"></div>

    <!-- Modal Dialog -->
    <div class="flex min-h-full items-center justify-center p-4 text-center">
        <div x-show="createModalOpen"
            class="relative w-full max-w-md transform overflow-hidden rounded-xl bg-surface border border-border text-left shadow-xl transition-all">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-border px-6 py-4 bg-slate-50/50">
                <h3 class="text-base font-bold text-textPrimary">Tambah Transaksi Penjualan</h3>
                <button @click="createModalOpen = false" type="button"
                    class="text-textSecondary hover:text-textPrimary rounded-lg p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="submitCreateTransaction">
                <div class="p-6 space-y-4">
                    <!-- Global Error Banner (misal stok ditolak backend) -->
                    <div x-show="errorMessage"
                        class="p-3.5 rounded-lg bg-red-50 border border-danger/30 text-danger text-xs font-medium"
                        x-text="errorMessage"></div>

                    <!-- Pilih Produk -->
                    <div>
                        <label for="inventory_id"
                            class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-1">
                            Pilih Produk <span class="text-danger">*</span>
                        </label>
                        <select id="inventory_id" x-model="formData.inventory_id" @change="onProductSelect" required
                            class="w-full px-3.5 py-2 text-sm rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white"
                            :class="{ 'border-danger bg-danger/5 focus:ring-danger/20': errors.inventory_id }">
                            <option value="" disabled>-- Pilih Produk --</option>
                            <template x-for="item in inventories" :key="item.id">
                                <option :value="item.id"
                                    x-text="item.name + ' (Stok: ' + item.stock + ') - Rp ' + Number(item.price).toLocaleString('id-ID')">
                                </option>
                            </template>
                        </select>
                        <template x-if="errors.inventory_id">
                            <p class="mt-1 text-sm text-danger font-medium" x-text="errors.inventory_id[0]"></p>
                        </template>

                        <!-- Info Detail Stok Produk Terpilih -->
                        <div x-show="selectedProduct" class="mt-2 text-xs flex items-center justify-between bg-slate-50 p-2.5 rounded-md border border-slate-200">
                            <span class="text-textSecondary">Stok Tersedia:</span>
                            <span class="font-bold" :class="selectedProduct && selectedProduct.stock > 0 ? 'text-success' : 'text-danger'"
                                  x-text="selectedProduct ? selectedProduct.stock + ' unit' : ''"></span>
                        </div>
                    </div>
                    <!-- Jumlah Pembelian -->
                    <div>
                        <label for="quantity"
                            class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-1">
                            Jumlah Pembelian <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="quantity" x-model="formData.quantity" min="1" step="1" required
                            placeholder="Contoh: 2"
                            class="w-full px-3.5 py-2 text-sm rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white"
                            :class="{
                                'border-danger bg-danger/5 focus:ring-danger/20': errors.quantity || (isExceedingStock),
                                'border-warning focus:ring-warning/20': isNearStockLimit && !isExceedingStock
                            }">

                        <!-- Error dari Backend -->
                        <template x-if="errors.quantity">
                            <p class="mt-1 text-sm text-danger font-medium" x-text="errors.quantity[0]"></p>
                        </template>

                        <!-- Real-time Warning Frontend: Melebihi Stok -->
                        <template x-if="!errors.quantity && isExceedingStock">
                            <p class="mt-1 text-sm text-danger font-medium"
                               x-text="'Jumlah melebihi stok tersedia (maksimal ' + selectedProduct.stock + ').'"></p>
                        </template>

                        <!-- Real-time Warning Frontend: Mendekati Batas Stok -->
                        <template x-if="!errors.quantity && !isExceedingStock && isNearStockLimit">
                            <p class="mt-1 text-xs text-warning font-medium">
                                Warning: Jumlah pembelian akan menghabiskan seluruh sisa stok produk ini.
                            </p>
                        </template>
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div>
                        <label for="transaction_date"
                            class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-1">
                            Tanggal Transaksi <span class="text-danger">*</span>
                        </label>
                        <input type="date" id="transaction_date" x-model="formData.transaction_date"
                            :max="todayDate" required
                            class="w-full px-3.5 py-2 text-sm rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white"
                            :class="{ 'border-danger bg-danger/5 focus:ring-danger/20': errors.transaction_date }">
                        <template x-if="errors.transaction_date">
                            <p class="mt-1 text-sm text-danger font-medium" x-text="errors.transaction_date[0]"></p>
                        </template>
                    </div>

                    <!-- Ringkasan Total Penjualan -->
                    <div x-show="estimatedTotal > 0" class="p-3 bg-blue-50/60 border border-blue-200 rounded-lg flex items-center justify-between">
                        <span class="text-xs font-semibold text-blue-900">Perkiraan Total:</span>
                        <span class="text-sm font-bold text-primary" x-text="'Rp ' + estimatedTotal.toLocaleString('id-ID')"></span>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end space-x-3 border-t border-border px-6 py-4 bg-slate-50/50">
                    <button type="button" @click="createModalOpen = false"
                        class="px-4 py-2 text-sm font-medium text-textSecondary bg-white border border-border rounded-lg hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" :disabled="submitting || isExceedingStock"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-lg shadow-xs transition disabled:opacity-50 flex items-center space-x-2">
                        <span x-text="submitting ? 'Memproses...' : 'Simpan Transaksi'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
