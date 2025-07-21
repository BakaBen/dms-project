<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
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

    Route::get('/trashed', [DocumentController::class, 'trashed'])->name('documents.trashed');
    Route::post('/documents/{document}/restore', [DocumentController::class, 'restore'])->name('documents.restore');
    Route::delete('/documents/{document}/force-delete', [DocumentController::class, 'forceDelete'])->name('documents.force-delete');

    Route::get('/preview/{document}', [DocumentController::class, 'preview'])->name('documents.preview');

    Route::post('/documents/rollback/{document}', [DocumentController::class, 'rollback'])->name('documents.rollback');
    Route::get('/documents/{document}/previous', [DocumentController::class, 'previous'])->name('documents.previous');
    Route::get('/documents/{document}/previous/{version}', [DocumentController::class, 'showPrevious'])->name('documents.show.previous');

});

require __DIR__.'/auth.php';
