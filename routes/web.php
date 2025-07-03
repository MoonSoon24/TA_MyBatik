<?php

use App\Http\Controllers\User\RiwayatPesananController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/home');

// login register routes
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// email verification
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect('/home');
    }

    if ($request->user()->markEmailAsVerified()) {
        event(new \Illuminate\Auth\Events\Verified($request->user()));
    }

    return redirect('/home')->with('verified', true);
})->middleware(['auth', 'signed'])->name('verification.verify');

// resend verification link
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminUserController::class, 'index'])->name('home');

    Route::get('/profile', function () {
        return view('admin.profile', [
            'user' => Auth::user()
        ]);
    })->name('profile');

    Route::post('/users/{user}/verify', [AdminUserController::class, 'verify'])->name('users.verify');

    Route::resource('users', AdminUserController::class);
    Route::resource('orders', AdminOrderController::class);
    
});

// profile routes
Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

// home routes
Route::get('/home', function(){
    return view('home');
})->name('home');

Route::resource('users', UserController::class);

Route::get('/create', function () {
    return view('create');
})->name('create');

Route::get('/ukuran', function () {
    return view('user.ukuran');
})->middleware(['auth', 'verified'])->name('ukuran');

Route::post('/order/store', [OrderController::class, 'store'])->middleware(['auth'])->name('order.store');

Route::get('/checkout', [OrderController::class, 'showCheckout'])->middleware(['auth'])->name('checkout.show');
Route::post('/checkout', [OrderController::class, 'storeOrder'])->middleware(['auth'])->name('checkout.store');

Route::get('/receipt/{order}', [OrderController::class, 'showReceipt'])->middleware(['auth'])->name('receipt');

Route::get('/history', [RiwayatPesananController::class, 'index'])->middleware(['auth', 'verified'])->name('history');

