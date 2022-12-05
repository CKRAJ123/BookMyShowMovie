<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\Auth\AuthController;
  
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

    return view('welcome');
});
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 
Route::get('dashboard', [AuthController::class, 'dashboard']); 
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
//dd("chans");
//Route::get('/accept', [AuthController::class, 'accept'])->name('accept');
Route::get('book_movie/{id?}', [AuthController::class, 'book_movie'])->name('book_movie');
Route::get('seat_booking/{theater_id}', [AuthController::class, 'seat_booking'])->name('seat_booking');
Route::post('paynow/{theater_id}', [AuthController::class, 'paynow'])->name('paynow');
Route::get('pay', [AuthController::class, 'generateBookingPaymentToken'])->name('generateBookingPaymentToken');
Route::get('paynow/success/{theater_id}/{id}', [AuthController::class, 'MovieTicketSuccess'])->name('MovieTicketSuccess');
//Route::post('total_amount_pay', [AuthController::class, 'create'])->name('create');
//Route::get('/book-movie', [AuthController::class, 'book_movie'])->name('book_movie1');
//Route::get('payment_begin', [AuthController::class, 'payment_begin'])->name('payment_begin');