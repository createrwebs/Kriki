<?php

use App\Http\Controllers\Lab\LabFormController;
use App\Http\Controllers\Lab\LabFormSubmissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('/lab/{project}/forms')->middleware('throttle:60,1')->group(function () {
    Route::get('/', [LabFormController::class, 'index']);
    Route::post('/', [LabFormController::class, 'store']);
    Route::get('/{form}', [LabFormController::class, 'show']);
    Route::post('/{form}/versions', [LabFormController::class, 'saveVersion']);
    Route::patch('/{form}/state', [LabFormController::class, 'state']);
    Route::post('/{form}/proposals', [LabFormController::class, 'propose']);
    Route::post('/{form}/embed', [LabFormController::class, 'embed']);
    Route::get('/{form}/submissions', [LabFormSubmissionController::class, 'index']);
    Route::get('/{form}/submissions/{submission}', [LabFormSubmissionController::class, 'show']);
    Route::patch('/{form}/submissions/{submission}/status', [LabFormSubmissionController::class, 'update']);
});
