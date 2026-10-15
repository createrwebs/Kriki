<?php

use App\Http\Controllers\Api\LabPublicFormController;
use Illuminate\Support\Facades\Route;

Route::get('/lab-forms/{form}', [LabPublicFormController::class, 'show'])->middleware('throttle:120,1');
Route::post('/lab-forms/{form}/submissions', [LabPublicFormController::class, 'store'])->middleware('throttle:lab-form-submissions');
