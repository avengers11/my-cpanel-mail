<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AmazonReviewController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\AudibleController;
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
    Route::get('/logout', 'logoutSubmit')->name('logoutSubmit');
});

Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::controller(DashboardController::class)->name("dashboard.")->group(function() {
        Route::get('/', 'dashboard')->name('index');
        Route::get('/group-posts', 'groupPost')->name('groupPost');
    });

    // email 
    Route::controller(EmailController::class)->name("email.")->prefix('email')->group(function() {
        Route::get('/', 'email')->name('index');
        Route::get('/add', 'add')->name('add');
        Route::post('/add', 'addSubmit')->name('addSubmit');
        Route::post('/add-forward', 'addForwardSubmit')->name('addForwardSubmit');
        Route::get('/generate', 'generate')->name('generate');
        Route::get('/delete', 'delete')->name('delete');

        // gets  
        Route::any('/fetch-email', 'fetchEmails')->name('fetchEmails');
        Route::any('/mark-as-read/{emailId}', 'markAsRead')->name('markAsRead');
    });

    // // cards 
    // Route::controller(CardController::class)->name("card.")->prefix('card')->group(function() {
    //     Route::get('/', 'card')->name('index');

    //     Route::any('/openBrowser-card', 'openBrowserCard')->name('openBrowser');
    //     Route::any('/add-card', 'addCard')->name('add');
    //     Route::get('/remove-card', 'removeCard')->name('remove');
    //     Route::get('/remove-card-dynamic', 'removeCardDynamic')->name('removeDynamic');
    //     Route::get('/amazon-order', 'amazonOrder')->name('amazonOrder');
    //     Route::post('/amazon-order-submit', 'amazonOrderSubmit')->name('amazonOrderSubmit');

    //     Route::get('/test-listner', 'listinerTest');
    // });

    // cards 
    Route::controller(CardController::class)->name("card.")->prefix('card')->group(function() {
        Route::get('/', 'card')->name('index');

        Route::any('/openBrowser-card', 'openBrowserCard')->name('openBrowser');
        Route::any('/add-card', 'addCard')->name('add');
        Route::get('/remove-card', 'removeCard')->name('remove');
        Route::get('/remove-card-dynamic', 'removeCardDynamic')->name('removeDynamic');
        Route::get('/remove-card-clear', 'removeCardClear')->name('removeClear');
        Route::any('/get-cards', 'getCards')->name('getCards');

        // order
        Route::any('/amazon-order', 'amazonOrder')->name('amazonOrder');
        Route::post('/amazon-order-save', 'amazonOrderSave')->name('amazonOrderSave');
        Route::get('/amazon-order-submit', 'amazonOrderSubmit')->name('amazonOrderSubmit');
    });

    // amazon 
    Route::controller(AmazonReviewController::class)->name("review.")->prefix('review')->group(function() {
        Route::get('/', 'reviewAll')->name('all');
        Route::any('/add-review', 'addReview')->name('addReview');
        Route::get('/todays-task', 'todaysTask')->name('todaysTask');
        Route::get('/completed-task', 'reviewcompletedTask')->name('completedTask');
    });


    // Audible 
    Route::controller(AudibleController::class)->name("audible.")->prefix('audible')->group(function() {
        Route::any('/', 'orderView')->name('orderView');
        Route::post('/save', 'orderSave')->name('orderSave');
        Route::get('/process', 'orderProcess')->name('orderProcess');
    });
});
