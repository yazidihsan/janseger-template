<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::prefix('dashboard')->middleware(['auth:sanctum','admin'])->group( function() {
    // Route::view('/dashboard', "dashboard")->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // Route::resource('users', UserController::class);
    // Route::get('/user', [UserController::class, "index"])->name('user');
    Route::get('/user', [ UserController::class, "index_view" ])->name('user');
    Route::view('/user/new', "pages.user.user-new")->name('user.new');
    Route::view('/user/edit/{userId}', "pages.user.user-edit")->name('user.edit');
});

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
