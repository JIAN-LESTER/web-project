<?php


use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\KBController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ConversationController;
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
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

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

Route::post('/2fa/resend', [TwoFactorAuthController::class, 'resend'])->name('2fa.resend');

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




Route::get('user/profile', [UserManagementController::class, 'profile'])->name('profile');
Route::put('user/updateProfile', [UserManagementController::class, 'updateProfile'])->name('profile.update');
Route::get('/user/{id}/profile', [UserManagementController::class, 'editProfile'])->name('profile.edit');


Route::get('admin/inquiry-logs', [AdminController::class, 'viewInquiryLogs'])->name('admin.inquiry_logs');

Route::get('user/dashboard', [UserController::class, 'viewDashboard'])->name('user.dashboard');

Route::get('/conversations/load', [ConversationController::class, 'loadMore'])->name('conversations.load');






Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/dashboard', [AdminController::class, 'viewDashboard', ])->name('admin.dashboard');
Route::get('/reports-analytics', [ReportsController::class, 'viewReports'])->name('admin.reports_analytics');
Route::get('/reports-analytics/export', [ReportsController::class, 'exportReports'])->name('admin.reports_analytics.exports');
Route::get('/admin/reports/export-csv', [ReportsController::class, 'exportCsv'])->name('admin.reports_export_csv');
Route::get('/reports/ajax-data', [ReportsController::class, 'ajaxReportData'])->name('reports.ajax');


Route::get('/faqs', [FAQController::class, 'index'])->name('faqs');
Route::get('/faqs/create', [FAQController::class, 'create'])->name('faqs.create');
Route::post('/faqs/store', [FAQController::class, 'store'])->name('faqs.store');
Route::get('/faqs/edit/{id}', [FAQController::class, 'edit'])->name('faqs.edit');
Route::post('/faqs/update/{id}', [FAQController::class, 'update'])->name('faqs.update');
Route::delete('/faqs/destroy/{id}', [FAQController::class, 'destroy'])->name('faqs.destroy');

Route::post('/faq/question', [FAQController::class, 'handleFaqQuestion']);





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

Route::get('/admin/user_crud/{id}/data', [UserController::class, 'getUserData']);


Route::prefix('admin/kb')->middleware(['auth'])->group(function () {
    Route::get('/search', [KBController::class, 'search'])->name('kb.search');
    Route::get('/', [AdminController::class, 'viewKB'])->name('admin.knowledge_base');
    Route::get('/upload', [KBController::class, 'create'])->name('kb.upload');
    Route::post('/store', [KBController::class, 'store'])->name('kb.store');
    Route::get('/view/{id}', [KBController::class, 'view'])->name('kb.view');
    Route::delete('/destroy/{id}', [KBController::class, 'destroy'])->name('kb.destroy');
});





// Route::get('/chatbot', Chatbot::class)->name('livewire.chatbot');


Route::get('/verify-email/{token}', [EmailVerificationController::class, 'verifyEmail'])->name('verify.email');

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

Route::get('/2fa/verify', [TwoFactorAuthController::class, 'verifyForm'])->name('2fa.verify.form');
Route::post('/2fa/verify', [TwoFactorAuthController::class, 'verify'])->name('2fa.verify');
