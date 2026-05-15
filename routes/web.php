<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;


Route::get('/', [MainController::class, 'welcome'])->name('welcome.index');

Route::get('/start', [MainController::class, 'start'])->name('start.index');

Route::get('/nonewvideo', [MainController::class, 'nonewvideo'])->name('nonewvideo.index');

Route::get('/badanswer', [MainController::class, 'badanswer'])->name('badanswer.index');

Route::get('/newconf', [MainController::class, 'newconf'])->name('newconf.show');

Route::get('/otladka', [MainController::class, 'otladka'])->name('otladka.show');

Route::get('/otladka2', [MainController::class, 'otladka2'])->name('otladka2.show');

// Route::get('/', function () {
//     return view('welcome');
// });
