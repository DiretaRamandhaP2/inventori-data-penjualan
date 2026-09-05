<!-- Modal Tambah / Edit Produk -->
<div x-show="formModalOpen" x-cloak @keydown.escape.window="formModalOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <!-- Overlay Backdrop -->
    <div x-show="formModalOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
        @click="formModalOpen = false"></div>

    <!-- Modal Dialog -->
    <div class="flex min-h-full items-center justify-center p-4 text-center">
        <div x-show="formModalOpen"
            class="relative w-full max-w-md transform overflow-hidden rounded-xl bg-surface border border-border text-left shadow-xl transition-all">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-border px-6 py-4 bg-slate-50/50">
                <h3 class="text-base font-bold text-textPrimary"
                    x-text="isEditMode ? 'Edit Produk (' + formData.id + ')' : 'Tambah Produk Baru'"></h3>
                <button @click="formModalOpen = false" type="button"
                    class="text-textSecondary hover:text-textPrimary rounded-lg p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="submitForm">
                <div class="p-6 space-y-4">
                    <div x-show="errorMessage"
                        class="p-3 rounded-lg bg-red-50 border border-danger/30 text-danger text-xs font-medium"
                        x-text="errorMessage"></div>

                    <!-- Nama Produk -->
                    <div>
                        <label for="name"
                            class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-1">
                            Nama Produk <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" x-model="formData.name" required
                            placeholder="Contoh: Kaos Oversize Basic"
                            class="w-full px-3.5 py-2 text-sm rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white"
                            :class="{ 'border-danger focus:ring-danger/20': errors.name }">
                        <template x-if="errors.name">
                            <p class="mt-1 text-xs text-danger font-medium" x-text="errors.name[0]"></p>
                        </template>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category"
                            class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-1">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <select id="category" x-model="formData.category" required
                            class="w-full px-3.5 py-2 text-sm rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white"
                            :class="{ 'border-danger focus:ring-danger/20': errors.category }">
                            <option value="" disabled>-- Pilih Kategori --</option>
                            <template x-for="cat in categories" :key="cat">
                                <option :value="cat" x-text="cat" :selected="formData.category === cat"></option>
                            </template>
                        </select>
                        <template x-if="errors.category">
                            <p class="mt-1 text-xs text-danger font-medium" x-text="errors.category[0]"></p>
                        </template>
                    </div>

                    <!-- Harga & Stok Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Harga Satuan -->
                        <div>
                            <label for="price"
                                class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-1">
                                Harga Satuan <span class="text-danger">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-semibold text-textSecondary">Rp</span>
                                <input type="number" id="price" x-model="formData.price" min="0"
                                    step="500"
                                    required placeholder="75000"
                                    class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white"
                                    :class="{ 'border-danger focus:ring-danger/20': errors.price }">
                            </div>
                            <template x-if="errors.price">
                                <p class="mt-1 text-xs text-danger font-medium" x-text="errors.price[0]"></p>
                            </template>
                        </div>

                        <!-- Stok -->
                        <div>
                            <label for="stock"
                                class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-1">
                                Stok <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="stock" x-model="formData.stock" min="0"
                                required placeholder="10"
                                class="w-full px-3.5 py-2 text-sm rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white"
                                :class="{ 'border-danger focus:ring-danger/20': errors.stock }">
                            <template x-if="errors.stock">
                                <p class="mt-1 text-xs text-danger font-medium" x-text="errors.stock[0]"></p>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end space-x-3 border-t border-border px-6 py-4 bg-slate-50/50">
                    <button type="button" @click="formModalOpen = false"
                        class="px-4 py-2 text-sm font-medium text-textSecondary bg-white border border-border rounded-lg hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" :disabled="submitting"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-lg shadow-xs transition disabled:opacity-50 flex items-center space-x-2">
                        <span x-text="submitting ? 'Menyimpan...' : 'Simpan Produk'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
