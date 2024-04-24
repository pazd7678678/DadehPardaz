<?php

use Illuminate\Support\Facades\Route;
use Pzamani\Auth\app\Http\Controllers\CheckController;
use Pzamani\Auth\app\Http\Controllers\GetSessionsController;
use Pzamani\Auth\app\Http\Controllers\LoginController;
use Pzamani\Auth\app\Http\Controllers\RefreshController;

Route::middleware(['api', 'apiheader'])->prefix('api/auth')->group(function () {
    Route::get('check', CheckController::class)->middleware(['auth.check']);
    Route::post('login', LoginController::class);
    Route::patch('refresh', RefreshController::class)->middleware(['auth.check']);
    Route::get('sessions', GetSessionsController::class)->middleware(['auth.check']);
});
