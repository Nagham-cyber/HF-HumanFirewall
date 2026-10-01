<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║      🧪 COMPREHENSIVE TEST - ALL ROUTES & PAGES          ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

// ==================== TEST USER ROUTES ====================
$userRoutes = [
    'home', 'login', 'register', 'forgot-password',
    'dashboard', 'leaderboard',
    'scenarios.index', 'scenarios.show', 'scenarios.play',
    'ai.assistant', 'ai.chat',
    'daily-challenge.index', 'daily-challenge.submit-all',
    'mistakes.index', 'mistakes.show', 'mistakes.review',
    'tasks.index', 'tasks.show', 'tasks.submit',
    'messages.index', 'messages.send',
    'badges.index', 'badges.show', 'badges.share',
    'progress', 'certificates.index', 'certificates.show', 'certificates.download',
    'profile.index', 'profile.update', 'profile.password', 'profile.delete',
    'notifications.index', 'notifications.read',
    'logout',
];

// ==================== TEST ADMIN ROUTES ====================
$adminRoutes = [
    'admin.login', 'admin.login.submit', 'admin.2fa.show', 'admin.2fa.verify',
    'admin.dashboard', 'admin.logout',
    'admin.scenarios.index', 'admin.scenarios.create', 'admin.scenarios.store', 'admin.scenarios.destroy',
    'admin.challenges.index', 'admin.challenges.create', 'admin.challenges.storeThree',
    'admin.tasks.index', 'admin.tasks.create', 'admin.tasks.store', 'admin.tasks.submissions', 'admin.tasks.submission.show', 'admin.tasks.submission.review',
    'admin.users.index', 'admin.users.show', 'admin.users.ban', 'admin.users.destroy', 'admin.users.reset-password', 'admin.users.update',
    'admin.categories.index', 'admin.categories.create', 'admin.categories.edit', 'admin.categories.update', 'admin.categories.destroy',
    'admin.messages.index', 'admin.messages.conversation', 'admin.messages.send', 'admin.messages.announcement', 'admin.messages.conversation.delete',
    'admin.settings.index', 'admin.settings.profile', 'admin.settings.password', 'admin.settings.2fa', 'admin.settings.allowed-ips',
    'admin.security.index', 'admin.security.clear-attempts',
    'admin.analytics',
    'admin.audit-logs.index', 'admin.audit-logs.show', 'admin.audit-logs.export',
    'admin.reports.index', 'admin.reports.exportUsers', 'admin.reports.exportSubmissions', 'admin.reports.exportScenarios', 'admin.reports.exportMistakes',
    'admin.backups.index', 'admin.backups.create', 'admin.backups.download', 'admin.backups.restore', 'admin.backups.delete',
];

// ==================== TEST DATABASE ====================
echo "📊 DATABASE CHECKS\n";
echo str_repeat('─', 60) . "\n";

$tables = [
    'users', 'admins', 'admin_audit_logs', 'login_attempts',
    'categories', 'scenarios', 'cyber_tasks', 'challenges', 'daily_challenges',
    'task_submissions', 'messages', 'user_mistakes', 'user_progress', 'badges',
];

foreach ($tables as $table) {
    $exists = \Schema::hasTable($table);
    $count = $exists ? DB::table($table)->count() : 0;
    $icon = $exists ? '✅' : '❌';
    echo "  $icon {$table}: {$count} rows\n";
}
echo "\n";

// ==================== TEST USER ROUTES ====================
echo "👤 USER ROUTES\n";
echo str_repeat('─', 60) . "\n";

$userMissing = 0;
foreach ($userRoutes as $routeName) {
    if (Route::has($routeName)) {
        echo "  ✅ {$routeName}\n";
    } else {
        echo "  ❌ {$routeName} — MISSING\n";
        $userMissing++;
    }
}
echo "\n";

// ==================== TEST ADMIN ROUTES ====================
echo "🔐 ADMIN ROUTES\n";
echo str_repeat('─', 60) . "\n";

$adminMissing = 0;
foreach ($adminRoutes as $routeName) {
    if (Route::has($routeName)) {
        echo "  ✅ {$routeName}\n";
    } else {
        echo "  ❌ {$routeName} — MISSING\n";
        $adminMissing++;
    }
}
echo "\n";

// ==================== TEST VIEWS ====================
echo "🎨 VIEW FILES\n";
echo str_repeat('─', 60) . "\n";

$views = [
    'welcome', 'dashboard', 'leaderboard',
    'layouts.app',
    'scenarios.index', 'scenarios.show', 'scenarios.play',
    'challenges.index',
    'badges.index', 'progress.index', 'profile.index',
    'certificates.index', 'certificates.show',
    'mistakes.index', 'mistakes.show',
    'ai.assistant', 'notifications.index',
    'tasks.index', 'tasks.show',
    'user-messages.index',
    'auth.login', 'auth.register', 'auth.forgot-password',
    'admin.layouts.app',
    'admin.auth.login', 'admin.auth.2fa',
    'admin.dashboard',
    'admin.scenarios.index', 'admin.scenarios.create',
    'admin.challenges.index', 'admin.challenges.create',
    'admin.tasks.index', 'admin.tasks.create', 'admin.tasks.submissions', 'admin.tasks.submission-show',
    'admin.users.index', 'admin.users.show',
    'admin.categories.index', 'admin.categories.create', 'admin.categories.edit',
    'admin.messages.index', 'admin.messages.conversation',
    'admin.settings.index',
    'admin.security.index',
    'admin.analytics.index',
    'admin.audit-logs.index', 'admin.audit-logs.show',
    'admin.reports.index',
    'admin.backups.index',
];

$viewMissing = 0;
foreach ($views as $view) {
    if (view()->exists($view)) {
        echo "  ✅ {$view}\n";
    } else {
        echo "  ❌ {$view} — MISSING\n";
        $viewMissing++;
    }
}
echo "\n";

// ==================== TEST CONTROLLERS ====================
echo "🎮 CONTROLLERS\n";
echo str_repeat('─', 60) . "\n";

$controllers = [
    'DashboardController', 'ScenarioController', 'AIController',
    'UserProgressController', 'BadgeController', 'MistakeController',
    'DailyChallengeController', 'ProfileController', 'NotificationController',
    'CertificateController', 'LanguageController', 'ForgotPasswordController',
    'CyberTaskController', 'UserMessageController',
    'Auth\\LoginController', 'Auth\\RegisterController',
    'Admin\\AdminAuthController', 'Admin\\AdminDashboardController',
    'Admin\\AdminScenarioController', 'Admin\\AdminChallengeController',
    'Admin\\AdminTaskController', 'Admin\\AdminUserController',
    'Admin\\AdminCategoryController', 'Admin\\AdminMessageController',
    'Admin\\AdminSettingsController', 'Admin\\AdminSecurityController',
    'Admin\\AdminAnalyticsController', 'Admin\\AdminAuditLogController',
    'Admin\\AdminReportController', 'Admin\\AdminBackupController',
];

$controllerMissing = 0;
foreach ($controllers as $controller) {
    $class = 'App\\Http\\Controllers\\' . $controller;
    if (class_exists($class)) {
        echo "  ✅ {$controller}\n";
    } else {
        echo "  ❌ {$controller} — MISSING\n";
        $controllerMissing++;
    }
}
echo "\n";

// ==================== FINAL REPORT ====================
$totalMissing = $userMissing + $adminMissing + $viewMissing + $controllerMissing;

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║                    📋 FINAL REPORT                       ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

echo "  👤 User Routes:     " . (count($userRoutes) - $userMissing) . "/" . count($userRoutes) . "\n";
echo "  🔐 Admin Routes:    " . (count($adminRoutes) - $adminMissing) . "/" . count($adminRoutes) . "\n";
echo "  🎨 Views:           " . (count($views) - $viewMissing) . "/" . count($views) . "\n";
echo "  🎮 Controllers:     " . (count($controllers) - $controllerMissing) . "/" . count($controllers) . "\n";
echo "  📊 Missing Total:   {$totalMissing}\n";
echo "\n";

if ($totalMissing === 0) {
    echo "🎉 ═══════════════════════════════════════════════════════ 🎉\n";
    echo "       ALL SYSTEMS OPERATIONAL! READY TO LAUNCH!        \n";
    echo "🎉 ═══════════════════════════════════════════════════════ 🎉\n";
} else {
    echo "⚠️  {$totalMissing} issue(s) need attention.\n";
}
echo "\n";