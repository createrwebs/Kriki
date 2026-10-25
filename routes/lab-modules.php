<?php

use App\Http\Controllers\Lab\LabModuleController;
use Illuminate\Support\Facades\Route;

Route::post('/lab/{project}/operations', [\App\Http\Controllers\Lab\LabOperationController::class, 'start'])->middleware('throttle:60,1');
Route::patch('/lab/{project}/operations/{operation}', [\App\Http\Controllers\Lab\LabOperationController::class, 'update'])->middleware('throttle:120,1');

Route::prefix('/lab/{project}/modules')->middleware('throttle:60,1')->group(function () {
    Route::get('/build-context', [LabModuleController::class, 'buildContext']);
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
    Route::get('/{module}/embed', [LabModuleController::class, 'embedPlan']);
    Route::patch('/{module}/contacts/{message}', [LabModuleController::class, 'contactState']);
    Route::get('/{module}/news', [LabModuleController::class, 'news']);
    Route::post('/{module}/news', [LabModuleController::class, 'storeNews']);
    Route::patch('/{module}/news/{article}', [LabModuleController::class, 'updateNews']);
    Route::get('/{module}/products', [LabModuleController::class, 'products']);
    Route::post('/{module}/products', [LabModuleController::class, 'storeProduct']);
    Route::patch('/{module}/products/{product}', [LabModuleController::class, 'updateProduct']);
    Route::get('/{module}/orders', [LabModuleController::class, 'orders']);
    Route::post('/{module}/orders/{order}/revoke-access', [LabModuleController::class, 'revokeOrderAccess']);
    Route::get('/{module}/orders/{order}/payment-attempts', [LabModuleController::class, 'orderAttempts']);
    Route::get('/{module}/payment-settings', [LabModuleController::class, 'paymentSettings']);
    Route::put('/{module}/payment-settings', [LabModuleController::class, 'savePaymentSettings']);
    Route::post('/{module}/payment-attempts/{attempt}/review', [LabModuleController::class, 'reviewPaymentAttempt']);
    Route::get('/{module}/payment-attempts/{attempt}/image', [LabModuleController::class, 'paymentImage']);
    Route::get('/{module}/payment-attempts/{attempt}/legacy-image', [LabModuleController::class, 'legacyPaymentImage']);
    Route::post('/{module}/slip-uploads/reconcile', [LabModuleController::class, 'reconcileSlipUploads']);
});
