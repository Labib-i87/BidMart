<?php

use App\Http\Controllers\BuyerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WebsiteController;

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
    return redirect(url('login'));
});

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('alreadyLoggedIn');
Route::get('/registration', [AuthController::class, 'registration'])->name('registration')->middleware('alreadyLoggedIn');
Route::post('/register-user', [AuthController::class, 'registerUser'])->name('register-user');
Route::post('/login-user', [AuthController::class, 'loginUser'])->name('login-user');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/back', [WebsiteController::class, 'back'])->name('back')->middleware('isLoggedIn');
Route::get('/homepage', [WebsiteController::class, 'homepage'])->name('homepage')->middleware('isLoggedIn');
Route::get('/dashboard', [WebsiteController::class, 'dashboard'])->name('dashboard')->middleware('isLoggedIn');
Route::get('/verification', [WebsiteController::class, 'verification'])->middleware('isLoggedIn');
Route::post('/verify-user', [WebsiteController::class, 'verifyUser'])->name('verify-user')->middleware('isLoggedIn');
Route::get('/payment', [WebsiteController::class, 'payment'])->middleware('isLoggedIn');
Route::get('/product-entry', [WebsiteController::class, 'productEntry'])->name('product-entry')->middleware('isLoggedIn');
Route::post('/product-upload', [WebsiteController::class, 'productUpload'])->name('product-upload')->middleware('isLoggedIn');
Route::get('/product-history', [WebsiteController::class, 'productHistory'])->name('product-history')->middleware('isLoggedIn');
Route::get('edit-product/{pid}', [WebsiteController::class, 'editProduct'])->middleware('isLoggedIn');
Route::put('update-product/{pid}', [WebsiteController::class, 'updateProduct'])->middleware('isLoggedIn');
Route::get('delete-product/{pid}', [WebsiteController::class, 'deleteProduct'])->middleware('isLoggedIn');

Route::get('bid/{pid}', [BuyerController::class, 'bid'])->name('bid')->middleware('isLoggedIn');
Route::get('entry-payment/{pid}', [BuyerController::class, 'entryPayment'])->name('entry-payment')->middleware('isLoggedIn');
Route::get('set-bid/{pid}', [BuyerController::class, 'setBid'])->name('set-bid')->middleware('isLoggedIn');

Route::get('sell-product/{pid}', [BuyerController::class, 'sellProduct'])->name('sell-product')->middleware('isLoggedIn');
Route::get('/cart', [BuyerController::class, 'cart'])->name('cart')->middleware('isLoggedIn');
Route::get('remove-product/{pid}', [BuyerController::class, 'removeProduct'])->name('remove-product')->middleware('isLoggedIn');

Route::get('payment-gateway/{pid}', [BuyerController::class, 'paymentGateway'])->name('payment-gateway')->middleware('isLoggedIn');
Route::get('buy-product/{pid}', [BuyerController::class, 'buyProduct'])->name('buy-product')->middleware('isLoggedIn');
Route::get('/purchase-history', [BuyerController::class, 'purchaseHistory'])->name('purchase-history')->middleware('isLoggedIn');
Route::get('view-product/{pid}', [BuyerController::class, 'viewProduct'])->name('view-product')->middleware('isLoggedIn');
Route::get('buyout-payment/{pid}', [BuyerController::class, 'buyoutPayment'])->name('buyout-payment')->middleware('isLoggedIn');
Route::get('buyout/{pid}', [BuyerController::class, 'buyout'])->name('buyout')->middleware('isLoggedIn');