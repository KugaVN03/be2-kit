<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;


// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::get('/', [PageController::class, 'showHome'])->name('home');

Route::get('/contact', )->name('contact');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/news', function () {
    return view('news');
})->name('news');

Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('products/{id}', [ProductController::class, 'show'])->name('products.user.show');

Route::resource('categories', CategoryController::class);
// Route::resource('comments', CommentController::class);
Route::post('products/{productId}/comment', [CommentController::class, 'store'])->name('comments.store');

Route::prefix('admin')->group(function () {
    Route::get('/', [PageController::class, 'showDashboard'])->name('dashboard');
    Route::get('products/bin', [ProductController::class, 'bin'])->name('products.bin');
    Route::patch('products/bin/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/bin/forceDelete/{id}', [ProductController::class, 'forceDelete'])->name('products.forcedelete');
    Route::post('products/bin/bulkaction', [ProductController::class, 'bulkAction'])->name('products.bulkaction');
    Route::resource('products', ProductController::class);
});