<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('inventory.index');
});

// Resource Route untuk Inventory CRUD
Route::resource('inventory', InventoryController::class);

// Resource Route untuk Transaction / Data Penjualan (kecuali edit, update, show)
Route::resource('sales', TransactionController::class)->except(['edit', 'update', 'show']);


