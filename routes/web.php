<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailController;
use Illuminate\Support\Facades\Route;


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

Route::get('/', function () {
    return view('welcome');
});


/*
===================================================
                  ADMIN
===================================================
*/
// account 
Route::controller(AccountController::class)->group(function() {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'loginSubmit')->name('loginSubmit');
});

Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::controller(DashboardController::class)->name("dashboard.")->group(function() {
        Route::get('/', 'dashboard')->name('index');
    });

    // email 
    Route::controller(EmailController::class)->name("email.")->prefix('email')->group(function() {
        Route::get('/', 'email')->name('index');
        Route::get('/add', 'add')->name('add');
        Route::post('/add', 'addSubmit')->name('addSubmit');
        Route::get('/delete/{email}', 'delete')->name('delete');
    });
});