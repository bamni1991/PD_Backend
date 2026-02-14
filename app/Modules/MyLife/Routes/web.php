<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Modules\MyLife\Controllers\FileManagerController;

Route::get('/', function () {
    return view('dashboard');
})->middleware('auth')->name('home');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// File Manager routes (migrated from existing include)
Route::prefix('file-manager')->middleware('auth')->group(function () {
    Route::get('/auth', [FileManagerController::class, 'auth'])->name('file-manager.auth');
    Route::get('/callback', [FileManagerController::class, 'callback'])->name('file-manager.callback');

    // Main interface
    Route::get('/{folderId?}', [FileManagerController::class, 'index'])
        ->where('folderId', '[a-zA-Z0-9\-_]+') // Optional: constrain folderId
        ->name('file-manager.index');
    // Note: The specific /folder/ route is technically redundant if the above pattern catches it, 
    // but we can keep distinct routes if desired. The above handles /file-manager and /file-manager/123
    Route::get('/folder/{folderId}', [FileManagerController::class, 'index'])->name('file-manager.folder');

    Route::post('/create-folder', [FileManagerController::class, 'createFolder'])->name('file-manager.create-folder');
    Route::post('/upload', [FileManagerController::class, 'uploadFile'])->name('file-manager.upload');
    Route::post('/rename', [FileManagerController::class, 'renameFile'])->name('file-manager.rename');
    Route::post('/delete', [FileManagerController::class, 'deleteFile'])->name('file-manager.delete');
});

// UI Test
Route::get('/ui-test', function () {
    return view('ui.content');
})->middleware('auth');
