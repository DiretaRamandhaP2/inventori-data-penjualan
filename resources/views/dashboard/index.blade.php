@extends('layouts.app')

@section('title', 'Dashboard - Ringkasan Bisnis & Penjualan')
@section('header_title', 'Dashboard Overview')

@section('content')
    <div class="space-y-8">
        <!-- Header Greeting -->
        <div
            class="bg-surface p-6 rounded-xl border border-border shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-textPrimary">Selamat Datang di Sistem Inventaris & Penjualan</h2>
                <p class="text-sm text-textSecondary mt-1">Berikut adalah ringkasan statistik dan aktivitas bisnis terbaru
                    Anda.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('sales.index') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-lg shadow-xs transition inline-flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Catat Transaksi Penjualan</span>
                </a>
            </div>
        </div>

        <!-- 4 Stat Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Card 1: Total Produk -->
            <div class="bg-surface p-6 rounded-xl border border-border shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-textSecondary uppercase tracking-wider">Total Produk</p>
                    <h3 class="text-2xl font-bold text-textPrimary mt-1">{{ number_format($totalProducts, 0, ',', '.') }}
                    </h3>
                    <p class="text-xs text-textSecondary mt-1">Varian produk terdaftar</p>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>

            <!-- Card 2: Total Stok -->
            <div class="bg-surface p-6 rounded-xl border border-border shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-textSecondary uppercase tracking-wider">Total Stok</p>
                    <h3 class="text-2xl font-bold text-textPrimary mt-1">{{ number_format($totalStock, 0, ',', '.') }}</h3>
                    <p class="text-xs text-textSecondary mt-1">Item di gudang</p>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-50 text-success border border-success/20 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 8h14M5 8a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v1a2 2 0 01-2 2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
            </div>

            <!-- Card 3: Total Transaksi -->
            <div class="bg-surface p-6 rounded-xl border border-border shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-textSecondary uppercase tracking-wider">Total Transaksi</p>
                    <h3 class="text-2xl font-bold text-textPrimary mt-1">
                        {{ number_format($totalTransactions, 0, ',', '.') }}</h3>
                    <p class="text-xs text-textSecondary mt-1">Riwayat penjualan</p>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                    </svg>
                </div>
            </div>

            <!-- Card 4: Total Pendapatan -->
            <div class="bg-surface p-6 rounded-xl border border-border shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-textSecondary uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-textPrimary mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </h3>
                    <p class="text-xs text-textSecondary mt-1">Akumulasi penjualan</p>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-amber-50 text-warning border border-warning/20 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Section Grafik & Alert Stok -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart Penjualan 7 Hari Terakhir (2 Kolom di LG) -->
            <div class="lg:col-span-2 bg-surface p-6 rounded-xl border border-border shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-textPrimary text-base">Grafik Penjualan 7 Hari Terakhir</h3>
                        <p class="text-xs text-textSecondary">Ringkasan total nilai penjualan per hari</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700">7 Hari
                        Terakhir</span>
                </div>

                <div class="h-64 relative">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Warning / Alert Stok Menipis & Habis (1 Kolom di LG) -->
            <div class="bg-surface p-6 rounded-xl border border-border shadow-xs space-y-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-bold text-textPrimary text-base flex items-center space-x-2">
                            <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Alert Stok Produk</span>
                        </h3>
                        <a href="{{ route('inventory.index') }}"
                            class="text-xs font-medium text-primary hover:underline">Kelola</a>
                    </div>

                    <div class="divide-y divide-border">
                        @forelse($lowStockProducts as $prod)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-textPrimary">{{ $prod->name }}</h4>
                                    <p class="text-xs text-textSecondary">{{ $prod->category }}
                                        ({{ $prod->id }})
                                    </p>
                                </div>
                                <div>
                                    @if ($prod->stock >= 15)
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-success border border-success/20">
                                            Stok: {{ $prod->stock }}
                                        </span>
                                    @elseif($prod->stock >= 1)
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-warning border border-warning/20">
                                            Stok: {{ $prod->stock }}
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-danger border border-danger/20">
                                            Habis (0)
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-textSecondary py-4 text-center">Belum ada data produk.</p>
                        @endforelse
                    </div>
                </div>

                <div class="pt-3 border-t border-border">
                    <a href="{{ route('inventory.index') }}"
                        class="w-full py-2 px-4 rounded-lg border border-border text-center text-xs font-medium text-textPrimary hover:bg-slate-50 transition block">
                        Lihat Semua Inventaris →
                    </a>
                </div>
            </div>

        </div>
        <!-- Section Recent Transactions (5 Terbaru) -->
        <div class="bg-surface rounded-xl border border-border shadow-xs overflow-hidden">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-textPrimary text-base">5 Transaksi Terbaru</h3>
                    <p class="text-xs text-textSecondary">Transaksi penjualan produk yang baru
                        dicatat</p>
                </div>
                <a href="{{ route('sales.index') }}" class="text-xs font-medium text-primary hover:underline">
                    Lihat Semua Transaksi →
                </a>
            </div>

            <div class="overflow-x">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/75 border-b border-border text-xs font-semibold text-textSecondary uppercase tracking-wider">
                            <th class="py-3 px-6">ID Transaksi</th>
                            <th class="py-3 px-6">Tanggal</th>
                            <th class="py-3 px-6">Produk</th>
                            <th class="py-3 px-6 text-center">Jumlah (Qty)</th>
                            <th class="py-3 px-6 text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border text-sm">
                        @forelse($recentTransactions as $tx)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-mono font-semibold text-xs text-textSecondary">
                                    {{ $tx->id }}</td>
                                <td class="py-3.5 px-6 text-textSecondary text-xs">
                                    {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M Y') }}
                                </td>
                                <td class="py-3.5 px-6 font-semibold text-textPrimary">
                                    {{ $tx->inventory->name ?? 'Produk Dihapus' }}
                                    <span class="block text-xs font-normal text-textSecondary">Rp
                                        {{ number_format($tx->price_at_transaction ?? 0, 0, ',', '.') }}
                                        / unit</span>
                                </td>
                                <td class="py-3.5 px-6 text-center font-medium text-textPrimary">
                                    {{ $tx->quantity }}
                                </td>
                                <td class="py-3.5 px-6 text-right font-bold text-primary">
                                    Rp {{ number_format($tx->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-textSecondary text-sm">
                                    Belum ada riwayat transaksi penjualan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('salesChart').getContext('2d');
                const labels = {!! json_encode($chartLabels) !!};
                const dataRevenue = {!! json_encode($chartDataRevenue) !!};

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: dataRevenue,
                            borderColor: '#2563EB',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointRadius: 4,
                            pointBackgroundColor: '#2563EB'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let value = context.raw || 0;
                                        return 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(
                                            value);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                                        } else if (value >= 1000) {
                                            return 'Rp ' + (value / 1000).toFixed(0) + ' Rb';
                                        }
                                        return 'Rp ' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
