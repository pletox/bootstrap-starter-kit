<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuickLinksController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

include '_utilities.php';

Route::redirect('/', 'home');

Route::group(['middleware' => ['auth:web']], function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/home/counters', [HomeController::class, 'counters'])->name('home.counters');
    Route::post('/home/recent-categories', [HomeController::class, 'recentCategories'])->name('home.recent-categories');

    Route::post('quick-links/list', [QuickLinksController::class, 'index'])->name('quick-links.index');
    Route::post('quick-links', [QuickLinksController::class, 'storeOrUpdate'])->name('quick-links.storeOrUpdate');
    Route::get('quick-links/{quickLink}', [QuickLinksController::class, 'edit'])->name('quick-links.edit');
    Route::delete('quick-links/{quickLink}', [QuickLinksController::class, 'destroy'])->name('quick-links.delete');

    Route::get('categories', [CategoriesController::class, 'index'])->name('categories.index');
    Route::post('categories', [CategoriesController::class, 'storeOrUpdate'])->name('categories.storeOrUpdate');
    Route::post('categories/bulk-action', [CategoriesController::class, 'bulkAction'])->name('categories.bulk-action');
    Route::post('categories/export', [CategoriesController::class, 'export'])->name('categories.export');
    Route::get('categories/options', [CategoriesController::class, 'options'])->name('categories.options');
    Route::get('categories/{category}', [CategoriesController::class, 'edit'])->name('categories.edit');
    Route::delete('categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.delete');

    Route::group(['prefix' => 'settings'], function () {
        Route::get('profile', [ProfileController::class, 'profile'])->name('settings.profile');
        Route::post('profile/delete', [ProfileController::class, 'destroy'])->name('settings.profile.delete');
        Route::get('profile/password-update', [ProfileController::class, 'passwordUpdate'])->name('settings.profile.password-update');
        Route::get('profile/appearance', [ProfileController::class, 'appearance'])->name('settings.profile.appearance');
    });

    /* EXAMPLE CODE CAN BE DELETED ONCE REFERRED */
    Route::view('ui-kit', 'uikit')->name('ui-kit');

    Route::get('tabs/profile', function () {
        return '<p>Profile From Ajax</p>';
    })->name('tabs.profile');

    Route::get('tabs/contact', function () {
        return '<p>Contact In Ajax</p>';
    })->name('tabs.contact');

});
