<?php

use Illuminate\Support\Facades\Route;
use Pzamani\Payment\app\Http\Controllers\GetPaytypesController;
use Pzamani\Payment\app\Http\Controllers\PayController;

Route::middleware(['api', 'apiheader'])->prefix('api/payment')->group(function () {
    Route::get('paytypes', GetPaytypesController::class)->middleware(['auth.check']);
    Route::post('pay', PayController::class)->middleware(['auth.check']);
});
