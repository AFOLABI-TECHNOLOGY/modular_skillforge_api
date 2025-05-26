<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentification\Http\Controllers\AuthentificationController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('authentifications', AuthentificationController::class)->names('authentification');
});
