<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentification\Http\Controllers\AuthentificationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('authentifications', AuthentificationController::class)->names('authentification');
});
