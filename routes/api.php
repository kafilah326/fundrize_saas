<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::middleware(['api.auth'])->group(function () {
    // Endpoints for Maintenance Fee
    Route::get('/maintenance-fees', [ApiController::class, 'getMaintenanceFees']);
    Route::put('/maintenance-fees/{id}/status', [ApiController::class, 'updateMaintenanceFeeStatus']);
    
    // Endpoint for all combined transactions
    Route::get('/transactions', [ApiController::class, 'getTransactions']);
});
