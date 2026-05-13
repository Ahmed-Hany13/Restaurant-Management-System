<?php

use App\Http\Controllers\CreateStaffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\MenuSectionController;
use App\Http\Controllers\MenuSubcategoryController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->middleware(['auth', 'admin', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [DashboardController::class, 'myAccount'])->name('dashboard.my-account');
    Route::put('/profile', [DashboardController::class, 'updateMyAccount'])->name('UpdateMyAccount');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders_page')->middleware('waiter');
    Route::get('/billing', [DashboardController::class, 'billing'])->name('billing_page')->middleware('cashier');
    Route::get('/kitchen', [DashboardController::class, 'kitchen'])->name('kitchen')->middleware('kitchen');
    Route::get('/create-staff', [DashboardController::class, 'CreateStaff'])->name('create-staff')->middleware('admin');
    Route::get('/menu-view', [DashboardController::class, 'menuView'])->name('menu');


    Route::resource('/admin/section', MenuSectionController::class)->middleware('admin')->names('section');
    Route::resource('/admin/category', MenuCategoryController::class)->middleware('admin')->names('category');
    Route::resource('/admin/subcategory', MenuSubcategoryController::class)->middleware('admin')->names('subcategory');
    Route::resource('/admin/item', MenuItemController::class)->middleware('admin')->names('item');


    Route::get('/api/categories', [MenuCategoryController::class, 'getBySection']);
    Route::get('/api/subcategories', [MenuSubcategoryController::class, 'getByCategory']);
    Route::get('/api/sections', [MenuSectionController::class, 'getActive']);
    Route::get('/api/items', [MenuItemController::class, 'getBySubcategory']);


    Route::post('/create-staff', [CreateStaffController::class, 'store'])->middleware(['admin'])->name('create_staff');



});

require __DIR__ . '/auth.php';
