<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'home']);

Route::get('/dashboard', [HomeController::class, 'login_home'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('edit_profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('edit_profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('edit_profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('admin/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'admin']);

Route::post('upload_product', [AdminController::class, 'upload_product'])->middleware(['auth', 'admin']);

Route::get('view_product', [AdminController::class, 'view_product'])->middleware(['auth', 'admin']);

Route::get('delete_product/{product_id}', [AdminController::class, 'delete_product'])->middleware(['auth', 'admin']);

Route::get('order_details/{order_id}', [HomeController::class, 'order_details'])->middleware(['auth', 'verified']);

Route::post('place_order/{order_id}', [HomeController::class, 'place_order'])->middleware(['auth', 'verified']);

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('view_order', [AdminController::class, 'view_order'])->middleware(['auth', 'admin']);

Route::patch('place_order/{order_id}/status', [AdminController::class, 'updateStatus'])->name('orders.updateStatus');

Route::get('/add_tukang', [AdminController::class, 'showTukangForm'])->name('add_tukang_form');

Route::post('/add_tukang', [AdminController::class, 'add_tukang'])->name('add_tukang');

Route::delete('/delete_tukang/{user_id}', [AdminController::class, 'delete_tukang'])->name('delete_tukang');

Route::patch('/update_status_tukang/{user_id}', [AdminController::class, 'update_status_tukang'])->name('update_status_tukang');

Route::patch('orders/{order_id}/assign-tukang', [AdminController::class, 'assignTukang'])->name('orders.assignTukang');

Route::get('/order_status/{status?}', [HomeController::class, 'order_status'])->name('order_status');

Route::patch('/orders/{order_id}/update-harga', [AdminController::class, 'updateHarga'])->name('orders.updateHarga');

Route::get('/payment_process/{order_id}', [HomeController::class, 'payment_process'])->name('payment_process');

Route::patch('update_estimasi_harga/{order_id}', [AdminController::class, 'updateEstimasiHarga'])->name('update_estimasi_harga');

// Rute Admin
Route::get('/penawaran', [AdminController::class, 'viewPenawaran'])->name('admin.penawaran');
Route::post('/penawaran/{order_id}/add-item', [AdminController::class, 'addPenawaranHargaItem'])->name('add_penawaran_harga_item');
Route::post('/penawaran/{order_id}/send', [AdminController::class, 'sendPenawaranHarga'])->name('send_penawaran_harga');

// Rute Customer
Route::patch('/order_status/{order_id}/approve', [HomeController::class, 'approvePenawaran'])->name('accept_penawaran');
Route::patch('/order_status/{order_id}/reject', [HomeController::class, 'rejectPenawaran'])->name('reject_penawaran');
Route::patch('/penawaran/{order_id}/update-item/{index}', [AdminController::class, 'updatePenawaranHargaItem'])->name('update_penawaran_harga_item');

Route::get('/generate-installments/{order_id}', [HomeController::class, 'generateInstallments'])->name('generate_installments');
Route::post('/upload-bukti-transfer/{order_id}/{installment_number}', [HomeController::class, 'uploadBuktiTransfer'])->name('upload_bukti_transfer');

Route::get('/admin/installments', [AdminController::class, 'viewInstallments'])->name('view_installments');
Route::patch('/admin/installments/approve/{installment_id}', [AdminController::class, 'approveInstallment'])->name('approve_installment');
Route::patch('/admin/installments/reject/{installment_id}', [AdminController::class, 'rejectInstallment'])->name('reject_installment');
