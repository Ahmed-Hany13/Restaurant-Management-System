<?php

use App\Http\Controllers\CreateStaffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\MenuSectionController;
use App\Http\Controllers\MenuSubcategoryController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth', 'admin', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [DashboardController::class, 'myAccount'])->name('dashboard.my-account');
    Route::put('/profile', [DashboardController::class, 'updateMyAccount'])->name('UpdateMyAccount');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders.index')->middleware('waiter');
    Route::get('/billing', [DashboardController::class, 'billing'])->name('billing_page')->middleware('cashier');
    Route::get('/kitchen', [DashboardController::class, 'kitchen'])->name('kitchen')->middleware('kitchen');
    Route::post('/kitchen/orders/{order_number}/start-preparing', [DashboardController::class, 'startPreparing'])->name('kitchen.start-preparing')->middleware('kitchen');
    Route::post('/kitchen/orders/{order_number}/mark-ready', [DashboardController::class, 'markReady'])->name('kitchen.mark-ready')->middleware('kitchen');
    Route::get('/create-staff', [DashboardController::class, 'CreateStaff'])->name('create-staff')->middleware('admin');
    Route::get('/menu-view', [MenuController::class, 'index'])->name('menu');



    Route::resource('/admin/section', MenuSectionController::class)->middleware('admin')->names('section');
    Route::resource('/admin/category', MenuCategoryController::class)->middleware('admin')->names('category');
    Route::resource('/admin/subcategory', MenuSubcategoryController::class)->middleware('admin')->names('subcategory');
    Route::resource('/admin/item', MenuItemController::class)->middleware('admin')->names('item');
    Route::resource('/admin/table',TableController::class)->middleware('admin')->names('table');
    Route::resource('/offers', OfferController::class)->names('offers');
    Route::resource('/orders', OrderController::class)->names('orders');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::patch('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::post('/reservations/{reservation}/arrive', [ReservationController::class, 'arrive'])->name('reservations.arrive');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('/reservations/{reservation}/no-show', [ReservationController::class, 'markNoShow'])->name('reservations.no_show');
    Route::post('/reservations', [OrderController::class, 'storeReservation'])->name('reservations.store');




    Route::get('/api/categories', [MenuCategoryController::class, 'getBySection']);
    Route::get('/api/subcategories', [MenuSubcategoryController::class, 'getByCategory']);
    Route::get('/api/sections', [MenuSectionController::class, 'getActive'])->name('menu.sections.active');
    Route::get('/api/items', [MenuItemController::class, 'getBySubcategory']);


    Route::post('/create-staff', [CreateStaffController::class, 'store'])->middleware(['admin'])->name('create_staff');



    Route::get('/table/{tableNumber}/{token}', [TableController::class, 'scanQrCode'])
    ->name('QrCode');
    Route::get('/table/{id}/qr-code', [TableController::class, 'generateQrCode'])->name('generateQrCode');

});

require __DIR__ . '/auth.php';
