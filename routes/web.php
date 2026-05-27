<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PwaPushSubscriptionController;
use App\Http\Controllers\QuickLinksController;

use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

include '_utilities.php';

Route::get('manifest.webmanifest', function () {
    $appName = config('app.name', 'PletoxStarter');

    return response()->json([
        'name' => $appName,
        'short_name' => str($appName)->limit(12, '')->toString(),
        'description' => 'Installable Laravel Bootstrap starter kit.',
        'start_url' => route('home'),
        'scope' => url('/'),
        'display' => 'standalone',
        'background_color' => '#f8f9fa',
        'theme_color' => '#a6192e',
        'icons' => [
            [
                'src' => asset('pwa/icons/icon-192x192.png'),
                'sizes' => '192x192',
                'type' => 'image/png',
            ],
            [
                'src' => asset('pwa/icons/icon-512x512.png'),
                'sizes' => '512x512',
                'type' => 'image/png',
            ],
            [
                'src' => asset('pwa/icons/maskable-icon-192x192.png'),
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'maskable',
            ],
            [
                'src' => asset('pwa/icons/maskable-icon-512x512.png'),
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'maskable',
            ],
        ],
    ], 200, ['Content-Type' => 'application/manifest+json']);
})->name('pwa.manifest');

Route::redirect('/', 'home');
Route::view('install-app', 'install-app')->name('install-app');

Route::group(['middleware' => ['auth:web']], function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/home/counters', [HomeController::class, 'counters'])->name('home.counters');
    Route::post('/home/recent-categories', [HomeController::class, 'recentCategories'])->name('home.recent-categories');


    Route::controller(PwaPushSubscriptionController::class)->prefix('pwa/push')->name('pwa.push.')->group(function () {
        Route::get('public-key', 'publicKey')->name('public-key');
        Route::post('subscribe', 'store')->name('subscribe');
        Route::delete('subscribe', 'destroy')->name('unsubscribe');
        Route::post('test', 'test')->name('test');
    });

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
        Route::get('profile/permissions', [ProfileController::class, 'permissions'])->name('settings.profile.permissions');
    });

    /* EXAMPLE CODE CAN BE DELETED ONCE REFERRED */
    Route::get('ui-kit', function () {
        abort_unless(app()->isLocal(), 404);

        return view('uikit', ['page' => 'ui-kit']);
    })->name('ui-kit');

    Route::get('developer-docs/{page?}', function (?string $page = null) {
        abort_unless(app()->isLocal(), 404);

        $page = $page ?: 'index';
        $views = [
            'index' => 'developer-docs.index',
            'components' => 'developer-docs.components',
            'forms' => 'developer-docs.forms',
            'datatables' => 'developer-docs.datatables',
            'infinite-scroll' => 'developer-docs.infinite-scroll',
            'pwa' => 'developer-docs.pwa',
            'backend' => 'developer-docs.backend',
            'testing' => 'developer-docs.testing',
        ];

        abort_unless(isset($views[$page]), 404);

        return view($views[$page], ['page' => $page]);
    })->name('developer-docs');

    Route::get('tabs/profile', function () {
        abort_unless(app()->isLocal(), 404);

        return '<p>Profile From Ajax</p>';
    })->name('tabs.profile');

    Route::get('tabs/contact', function () {
        abort_unless(app()->isLocal(), 404);

        return '<p>Contact In Ajax</p>';
    })->name('tabs.contact');

});
