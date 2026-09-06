<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. 4 Kartu Statistik Dinamis dari DB
        $totalProducts = Inventory::count();
        $totalStock = (int) Inventory::sum('stock');
        $totalTransactions = Transaction::count();
        $totalRevenue = (float) Transaction::sum('total');

        // 2. 5 Transaksi Penjualan Terbaru
        $recentTransactions = Transaction::with('inventory')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // 3. 5 Produk Stok Menipis / Habis (Alert)
        $lowStockProducts = Inventory::orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // 4. Data Penjualan 7 Hari Terakhir untuk Grafik (Chart.js)
        $sevenDaysAgo = now()->subDays(6)->format('Y-m-d');
        $dailySales = Transaction::select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(total) as total_amount'),
                DB::raw('SUM(quantity) as total_qty')
            )
            ->whereDate('transaction_date', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        // Siapkan array 7 hari lengkap (bila ada hari tanpa transaksi, diisi 0)
        $chartLabels = [];
        $chartDataRevenue = [];
        $chartDataQty = [];

        for ($i = 6; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $labelStr = now()->subDays($i)->format('d M');
            
            $chartLabels[] = $labelStr;
            if (isset($dailySales[$dateStr])) {
                $chartDataRevenue[] = (float) $dailySales[$dateStr]->total_amount;
                $chartDataQty[] = (int) $dailySales[$dateStr]->total_qty;
            } else {
                $chartDataRevenue[] = 0;
                $chartDataQty[] = 0;
            }
        }

        return view('dashboard.index', [
            'totalProducts'      => $totalProducts,
            'totalStock'         => $totalStock,
            'totalTransactions'  => $totalTransactions,
            'totalRevenue'       => $totalRevenue,
            'recentTransactions' => $recentTransactions,
            'lowStockProducts'   => $lowStockProducts,
            'chartLabels'        => $chartLabels,
            'chartDataRevenue'   => $chartDataRevenue,
            'chartDataQty'       => $chartDataQty,
        ]);
    }
}
