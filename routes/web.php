<?php


use App\Livewire\Chatbot;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\TwoFactorAuthController;


use App\Http\Controllers\PasswordResetController;

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/', [AuthController::class, 'showLoginForm'])->name('home'); // or remove if not needed
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // <-- this is the fix
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/chatbot', [UserController::class, 'index'])->name('chatbot');

Route::get('/dashboard', [AdminController::class, 'viewDashboard'])->name('admin.dashboard');
Route::get('/knowledge-base', [AdminController::class, 'viewKB'])->name('admin.knowledge_base');
Route::get('/reports-analytics', [AdminController::class, 'viewReports'])->name('admin.reports_analytics');
Route::get('/logs', [AdminController::class, 'viewLogs'])->name('admin.logs');
Route::get('/user-management', [AdminController::class, 'viewUsers'])->name('admin.user_management');
Route::get('/charts', [AdminController::class, 'viewCharts'])->name('admin.charts');
Route::get('/forms', [AdminController::class, 'viewForms'])->name('admin.forms');


Route::prefix('admin/user_crud')->name('admin.')->group(function () {
    Route::get('/create', [UserManagementController::class, 'create'])->name('create');
    Route::post('/store', [UserManagementController::class, 'store'])->name('store');
    Route::get('/show/{id}', [UserManagementController::class, 'show'])->name('show');
    Route::get('/edit/{id}', [UserManagementController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [UserManagementController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [UserManagementController::class, 'destroy'])->name('destroy');
});






// Route::get('/chatbot', Chatbot::class)->name('livewire.chatbot');


Route::get('/verify-email/{token}', [EmailVerificationController::class, 'verifyEmail'])->name('verify.email');

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

Route::get('/2fa/verify', [TwoFactorAuthController::class, 'verifyForm'])->name('2fa.verify.form');
Route::post('/2fa/verify', [TwoFactorAuthController::class, 'verify'])->name('2fa.verify');
