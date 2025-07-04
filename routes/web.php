<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Games\GameController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Games\GameListController;
use App\Http\Controllers\Games\RatingController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ProfileSettingsController;
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
    Route::post('/rating', [RatingController::class, 'storeOrUpdate'])->name('games.rating.storeOrUpdate');
    Route::post('/myList/toggle', [GameController::class, 'toggleGame'])->name('games.toggleList');

    Route::prefix('myList')->name('games.myList.')->group(function () {
        Route::post('/', [GameListController::class, 'store'])->name('store');
        Route::post('/addItem', [GameListController::class, 'addItem'])->name('addItem');
        Route::put('/{id}', [GameListController::class, 'update'])->name('update');
        Route::delete('/{id}', [GameListController::class, 'destroy'])->name('destroy');
        Route::delete('/item/{item}', [GameController::class, 'removeItem'])->name('remove');
    });
});

// Route::group(['prefix' => 'games', 'middleware' => 'auth'], function () {
//     Route::get('/', [GameController::class, 'index'])->name('games.index');
//     Route::get('/myList', [GameController::class, 'myList'])->name('games.myList');
//     Route::get('/create', [GameController::class, 'create'])->name('games.create');
//     Route::get('/{id}', [GameController::class, 'detail'])->name('games.detail');
//     Route::get('/{id}/edit', [GameController::class, 'edit'])->name('games.edit');
//     Route::post('/{id}/update', [GameController::class, 'update'])->name('games.update');
//     Route::post('/games/{id}/rate', [GameController::class, 'rate'])->name('games.rate');
//     Route::post('/myList/toggle', [GameController::class, 'toggleGame'])->name('games.toggleList');
//     Route::delete('/myList/{item}', [GameController::class, 'removeItem'])->name('games.myList.remove');
//     Route::post('/myList', [GameListController::class, 'store'])->name('games.myList.store');
//     Route::post('/myList/addItem', [GameListController::class, 'addItem'])->name('games.myList.addItem');
//     Route::post('/rating', [RatingController::class, 'storeOrUpdate'])->name('games.rating.storeOrUpdate');
//     Route::put('/{id}', [GameListController::class, 'update'])->name('games.myList.update');
//     Route::delete('/{id}', [GameListController::class, 'destroy'])->name('games.myList.destroy');
// });

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
   Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index'); 
   Route::get('/profile/games', [ProfileController::class, 'games'])->name('profile.games');
   Route::get('/profile/lists', [ProfileController::class, 'lists'])->name('profile.lists');
   Route::get('/profile/lists/{list}', [ProfileController::class, 'show'])->name('profile.lists.show');
   Route::get('/profile/settings/edit', [ProfileSettingsController::class, 'edit'])->name('profile.settings.edit');
   Route::get('/profile/settings/password', [ProfileSettingsController::class, 'password'])->name('profile.settings.password');
});
