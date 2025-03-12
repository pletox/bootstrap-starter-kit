<?php

use Illuminate\Support\Facades\Route;

Route::prefix('utilities')->group(function () {
    Route::post('/richtext/upload', [\App\Http\Controllers\Utilities\RichTextEditorController::class, 'upload'])
        ->name('utilities.richtext.upload');

    Route::get('/richtext/mention', [\App\Http\Controllers\Utilities\RichTextEditorController::class, 'mention'])
        ->name('utilities.richtext.mention');
});
