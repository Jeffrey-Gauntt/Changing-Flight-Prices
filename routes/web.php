<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HistoryController;
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

// homepage
Route::get('/', [IndexController::class, 'show']);

// go to register form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// register process (storing a new user)
Route::post('/register', [UserController::class, 'store']);

// go to login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// login process
Route::post('/login', [UserController::class, 'authenticate']);

// user's personal history of searches
Route::get('/history', [HistoryController::class, 'show'])->middleware('auth');

// show search form
Route::get('/search', [SearchController::class, 'show'])->middleware('auth');

// search process
Route::post('/search', [SearchController::class, 'search'])->middleware('auth');

// logout
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

