<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Products\ProductStatusToggleController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

include '_utilities.php';

Route::redirect('/', 'home');


Route::group(['middleware' => ['auth:web']], function () {

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::get('categories', [CategoriesController::class, 'index'])->name('categories.index');
    Route::post('categories', [CategoriesController::class, 'storeOrUpdate'])->name('categories.storeOrUpdate');
    Route::get('categories/{category}', [CategoriesController::class, 'edit'])->name('categories.edit');
    Route::delete('categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.delete');

    Route::group(['prefix' => 'settings'], function () {
        Route::get('profile', [ProfileController::class, 'profile'])->name('settings.profile');
        Route::post('profile/delete', [ProfileController::class, 'destroy'])->name('settings.profile.delete');
        Route::get('profile/password-update', [ProfileController::class, 'passwordUpdate'])->name('settings.profile.password-update');
        Route::get('profile/appearance', [ProfileController::class, 'appearance'])->name('settings.profile.appearance');
    });


});
