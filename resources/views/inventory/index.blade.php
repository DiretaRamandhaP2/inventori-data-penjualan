@extends('layouts.app')

@section('title', 'Daftar Inventaris Produk')
@section('header_title', 'Manajemen Inventaris')

@section('content')
<div
    x-data="inventoryApp({
        categories: {{ json_encode($categories) }},
        routes: {
            store: '{{ route('inventory.store') }}',
            index: '{{ route('inventory.index') }}'
        },
        oldInput: {{ json_encode(session()->getOldInput() ?? new stdClass()) }},
        serverErrors: {{ json_encode($errors->getBag('default')->toArray() ?? new stdClass()) }}
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

    <!-- Toolbar: Search & Add Button -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-surface p-4 md:p-6 rounded-xl border border-border shadow-xs">
        <form method="GET" action="{{ route('inventory.index') }}" class="relative flex-1 max-w-md">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-textSecondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari berdasarkan nama, ID, atau kategori..."
                    class="w-full pl-9 pr-10 py-2 text-sm rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-background"
                >
                @if($search)
                    <a href="{{ route('inventory.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-textSecondary hover:text-textPrimary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        <button
            @click="openCreateModal"
            class="w-full lg:w-auto min-h-11 px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-lg shadow-xs transition flex items-center justify-center space-x-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Produk</span>
        </button>
    </div>

    <!-- Main Table Container -->
    <div class="bg-surface rounded-xl border border-border shadow-xs overflow-hidden">
        <!-- Desktop Table View (hidden md:table) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-border text-xs font-semibold text-textSecondary uppercase tracking-wider">
                        <th class="py-3.5 px-6">ID Produk</th>
                        <th class="py-3.5 px-6">Nama Produk</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6">Harga Satuan</th>
                        <th class="py-3.5 px-6">Stok Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    @forelse($inventories as $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-mono font-medium text-xs text-textSecondary">{{ $item->id }}</td>
                            <td class="py-4 px-6 font-semibold text-textPrimary">{{ $item->name }}</td>
                            <td class="py-4 px-6 text-textSecondary">
                                <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-medium text-textPrimary">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-4 px-6">
                                @if($item->stock >= 15)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-success border border-success/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-success mr-1.5"></span>
                                        {{ $item->stock }} (Aman)
                                    </span>
                                @elseif($item->stock >= 1)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-warning border border-warning/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-warning mr-1.5"></span>
                                        {{ $item->stock }} (Menipis)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-danger border border-danger/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-danger mr-1.5"></span>
                                        0 (Habis)
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <button
                                    @click="openEditModal({{ json_encode($item) }})"
                                    class="px-3 py-1.5 text-xs font-medium text-primary hover:text-primary-dark hover:bg-blue-50 border border-primary/30 rounded-md transition"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="openDeleteModal('{{ $item->id }}', '{{ addslashes($item->name) }}')"
                                    class="px-3 py-1.5 text-xs font-medium text-danger hover:bg-red-50 border border-danger/30 rounded-md transition"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-textSecondary">
                                <p class="font-medium text-sm">Tidak ada data produk ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="md:hidden divide-y divide-border">
            @forelse($inventories as $item)
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-xs text-textSecondary font-semibold">{{ $item->id }}</span>
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">{{ $item->category }}</span>
                    </div>

                    <div>
                        <h4 class="font-bold text-textPrimary text-base">{{ $item->name }}</h4>
                        <p class="text-sm font-semibold text-primary mt-0.5">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <div>
                            @if($item->stock >= 15)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-success border border-success/20">
                                    Stok: {{ $item->stock }} (Aman)
                                </span>
                            @elseif($item->stock >= 1)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-warning border border-warning/20">
                                    Stok: {{ $item->stock }} (Menipis)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-danger border border-danger/20">
                                    Stok: 0 (Habis)
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2">
                            <button @click="openEditModal({{ json_encode($item) }})" class="px-3 py-1 text-xs font-medium text-primary border border-primary/30 rounded-md">Edit</button>
                            <button @click="openDeleteModal('{{ $item->id }}', '{{ addslashes($item->name) }}')" class="px-3 py-1 text-xs font-medium text-danger border border-danger/30 rounded-md">Hapus</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-textSecondary text-sm">Tidak ada data produk ditemukan.</div>
            @endforelse
        </div>

        <div class="px-6 py-4 border-t border-border bg-slate-50/50">
            {{ $inventories->links() }}
        </div>
    </div>

    @include('inventory._modal-form')
    @include('inventory._modal-delete')
</div>

@push('scripts')
<script>
    function inventoryApp(config) {
        return {
            categories: config.categories || [],
            routes: config.routes || {},

            formModalOpen: false,
            isEditMode: false,
            formData: { id: '', name: '', category: '', price: '', stock: 0 },
            errors: {},
            errorMessage: '',
            submitting: false,

            deleteModalOpen: false,
            deleteTarget: { id: '', name: '' },
            deleting: false,
            deleteErrorMessage: '',

            flashMessage: { text: '', type: 'success' },

            init() {
                // Auto-open modal jika terdapat validation errors dari HTTP redirect back (old input & session errors)
                if (config.serverErrors && Object.keys(config.serverErrors).length > 0) {
                    this.errors = config.serverErrors;
                    if (config.oldInput) {
                        this.formData = Object.assign({}, this.formData, config.oldInput);
                        if (config.oldInput.id) {
                            this.isEditMode = true;
                        }
                    }
                    this.formModalOpen = true;
                }
            },

            showNotification(text, type = 'success') {
                this.flashMessage = { text, type };
                setTimeout(() => {
                    if (this.flashMessage.text === text) {
                        this.flashMessage.text = '';
                    }
                }, 4000);
            },

            openCreateModal() {
                this.isEditMode = false;
                this.formData = { id: '', name: '', category: this.categories[0] || 'Fashion', price: '', stock: 0 };
                this.errors = {};
                this.errorMessage = '';
                this.formModalOpen = true;
            },

            openEditModal(item) {
                this.isEditMode = true;
                this.formData = {
                    id: item.id,
                    name: item.name,
                    category: item.category,
                    price: item.price,
                    stock: item.stock,
                };
                this.errors = {};
                this.errorMessage = '';
                this.formModalOpen = true;
            },

            openDeleteModal(id, name) {
                this.deleteTarget = { id, name };
                this.deleteErrorMessage = '';
                this.deleteModalOpen = true;
            },

            async submitForm() {
                this.submitting = true;
                this.errors = {};
                this.errorMessage = '';

                const url = this.isEditMode
                    ? `${this.routes.store}/${this.formData.id}`
                    : this.routes.store;

                const method = this.isEditMode ? 'PUT' : 'POST';

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(this.formData)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            this.errors = data.errors;
                        } else {
                            this.errorMessage = data.message || 'Terjadi kesalahan saat menyimpan data.';
                        }
                        this.submitting = false;
                        return;
                    }

                    this.formModalOpen = false;
                    this.submitting = false;
                    this.showNotification(data.message, 'success');

                    setTimeout(() => window.location.reload(), 600);

                } catch (err) {
                    this.errorMessage = 'Terjadi kesalahan jaringan atau server.';
                    this.submitting = false;
                }
            },

            async confirmDelete() {
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
                        this.deleteErrorMessage = data.message || 'Gagal menghapus produk.';
                        this.deleting = false;
                        return;
                    }

                    this.deleteModalOpen = false;
                    this.deleting = false;
                    this.showNotification(data.message, 'success');

                    setTimeout(() => window.location.reload(), 600);

                } catch (err) {
                    this.deleteErrorMessage = 'Terjadi kesalahan jaringan atau server.';
                    this.deleting = false;
                }
            }
        };
    }

    document.addEventListener('alpine:init', () => {
        if (window.Alpine) {
            Alpine.data('inventoryApp', inventoryApp);
        }
    });
</script>
@endpush
@endsection
