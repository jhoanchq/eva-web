<?php

use App\Http\Controllers\Admin\EmailTestController;
use App\Http\Controllers\Admin\MailSettingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mail settings
    Route::get('/admin/mail', [MailSettingController::class, 'index'])->name('admin.mail.settings');
    Route::post('/admin/mail', [MailSettingController::class, 'update'])->name('admin.mail.settings.update');
    Route::get('/admin/mail/test', [MailSettingController::class, 'testForm'])->name('admin.mail.test');
    Route::post('/admin/mail/test/send', [EmailTestController::class, 'send'])->name('admin.mail.test.send');
});

require __DIR__.'/auth.php';
