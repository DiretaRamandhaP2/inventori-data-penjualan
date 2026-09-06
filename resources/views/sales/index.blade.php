@extends('layouts.app')

@section('title', 'Daftar Transaksi Penjualan')
@section('header_title', 'Data Penjualan')

@section('content')
<div
    x-data="salesApp({
        inventories: {{ json_encode($inventories) }},
        routes: {
            store: '{{ route('sales.store') }}',
            index: '{{ route('sales.index') }}'
        }
    })"
    class="space-y-6"
>
    <!-- Dynamic Notification Banner -->
    <div
        x-show="flashMessage.text !== ''"
        class="rounded-lg p-4 shadow-xs border flex items-start gap-3"
        :class="{
            'bg-emerald-50 border-success/30 text-success': flashMessage.type === 'success',
            'bg-red-50 border-danger/30 text-danger': flashMessage.type === 'error'
        }"
        style="display: none;"
    >
        <div class="flex items-center space-x-3">
            <span class="min-w-0 break-words text-sm font-medium" x-text="flashMessage.text"></span>
        </div>
        <button @click="flashMessage.text = ''" aria-label="Tutup notifikasi" class="shrink-0 min-h-11 min-w-11 inline-flex items-center justify-center text-slate-400 hover:text-slate-600 rounded-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Toolbar: Search & Filter Tanggal & Button Tambah -->
    <div class="bg-surface p-4 md:p-6 rounded-xl border border-border shadow-xs">
        <form method="GET" action="{{ route('sales.index') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
                <!-- Search Nama Produk / TRX ID -->
                <div class="relative flex-1 max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-textSecondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari transaksi / produk..."
                        class="w-full pl-9 pr-8 py-2 text-sm rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-background"
                    >
                </div>

                <!-- Filter Tanggal Dari - Sampai -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full lg:w-auto">
                    <input
                        type="date"
                        name="date_from"
                        value="{{ $dateFrom }}"
                        class="w-full sm:w-auto py-2 px-3 text-xs md:text-sm rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-background"
                        title="Dari Tanggal"
                    >
                    <span class="text-xs text-textSecondary font-semibold">s/d</span>
                    <input
                        type="date"
                        name="date_to"
                        value="{{ $dateTo }}"
                        class="w-full sm:w-auto py-2 px-3 text-xs md:text-sm rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-background"
                        title="Sampai Tanggal"
                    >
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="min-h-11 px-3.5 py-2 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-900 rounded-lg transition">
                        Filter
                    </button>
                    @if($search || $dateFrom || $dateTo)
                        <a href="{{ route('sales.index') }}" class="min-h-11 inline-flex items-center px-3 py-2 text-xs font-semibold text-textSecondary hover:text-textPrimary bg-slate-100 rounded-lg transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            <button
                type="button"
                @click="openCreateModal"
                class="w-full lg:w-auto min-h-11 px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-lg shadow-xs transition flex items-center justify-center space-x-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Transaksi</span>
            </button>
        </form>
    </div>

    <!-- Table Container -->
    <div class="bg-surface rounded-xl border border-border shadow-xs overflow-hidden">
        <!-- Desktop View (hidden md:table) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-border text-xs font-semibold text-textSecondary uppercase tracking-wider">
                        <th class="py-3.5 px-6">ID Transaksi</th>
                        <th class="py-3.5 px-6">Tanggal</th>
                        <th class="py-3.5 px-6">Produk</th>
                        <th class="py-3.5 px-6 text-center">Jumlah</th>
                        <th class="py-3.5 px-6">Total Transaksi</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    @forelse($transactions as $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-mono font-medium text-xs text-textSecondary">{{ $item->id }}</td>
                            <td class="py-4 px-6 text-textSecondary text-xs">
                                {{ \Carbon\Carbon::parse($item->transaction_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-textPrimary block">{{ $item->inventory ? $item->inventory->name : 'Produk Dihapus' }}</span>
                                <span class="text-xs text-textSecondary">{{ $item->inventory ? $item->inventory->category : '-' }}</span>
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-textPrimary">
                                {{ number_format($item->quantity, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 font-bold text-primary">
                                Rp {{ number_format($item->total, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button
                                    @click="openDeleteModal('{{ $item->id }}', '{{ addslashes($item->inventory ? $item->inventory->name : 'Produk') }}', {{ $item->quantity }}, '{{ \Carbon\Carbon::parse($item->transaction_date)->format('d/m/Y') }}', {{ $item->total }})"
                                    class="px-3 py-1.5 text-xs font-medium text-danger hover:bg-red-50 border border-danger/30 rounded-md transition"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-textSecondary">
                                <p class="font-medium text-sm">Belum ada riwayat transaksi penjualan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View (md:hidden) -->
        <div class="md:hidden divide-y divide-border">
            @forelse($transactions as $item)
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-mono text-textSecondary font-semibold">{{ $item->id }}</span>
                        <span class="text-textSecondary">{{ \Carbon\Carbon::parse($item->transaction_date)->translatedFormat('d M Y') }}</span>
                    </div>

                    <div>
                        <h4 class="font-bold text-textPrimary text-base">{{ $item->inventory ? $item->inventory->name : 'Produk Dihapus' }}</h4>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs text-textSecondary">Jumlah: <strong class="text-textPrimary">{{ $item->quantity }} item</strong></span>
                            <span class="text-sm font-bold text-primary">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button
                            @click="openDeleteModal('{{ $item->id }}', '{{ addslashes($item->inventory ? $item->inventory->name : 'Produk') }}', {{ $item->quantity }}, '{{ \Carbon\Carbon::parse($item->transaction_date)->format('d/m/Y') }}', {{ $item->total }})"
                            class="px-3 py-1 text-xs font-medium text-danger border border-danger/30 rounded-md"
                        >
                            Hapus Transaksi
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-textSecondary text-sm">Belum ada riwayat transaksi penjualan.</div>
            @endforelse
        </div>

        <div class="px-6 py-4 border-t border-border bg-slate-50/50">
            {{ $transactions->links() }}
        </div>
    </div>

    @include('sales._modal-create')
    @include('sales._modal-delete')
</div>

@push('scripts')
<script>
    function salesApp(config) {
        return {
            inventories: config.inventories,
            routes: config.routes,

            createModalOpen: false,
            formData: {
                inventory_id: '',
                quantity: 1,
                transaction_date: new Date().toISOString().split('T')[0]
            },
            selectedProduct: null,
            errors: {},
            errorMessage: '',
            submitting: false,
            todayDate: new Date().toISOString().split('T')[0],

            deleteModalOpen: false,
            deleteTarget: { id: '', product_name: '', quantity: 0, date: '', total: 0 },
            deleting: false,
            deleteErrorMessage: '',

            flashMessage: { text: '', type: 'success' },

            get isExceedingStock() {
                if (!this.selectedProduct) return false;
                const qty = Number(this.formData.quantity);
                return qty > this.selectedProduct.stock;
            },

            get estimatedTotal() {
                if (!this.selectedProduct || !this.formData.quantity || this.formData.quantity <= 0) return 0;
                return Number(this.formData.quantity) * Number(this.selectedProduct.price);
            },

            showNotification(text, type = 'success') {
                this.flashMessage = { text, type };
                setTimeout(() => {
                    if (this.flashMessage.text === text) {
                        this.flashMessage.text = '';
                    }
                }, 4000);
            },

            onProductSelect() {
                this.selectedProduct = this.inventories.find(item => item.id === this.formData.inventory_id) || null;
            },

            openCreateModal() {
                this.formData = {
                    inventory_id: this.inventories.length > 0 ? this.inventories[0].id : '',
                    quantity: 1,
                    transaction_date: this.todayDate
                };
                this.onProductSelect();
                this.errors = {};
                this.errorMessage = '';
                this.createModalOpen = true;
            },

            openDeleteModal(id, product_name, quantity, date, total) {
                this.deleteTarget = { id, product_name, quantity, date, total };
                this.deleteErrorMessage = '';
                this.deleteModalOpen = true;
            },

            async submitCreateTransaction() {
                this.submitting = true;
                this.errors = {};
                this.errorMessage = '';

                try {
                    const response = await fetch(this.routes.store, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(this.formData)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422) {
                            if (data.errors) {
                                this.errors = data.errors;
                            } else {
                                this.errorMessage = data.message || 'Stok tidak mencukupi atau data tidak valid.';
                            }
                        } else {
                            this.errorMessage = data.message || 'Terjadi kesalahan pada server.';
                        }
                        this.submitting = false;
                        return;
                    }

                    this.createModalOpen = false;
                    this.submitting = false;
                    this.showNotification(data.message, 'success');

                    setTimeout(() => window.location.reload(), 600);

                } catch (err) {
                    this.errorMessage = 'Terjadi kesalahan jaringan atau koneksi server.';
                    this.submitting = false;
                }
            },

            async confirmDeleteTransaction() {
                this.deleting = true;
                this.deleteErrorMessage = '';

                const url = `${this.routes.store}/${this.deleteTarget.id}`;

                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        this.deleteErrorMessage = data.message || 'Gagal menghapus transaksi.';
                        this.deleting = false;
                        return;
                    }

                    this.deleteModalOpen = false;
                    this.deleting = false;
                    this.showNotification(data.message, 'success');

                    setTimeout(() => window.location.reload(), 600);

                } catch (err) {
                    this.deleteErrorMessage = 'Terjadi kesalahan jaringan saat menghapus transaksi.';
                    this.deleting = false;
                }
            }
        };
    }
</script>
@endpush
@endsection
