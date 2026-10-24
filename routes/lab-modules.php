<?php

use App\Http\Controllers\Lab\LabModuleController;
use Illuminate\Support\Facades\Route;

Route::prefix('/lab/{project}/modules')->middleware('throttle:60,1')->group(function () {
    Route::get('/', [LabModuleController::class, 'index']);
    Route::put('/capabilities/{key}', [LabModuleController::class, 'capability']);
    Route::post('/', [LabModuleController::class, 'store']);
    Route::get('/proposals/{proposal}', [LabModuleController::class, 'proposal']);
    Route::post('/proposals/{proposal}/apply', [LabModuleController::class, 'applyProposal']);
    Route::post('/proposals/{proposal}/cancel', [LabModuleController::class, 'cancelProposal']);
    Route::post('/{module}/proposals', [LabModuleController::class, 'propose']);
    Route::patch('/{module}/state', [LabModuleController::class, 'state']);
    Route::get('/{module}/contacts', [LabModuleController::class, 'contacts']);
    Route::post('/{module}/embed', [LabModuleController::class, 'embed']);
    Route::patch('/{module}/contacts/{message}', [LabModuleController::class, 'contactState']);
    Route::get('/{module}/news', [LabModuleController::class, 'news']);
    Route::post('/{module}/news', [LabModuleController::class, 'storeNews']);
    Route::patch('/{module}/news/{article}', [LabModuleController::class, 'updateNews']);
    Route::get('/{module}/products', [LabModuleController::class, 'products']);
    Route::post('/{module}/products', [LabModuleController::class, 'storeProduct']);
    Route::patch('/{module}/products/{product}', [LabModuleController::class, 'updateProduct']);
    Route::get('/{module}/orders', [LabModuleController::class, 'orders']);
    Route::get('/{module}/payment-settings', [LabModuleController::class, 'paymentSettings']);
    Route::put('/{module}/payment-settings', [LabModuleController::class, 'savePaymentSettings']);
    Route::post('/{module}/payment-attempts/{attempt}/review', [LabModuleController::class, 'reviewPaymentAttempt']);
});
