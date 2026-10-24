<?php

use App\Http\Controllers\Api\LabPublicFormController;
use Illuminate\Support\Facades\Route;

Route::get('/lab-forms/{form}', [LabPublicFormController::class, 'show'])->middleware('throttle:120,1');
Route::post('/lab-forms/{form}/submissions', [LabPublicFormController::class, 'store'])->middleware('throttle:lab-form-submissions');
Route::post('/lab-widgets/{module}/contact', [\App\Http\Controllers\Api\LabPublicModuleController::class, 'contact'])->middleware('throttle:lab-widget-submissions');
Route::get('/lab-widgets/{module}/news', [\App\Http\Controllers\Api\LabPublicModuleController::class, 'news'])->middleware('throttle:120,1');
Route::get('/lab-widgets/{module}/storefront', [\App\Http\Controllers\Api\LabPublicModuleController::class, 'storefront'])->middleware('throttle:120,1');
Route::post('/lab-widgets/{module}/orders', [\App\Http\Controllers\Api\LabPublicModuleController::class, 'createOrder'])->middleware('throttle:lab-widget-submissions');
Route::get('/lab-widgets/{module}/orders/{order}', [\App\Http\Controllers\Api\LabPublicModuleController::class, 'showOrder'])->middleware('throttle:120,1');
Route::post('/lab-widgets/{module}/orders/{order}/slips', [\App\Http\Controllers\Api\LabPublicModuleController::class, 'submitOrderSlip'])->middleware('throttle:lab-widget-submissions');
