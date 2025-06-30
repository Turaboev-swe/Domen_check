<?php
use App\Http\Controllers\DomainCheckController;

use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/check-domains', [DomainCheckController::class, 'checkDomains']);
});
