<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('tasks', TaskController::class);
    Route::resource('projects', \App\Http\Controllers\ProjectController::class);
    Route::post('/projects/{project}/invite', [\App\Http\Controllers\ProjectController::class, 'invite'])->name('projects.invite');
    Route::delete('/projects/{project}/members/{user}', [\App\Http\Controllers\ProjectController::class, 'removeMember'])->name('projects.members.remove');
    Route::post('/projects/{project}/leave', [\App\Http\Controllers\ProjectController::class, 'leave'])->name('projects.leave');
    
    Route::get('/invitations/{token}', [\App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\AdminController::class, 'login'])->name('login');
    Route::post('/login', [App\Http\Controllers\AdminController::class, 'store'])->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';
