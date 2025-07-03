<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Games\GameController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Games\GameListController;
use App\Http\Controllers\Games\RatingController;
use App\Http\Controllers\User\ProfileController;
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
    Route::get('/myList', [GameController::class, 'myList'])->name('games.myList');
    Route::get('/create', [GameController::class, 'create'])->name('games.create');
    Route::get('/{id}', [GameController::class, 'detail'])->name('games.detail');
    Route::get('/{id}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::post('/{id}/update', [GameController::class, 'update'])->name('games.update');
    Route::post('/games/{id}/rate', [GameController::class, 'rate'])->name('games.rate');
    Route::post('/myList/toggle', [GameController::class, 'toggleGame'])->name('games.toggleList');
    Route::delete('/myList/{item}', [GameController::class, 'removeItem'])->name('games.myList.remove');
    Route::post('/myList', [GameListController::class, 'store'])->name('games.myList.store');
    Route::post('/myList/addItem', [GameListController::class, 'addItem'])->name('games.myList.addItem');
    Route::post('/ratings', [RatingController::class, 'storeOrUpdate'])->name('ratings.storeOrUpdate');
});

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
   Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index'); 
});
