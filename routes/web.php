<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('inventory.index');
});

// Resource Route untuk Inventory CRUD
Route::resource('inventory', InventoryController::class);

