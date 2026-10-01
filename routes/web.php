<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\UserProgressController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\MistakeController;
use App\Http\Controllers\DailyChallengeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|==========================================================================
| ADMIN ROUTES — MUST BE FIRST (Highest Priority)
|==========================================================================
*/

Route::prefix('admin/hf-x7k9m-p2q8-2026-secure')->name('admin.')->group(function () {

    // ==================== GUEST (LOGIN) ====================
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])
            ->name('login.submit')
            ->middleware('throttle:5,1'); // 5 محاولات في الدقيقة
        Route::get('/2fa', [\App\Http\Controllers\Admin\AdminAuthController::class, 'show2FA'])->name('2fa.show');
        Route::post('/2fa', [\App\Http\Controllers\Admin\AdminAuthController::class, 'verify2FA'])->name('2fa.verify');
    });

    // ==================== PROTECTED (ADMIN) ====================
    Route::middleware('admin.auth')->group(function () {

        // ---------- Dashboard ----------
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');

        // ---------- Scenarios ----------
        Route::prefix('scenarios')->name('scenarios.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminScenarioController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\AdminScenarioController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\AdminScenarioController::class, 'store'])->name('store');
            Route::post('/delete-by-title', [\App\Http\Controllers\Admin\AdminScenarioController::class, 'deleteByTitle'])->name('deleteByTitle');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\AdminScenarioController::class, 'destroy'])->name('destroy');
        });

        // ---------- Challenges ----------
        Route::prefix('challenges')->name('challenges.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminChallengeController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\AdminChallengeController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\AdminChallengeController::class, 'store'])->name('store');
            Route::post('/store-three', [\App\Http\Controllers\Admin\AdminChallengeController::class, 'storeThree'])->name('storeThree');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\AdminChallengeController::class, 'destroy'])->name('destroy');
        });

        // ---------- Categories ----------
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'edit'])->name('edit');
            Route::post('/{id}/update', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'destroy'])->name('destroy');
        });

        // ---------- Users ----------
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('show');
            Route::post('/{id}/ban', [\App\Http\Controllers\Admin\AdminUserController::class, 'toggleBan'])->name('ban');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/reset-password', [\App\Http\Controllers\Admin\AdminUserController::class, 'resetPassword'])->name('reset-password');
            Route::post('/{id}/update', [\App\Http\Controllers\Admin\AdminUserController::class, 'update'])->name('update');
        });

        // ---------- Cyber Tasks ----------
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminTaskController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\AdminTaskController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\AdminTaskController::class, 'store'])->name('store');
            Route::post('/generate-code', [\App\Http\Controllers\Admin\AdminTaskController::class, 'generateCode'])->name('generateCode');
            Route::get('/submissions', [\App\Http\Controllers\Admin\AdminTaskController::class, 'submissions'])->name('submissions');
            Route::get('/submissions/{id}', [\App\Http\Controllers\Admin\AdminTaskController::class, 'showSubmission'])->name('submission.show');
            Route::post('/submissions/{id}/review', [\App\Http\Controllers\Admin\AdminTaskController::class, 'reviewSubmission'])->name('submission.review');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\AdminTaskController::class, 'destroy'])->name('destroy');
        });

        // ---------- Messages ----------
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminMessageController::class, 'index'])->name('index');
            Route::get('/conversation/{userId}', [\App\Http\Controllers\Admin\AdminMessageController::class, 'conversation'])->name('conversation');
            Route::post('/send', [\App\Http\Controllers\Admin\AdminMessageController::class, 'send'])->name('send');
            Route::post('/announcement', [\App\Http\Controllers\Admin\AdminMessageController::class, 'sendAnnouncement'])->name('announcement');
            Route::delete('/conversation/{userId}', [\App\Http\Controllers\Admin\AdminMessageController::class, 'deleteConversation'])->name('conversation.delete');
        });

        // ---------- Audit Logs ----------
        Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminAuditLogController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\Admin\AdminAuditLogController::class, 'export'])->name('export');
            Route::get('/{id}', [\App\Http\Controllers\Admin\AdminAuditLogController::class, 'show'])->name('show');
        });

        // ---------- Backups ----------
        Route::prefix('backups')->name('backups.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminBackupController::class, 'index'])->name('index');
            Route::post('/create', [\App\Http\Controllers\Admin\AdminBackupController::class, 'create'])->name('create');
            Route::get('/download/{filename}', [\App\Http\Controllers\Admin\AdminBackupController::class, 'download'])->name('download');
            Route::post('/restore/{filename}', [\App\Http\Controllers\Admin\AdminBackupController::class, 'restore'])->name('restore');
            Route::delete('/{filename}', [\App\Http\Controllers\Admin\AdminBackupController::class, 'delete'])->name('delete');
        });

        // ---------- Reports ----------
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminReportController::class, 'index'])->name('index');
            Route::get('/export-users', [\App\Http\Controllers\Admin\AdminReportController::class, 'exportUsers'])->name('exportUsers');
            Route::get('/export-submissions', [\App\Http\Controllers\Admin\AdminReportController::class, 'exportSubmissions'])->name('exportSubmissions');
            Route::get('/export-scenarios', [\App\Http\Controllers\Admin\AdminReportController::class, 'exportScenarios'])->name('exportScenarios');
            Route::get('/export-mistakes', [\App\Http\Controllers\Admin\AdminReportController::class, 'exportMistakes'])->name('exportMistakes');
        });
                // ---------- Advanced Analytics ----------
        Route::get('/analytics', [\App\Http\Controllers\Admin\AdminAnalyticsController::class, 'index'])->name('analytics');

        // ---------- Security Dashboard ----------
        Route::prefix('security')->name('security.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminSecurityController::class, 'index'])->name('index');
            Route::post('/clear-attempts', [\App\Http\Controllers\Admin\AdminSecurityController::class, 'clearAttempts'])->name('clear-attempts');
        });

        // ---------- Settings ----------
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])->name('index');
            Route::post('/profile', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'updateProfile'])->name('profile');
            Route::post('/password', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'updatePassword'])->name('password');
            Route::post('/2fa', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'toggle2FA'])->name('2fa');
            Route::post('/allowed-ips', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'updateAllowedIPs'])->name('allowed-ips');
        });
    });
});


/*
|==========================================================================
| USER ROUTES
|==========================================================================
*/

// ==================== HOME ====================
Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index'])->name('home');

// ==================== LANGUAGE SWITCH ====================
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// ==================== GUEST ROUTES (User) ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,1');
});

// ==================== PASSWORD RESET ====================
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot-password');
Route::post('/forgot-password/send-code', [ForgotPasswordController::class, 'sendResetCode'])->name('forgot-password.send-code')->middleware('throttle:3,1');
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('forgot-password.reset');

// ==================== LOGOUT ====================
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== AUTHENTICATED USER ROUTES ====================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/leaderboard', [DashboardController::class, 'leaderboard'])->name('leaderboard');

    // Scenarios
    Route::prefix('scenarios')->name('scenarios.')->group(function () {
        Route::get('/', [ScenarioController::class, 'index'])->name('index');
        Route::get('/{id}', [ScenarioController::class, 'show'])->name('show');
        Route::get('/{id}/play', [ScenarioController::class, 'play'])->name('play');
        Route::post('/{id}/complete', [ScenarioController::class, 'complete'])->name('complete');
    });

    // AI Assistant
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/assistant', [AIController::class, 'index'])->name('assistant');
        Route::post('/chat', [AIController::class, 'chat'])->name('chat')->middleware('throttle:20,1');
        Route::post('/analyze-email', [AIController::class, 'analyzeEmail'])->name('analyze-email');
    });

    // Daily Challenge
    Route::prefix('daily-challenge')->name('daily-challenge.')->group(function () {
        Route::get('/', [DailyChallengeController::class, 'index'])->name('index');
        Route::post('/submit-all', [DailyChallengeController::class, 'submitAll'])->name('submit-all');
    });

    // Mistakes Vault
    Route::prefix('mistakes')->name('mistakes.')->group(function () {
        Route::get('/', [MistakeController::class, 'index'])->name('index');
        Route::get('/{id}', [MistakeController::class, 'show'])->name('show');
        Route::post('/{id}/review', [MistakeController::class, 'markReviewed'])->name('review');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password')->middleware('throttle:3,1');
        Route::post('/delete', [ProfileController::class, 'destroy'])->name('delete');
    });

    // Notifications (News)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markRead'])->name('read');
    });

    // Certificates
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [CertificateController::class, 'index'])->name('index');
        Route::get('/{type}', [CertificateController::class, 'show'])->name('show');
        Route::get('/{type}/download-pdf', [CertificateController::class, 'downloadPDF'])->name('download');
    });

    // Progress
    Route::get('/progress', [UserProgressController::class, 'index'])->name('progress');

    // Cyber Tasks
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CyberTaskController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\CyberTaskController::class, 'show'])->name('show');
        Route::post('/{id}/submit', [\App\Http\Controllers\CyberTaskController::class, 'submit'])->name('submit')->middleware('throttle:5,1');
    });

    // Messages (User)
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserMessageController::class, 'index'])->name('index');
        Route::post('/send', [\App\Http\Controllers\UserMessageController::class, 'send'])->name('send')->middleware('throttle:10,1');
        Route::post('/{id}/read', [\App\Http\Controllers\UserMessageController::class, 'markAnnouncementRead'])->name('read');
    });

    // Badges
    Route::prefix('badges')->name('badges.')->group(function () {
        Route::get('/', [BadgeController::class, 'index'])->name('index');
        Route::get('/{id}', [BadgeController::class, 'show'])->name('show');
        Route::post('/{id}/share', [BadgeController::class, 'shareLinkedIn'])->name('share');
    });
});