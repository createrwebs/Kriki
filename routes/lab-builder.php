<?php

use App\Http\Controllers\Lab\WebsiteBuilderAiController;
use App\Http\Controllers\Lab\WebsiteBuilderController;
use App\Http\Controllers\Lab\WebsiteDraftController;
use App\Http\Controllers\Lab\WebsiteProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/lab/builder', WebsiteBuilderController::class)->middleware('throttle:120,1,builder-read')->name('lab.builder');
Route::get('/lab/builder/catalog', [WebsiteDraftController::class, 'catalog'])->middleware('throttle:120,1,builder-read')->name('lab.builder.catalog');
Route::post('/lab/builder/ai-edit', [WebsiteBuilderAiController::class, 'edit'])->middleware('throttle:30,1,builder-write')->name('lab.builder.ai-edit');
Route::post('/lab/builder/upload-image', [WebsiteBuilderAiController::class, 'uploadImage'])->middleware('throttle:30,1,builder-write')->name('lab.builder.upload-image');
Route::get('/lab/builder/projects', [WebsiteBuilderAiController::class, 'listProjects'])->middleware('throttle:120,1,builder-read')->name('lab.builder.projects.index');
Route::delete('/lab/builder/projects/{uuid}', [WebsiteBuilderAiController::class, 'destroyProject'])->whereUuid('uuid')->middleware('throttle:30,1,builder-write')->name('lab.builder.projects.destroy');
Route::get('/lab/builder/drafts', [WebsiteDraftController::class, 'index'])->middleware('throttle:120,1,builder-read')->name('lab.builder.drafts.index');
Route::post('/lab/builder/drafts', [WebsiteDraftController::class, 'store'])->middleware('throttle:30,1,builder-write')->name('lab.builder.drafts.store');
Route::get('/lab/builder/drafts/{uuid}', [WebsiteDraftController::class, 'show'])->whereUuid('uuid')->middleware('throttle:120,1,builder-read')->name('lab.builder.drafts.show');
Route::put('/lab/builder/drafts/{uuid}', [WebsiteDraftController::class, 'update'])->whereUuid('uuid')->middleware('throttle:30,1,builder-write')->name('lab.builder.drafts.update');
Route::delete('/lab/builder/drafts/{uuid}', [WebsiteBuilderAiController::class, 'destroyDraft'])->whereUuid('uuid')->middleware('throttle:30,1,builder-write')->name('lab.builder.drafts.destroy');
Route::post('/lab/builder/drafts/{uuid}/project', WebsiteProjectController::class)->whereUuid('uuid')->middleware('throttle:10,1,builder-project')->name('lab.builder.drafts.project');
