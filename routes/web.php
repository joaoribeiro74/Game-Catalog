<?php

use App\Http\Controllers\Games\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::group(['prefix' => 'games'], function () {
    Route::get('/', [GameController:: class, 'index'])->name('games.index');
    Route::get('/create', [GameController::class, 'create'])->name('games.create');
    Route::get('/{id}', [GameController::class, 'detail'])->name('games.detail');
    Route::get('/{id}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::post('/{id}', [GameController::class, 'update'])->name('games.update');
});
