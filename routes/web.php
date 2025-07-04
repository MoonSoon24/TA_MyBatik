<?php

// FILE FOR MODIFICATION: routes/web.php
// This is your updated routes file with gallery features integrated.

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Import all necessary controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\DesignController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\User\RiwayatPesananController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Public Routes ---

Route::redirect('/', '/home');

// home routes
Route::get('/home', function(){
    return view('home');
})->name('home');

// gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');


// Login/Register Routes
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- Authenticated User Routes ---
// All routes that require a user to be logged in and verified.
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Design & Order Process
    Route::get('/create', function () { return view('create'); })->name('create');
    Route::get('/ukuran', function () { return view('user.ukuran'); })->name('ukuran');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkout', [OrderController::class, 'storeOrder'])->name('checkout.store');
    Route::get('/receipt/{order}', [OrderController::class, 'showReceipt'])->name('receipt');
    Route::get('/history', [RiwayatPesananController::class, 'index'])->name('history');

    Route::patch('/designs/{design}', [DesignController::class, 'update'])->name('designs.update');
    Route::delete('/designs/{design}', [DesignController::class, 'destroy'])->name('designs.destroy');
    Route::post('/designs', [DesignController::class, 'store'])->name('designs.store');
    Route::post('/designs/{design}/comments', [DesignController::class, 'addComment'])->name('designs.comments.add');
    Route::post('/designs/{design}/like', [DesignController::class, 'toggleLike'])->name('designs.like');
});


// --- Admin Routes ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminUserController::class, 'index'])->name('home');
    Route::get('/profile', function () {
        return view('admin.profile', ['user' => Auth::user()]);
    })->name('profile');
    Route::post('/users/{user}/verify', [AdminUserController::class, 'verify'])->name('users.verify');
    Route::resource('users', AdminUserController::class);
    Route::resource('orders', AdminOrderController::class);
});


// --- Email Verification Routes ---
// These routes handle the logic for email verification notices and links.
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route('home');
    }
    if ($request->user()->markEmailAsVerified()) {
        event(new \Illuminate\Auth\Events\Verified($request->user()));
    }
    return redirect()->route('home')->with('verified', true);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Fallback resource route (if needed, but profile routes are more specific)
Route::resource('users', UserController::class)->middleware('auth');