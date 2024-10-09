<?php

use App\Http\Controllers\Auth\AuthSocialiteProvider;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/{provider}/redirect',[AuthSocialiteProvider::class,'redirect'])->name('redirect');
Route::get('auth/{provider}/callback',[AuthSocialiteProvider::class,'callback'])->name('callback');

Route::post('store-image',[UserController::class,'store']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
