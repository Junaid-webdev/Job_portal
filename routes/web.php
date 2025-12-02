<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// First Page ///
Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest Routes

Route::middleware(['guest'])->group(function() {
Route::get('/account/register', [AccountController::class, 'register'])
    ->middleware('agecheck')
    ->name('account.register');

Route::post('/account/process', [AccountController::class, 'processRegistration'])->name('account.process');
Route::get('/account/login', [AccountController::class, 'login'])->name('account.login'); // <<< FIXED
Route::post('/account/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
});
// Auth Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::get('/account/logout', [AccountController::class, 'logout'])->name('account.logout');
    Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('account.update-profile');
    Route::post('/update-picture', [AccountController::class, 'updateProfilePic'])->name('account.update-picture');
    Route::get('account/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
    Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
    Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
    Route::get('/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
    Route::post('/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
    Route::post('/update-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');

});
