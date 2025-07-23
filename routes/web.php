<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('documents', DocumentController::class);
    Route::resource('comments', CommentController::class);
    Route::get('/published', [DocumentController::class, 'published'])->name('documents.published');
    Route::get('/rejected', [DocumentController::class, 'rejected'])->name('documents.rejected');

    Route::get('/trashed', [DocumentController::class, 'trashed'])->name('documents.trashed');
    Route::post('/documents/{document}/restore', [DocumentController::class, 'restore'])->name('documents.restore');
    Route::delete('/documents/{document}/force-delete', [DocumentController::class, 'forceDelete'])->name('documents.force-delete');

    Route::get('/preview/{document}', [DocumentController::class, 'preview'])->name('documents.preview');

    Route::post('/documents/rollback/{document}', [DocumentController::class, 'rollback'])->name('documents.rollback');
    Route::get('/documents/{document}/versions', [DocumentController::class, 'allVersions'])->name('documents.versions');
    Route::post('/documents/{document}/reactivate', [DocumentController::class, 'reactivate'])->name('documents.reactivate');
    Route::get('/documents/{document}/previous', [DocumentController::class, 'previous'])->name('documents.previous');
    Route::get('/documents/{document}/previous/{version}', [DocumentController::class, 'showPrevious'])->name('documents.show.previous');

    Route::post('/documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');

    Route::post('/documents/{document}/enable-approval', [DocumentController::class, 'enableApproval'])->name('documents.enableApproval');
    Route::post('/documents/{document}/disable-approval', [DocumentController::class, 'disableApproval'])->name('documents.disableApproval');

});

require __DIR__.'/auth.php';
