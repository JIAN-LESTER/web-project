<?php


use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\KBController;
use App\Livewire\Chatbot;
use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\TwoFactorAuthController;


use App\Http\Controllers\PasswordResetController;

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// registration success page
Route::get('/registration/success', function () {
    return view('auth.registration-success');
})->name('registration.success');


Route::get('/password-reset/success', function () {
    return view('auth.reset_password_success');
})->name('reset-password.success');


Route::get('/', [AuthController::class, 'showLoginForm'])->name('home'); // or remove if not needed
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // <-- this is the fix
Route::post('/login', [AuthController::class, 'login']);

Route::get('/2fa/resend', 'Auth\YourAuthController@resendTwoFactorCode')->name('2fa.resend');

Route::get('/chatbot', [UserController::class, 'index'])->name('chatbot');


Route::post('/chatbot/message', [ChatbotController::class, 'handleChat'])->name('chatbot.handle');
Route::get('/chat/conversation/{conversationID}', [ChatbotController::class, 'showConversation'])->name('chat.conversation');
Route::get('/chatbot/new', [ChatbotController::class, 'newChat'])->name('chat.new');

Route::get('/chat/conversation/{conversationID}/messages', [ChatbotController::class, 'fetchMessages']);



Route::get('/2fa', function () {
    return view('auth.two-factor');
})->name('dashboard');

Route::get('/reset', function () {
    return view('auth.reset-password');
})->name('dashboard');

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});







Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/dashboard', [AdminController::class, 'viewDashboard', ])->name('admin.dashboard');
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


Route::prefix('admin/kb')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'viewKB'])->name('admin.knowledge_base');
    Route::get('/upload', [KBController::class, 'create'])->name('kb.upload');
    Route::post('/store', [KBController::class, 'store'])->name('kb.store');
    Route::get('/view/{id}', [KBController::class, 'view'])->name('kb.view');
    Route::get('/search', [KBController::class, 'search'])->name('kb.search');
});





// Route::get('/chatbot', Chatbot::class)->name('livewire.chatbot');


Route::get('/verify-email/{token}', [EmailVerificationController::class, 'verifyEmail'])->name('verify.email');

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

Route::get('/2fa/verify', [TwoFactorAuthController::class, 'verifyForm'])->name('2fa.verify.form');
Route::post('/2fa/verify', [TwoFactorAuthController::class, 'verify'])->name('2fa.verify');
