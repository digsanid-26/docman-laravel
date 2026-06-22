<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\GmailOAuthController;
use App\Http\Controllers\Admin\DocumentReviewController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('documents.index');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::post('/documents/{document}/resubmit', [DocumentController::class, 'resubmit'])->name('documents.resubmit');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/documents', [DocumentReviewController::class, 'index'])->name('documents.index');
    Route::get('/documents/export', [ExportController::class, 'export'])->name('documents.export');
    Route::get('/documents/{document}', [DocumentReviewController::class, 'show'])->name('documents.show');
    Route::post('/documents/{document}/review', [DocumentReviewController::class, 'review'])->name('documents.review');
    Route::get('/documents/{document}/download', [DocumentReviewController::class, 'download'])->name('documents.download');

    Route::get('/config', [ConfigController::class, 'index'])->name('config.index');
    Route::patch('/config/account', [ConfigController::class, 'updateAccount'])->name('config.account');
    Route::patch('/config/templates', [ConfigController::class, 'updateTemplates'])->name('config.templates');
    Route::post('/config/test-email', [ConfigController::class, 'sendTestEmail'])->name('config.test-email');

    Route::get('/gmail/redirect', [GmailOAuthController::class, 'redirect'])->name('gmail.redirect');
    Route::get('/gmail/callback', [GmailOAuthController::class, 'callback'])->name('gmail.callback');
});

require __DIR__.'/auth.php';
