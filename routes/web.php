<?php

use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', [PortfolioController::class, 'index']);


Auth::routes();

Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::get('/users', [UserController::class, 'index']);
Route::get('/admin', [HomeController::class, 'index'])->name('admin');
Route::get('logout', [HomeController::class, 'logout'])->name('logoutUser');