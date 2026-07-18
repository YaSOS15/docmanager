<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SuggestionController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [CategoryController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/{category}', [CategoryController::class, 'index'])->name('dashboard.category');

    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme');
    Route::patch('/profile/view-mode', [ProfileController::class, 'updateViewMode'])->name('profile.view-mode');
    Route::patch('/profile/username', [ProfileController::class, 'updateUsername'])->name('profile.username');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/suggestions', [SuggestionController::class, 'create'])->name('suggestions.create');
    Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');

    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
    Route::post('/trash/categories/{uuid}/restore', [TrashController::class, 'restoreCategory'])->name('trash.categories.restore');
    Route::post('/trash/documents/{uuid}/restore', [TrashController::class, 'restoreDocument'])->name('trash.documents.restore');
    Route::delete('/trash/categories/{uuid}', [TrashController::class, 'forceDeleteCategory'])->name('trash.categories.force-delete');
    Route::delete('/trash/documents/{uuid}', [TrashController::class, 'forceDeleteDocument'])->name('trash.documents.force-delete');

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}/quota', [\App\Http\Controllers\Admin\UserController::class, 'updateQuota'])->name('users.quota');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/suggestions', [\App\Http\Controllers\Admin\SuggestionController::class, 'index'])->name('suggestions.index');
});