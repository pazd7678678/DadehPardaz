<?php

use Illuminate\Support\Facades\Route;
use Pzamani\Base\app\Http\Controllers\HealthController;

Route::middleware(['api', 'apiheader'])->prefix('api/base')->group(function () {
    Route::get('health', HealthController::class);
});
