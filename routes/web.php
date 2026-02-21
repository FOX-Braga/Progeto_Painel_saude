<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommunityController;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('dashboard');

    Route::get('/communities', [CommunityController::class, 'index'])->name('communities.index');
    Route::get('/communities/create', [CommunityController::class, 'create'])->name('communities.create');
    Route::post('/communities', [CommunityController::class, 'store'])->name('communities.store');
    Route::get('/communities/{community}/edit', [CommunityController::class, 'edit'])->name('communities.edit');
    Route::put('/communities/{community}', [CommunityController::class, 'update'])->name('communities.update');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');
});
