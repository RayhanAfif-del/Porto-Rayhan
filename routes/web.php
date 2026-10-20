<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

/*
|--------------------------------------------------------------------------
| Admin panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'store'])->middleware('throttle:5,1');
    });

    Route::middleware(['auth', 'admin'])->name('admin.')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::patch('projects/{project}/toggle-publish', [Admin\ProjectController::class, 'togglePublish'])->name('projects.toggle-publish');
        Route::resource('projects', Admin\ProjectController::class)->except('show');
        Route::resource('education', Admin\EducationController::class)->except('show');
        Route::resource('experiences', Admin\ExperienceController::class)->except('show');
        Route::resource('messages', Admin\MessageController::class)->only(['index', 'show', 'destroy']);
        Route::post('messages/{message}/reply', [Admin\MessageController::class, 'reply'])->name('messages.reply');

        Route::get('settings', [Admin\SiteSettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [Admin\SiteSettingController::class, 'update'])->name('settings.update');
        Route::post('settings/image', [Admin\SiteSettingController::class, 'uploadImage'])->name('settings.upload-image');
        Route::post('settings/gallery-image', [Admin\SiteSettingController::class, 'uploadGalleryImage'])->name('settings.upload-gallery-image');
    });
});
