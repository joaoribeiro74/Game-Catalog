<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Games\GameController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::group(['prefix' => 'auth'], function () {
    Route::get('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


Route::group(['prefix' => 'games', 'middleware' => 'auth'], function () {
    Route::get('/', [GameController::class, 'index'])->name('games.index');
    Route::get('/create', [GameController::class, 'create'])->name('games.create');
    Route::get('/{id}', [GameController::class, 'detail'])->name('games.detail');
    Route::get('/{id}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::post('/{id}', [GameController::class, 'update'])->name('games.update');
});
