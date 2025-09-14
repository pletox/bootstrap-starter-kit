<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/editor/upload', function (Request $request) {
    $request->validate([
        'image' => 'required|image|max:2048', // 2MB limit
    ]);

    $path = $request->file('image')->store('uploads/editor', 'public');

    return response()->json([
        'url' => Storage::url($path)
    ]);
});

