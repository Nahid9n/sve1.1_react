<?php

use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Landing Page Routes
|--------------------------------------------------------------------------
*/

Route::get('landing/theme/{slug}', [LandingPageController::class, 'homePage'])->name('landing-theme.home');
Route::post('place-order/landing', [LandingPageController::class, 'placeOrder'])->name('landing.place-order');

// AJAX Operations
Route::post('landing/add/cart', [LandingPageController::class, 'addToCart'])->name('landing.add-to-cart');
Route::post('landing/remove/cart', [LandingPageController::class, 'removeFromCart'])->name('landing.remove-from-cart');
Route::post('landing/update-cart-quantity', [LandingPageController::class, 'updateCartQuantity'])->name('landing.update-cart-quantity');
Route::post('landing/abandoned/cart', [LandingPageController::class, 'abandonedCart'])->name('landing.abandoned-cart');
Route::post('landing/get-attributes', [LandingPageController::class, 'getAttributes'])->name('landing.get.attributes');

// Confirmation Page
Route::get('order/confirmation/{order}', [LandingPageController::class, 'orderConfirmation'])->name('order.confirmation');

/*
|--------------------------------------------------------------------------
| Admin Landing Page Management Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'admin.auth'], function () {
    // Landing Pages
    Route::get('/admin/landing/pages', [LandingPageController::class, 'index'])->name('admin.landing.pages.index');
    Route::get('/admin/landing/pages/create', [LandingPageController::class, 'create'])->name('admin.landing.pages.create');
    Route::post('/admin/landing/pages/store', [LandingPageController::class, 'store'])->name('admin.landing.pages.store');
    Route::get('/admin/landing/pages/edit/{id}', [LandingPageController::class, 'edit'])->name('admin.landing.pages.edit');
    Route::put('/admin/landing/pages/update/{id}', [LandingPageController::class, 'update'])->name('admin.landing.pages.update');
    Route::get('/admin/landing/pages/delete/{id}', [LandingPageController::class, 'destroy'])->name('admin.landing.pages.delete');
    Route::get('/admin/landing/pages/customize/{slug}', [LandingPageController::class, 'customize'])->name('admin.landing.pages.customize');

    // Landing Category
    Route::get('/admin/landing/category', [LandingPageController::class, 'indexCategory'])->name('admin.landing.category.index');
    Route::post('/admin/landing/category/store', [LandingPageController::class, 'storeCategory'])->name('admin.landing.category.store');
    Route::post('/admin/landing/category/update', [LandingPageController::class, 'updateCategory'])->name('admin.landing.category.update');
    Route::get('/admin/landing/category/delete/{id}', [LandingPageController::class, 'destroyCategory'])->name('admin.landing.category.delete');

    // Landing Theme
    Route::get('/admin/landing/theme', [LandingPageController::class, 'indexTheme'])->name('admin.landing.theme.index');
    Route::post('/admin/landing/theme/store', [LandingPageController::class, 'storeTheme'])->name('admin.landing.theme.store');
    Route::post('/admin/landing/theme/update', [LandingPageController::class, 'updateTheme'])->name('admin.landing.theme.update');
    Route::get('/admin/landing/theme/delete/{id}', [LandingPageController::class, 'destroyTheme'])->name('admin.landing.theme.delete');
    Route::get('/admin/landing/theme/{slug}/preview', [LandingPageController::class, 'preview'])->name('admin.landing.theme.preview');

    // Landing Save Data
    Route::post('/admin/landing-page/{id}/save', [LandingPageController::class, 'save'])->name('admin.landing.page.save');
    Route::post('/admin/landing-page/upload-image', [LandingPageController::class, 'uploadImage'])->name('admin.landing.page.upload.image');
});
