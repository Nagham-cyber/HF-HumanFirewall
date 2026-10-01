<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\AdminAuditLog;

class AdminBackupController extends Controller
{
    /**
     * مجلد النسخ الاحتياطية
     */
    private function backupPath()
    {
        $path = storage_path('app/backups');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        return $path;
    }

    /**
     * صفحة عرض النسخ
     */
    public function index()
    {
        $backupPath = $this->backupPath();
        $files = File::files($backupPath);

        $backups = [];
        foreach ($files as $file) {
            $backups[] = [
                'name' => $file->getFilename(),
                'size' => $this->formatSize($file->getSize()),
                'size_bytes' => $file->getSize(),
                'created_at' => Carbon::createFromTimestamp($file->getMTime())->format('M d, Y H:i:s'),
                'timestamp' => $file->getMTime(),
            ];
        }

        // ترتيب: الأحدث أولاً
        usort($backups, function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        $stats = [
            'total' => count($backups),
            'total_size' => $this->formatSize(array_sum(array_column($backups, 'size_bytes'))),
            'last_backup' => count($backups) > 0 ? $backups[0]['created_at'] : 'Never',
            'db_size' => File::exists(database_path('database.sqlite'))
                ? $this->formatSize(File::size(database_path('database.sqlite')))
                : 'N/A',
        ];

        return view('admin.backups.index', compact('backups', 'stats'));
    }

    /**
     * إنشاء نسخة احتياطية
     */
    public function create(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $type = $request->get('type', 'db'); // db | full

        try {
            $timestamp = now()->format('Y-m-d_His');
            $filename = "backup_{$type}_{$timestamp}.sqlite";
            $backupPath = $this->backupPath() . '/' . $filename;

            // نسخ قاعدة البيانات (SQLite)
            $dbPath = database_path('database.sqlite');

            if (!File::exists($dbPath)) {
                return back()->with('error', 'Database file not found.');
            }

            // تشفير النسخة الاحتياطية
            $dbContent = File::get($dbPath);
            $encryptedContent = encrypt($dbContent);
            File::put($backupPath, $encryptedContent);

            // إذا كان "full" — نضيف الملفات المهمة في ZIP
            if ($type === 'full') {
                $zipFilename = "backup_full_{$timestamp}.zip";
                $zipPath = $this->backupPath() . '/' . $zipFilename;

                $zip = new \ZipArchive();
                if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
                    // قاعدة البيانات
                    $zip->addFile($dbPath, 'database/database.sqlite');

                    // ملفات المشروع المهمة (بدون vendor)
                    $this->addFolderToZip($zip, base_path('app'), 'app');
                    $this->addFolderToZip($zip, base_path('config'), 'config');
                    $this->addFolderToZip($zip, base_path('database/migrations'), 'database/migrations');
                    $this->addFolderToZip($zip, base_path('database/seeders'), 'database/seeders');
                    $this->addFolderToZip($zip, base_path('resources/views'), 'resources/views');
                    $this->addFolderToZip($zip, base_path('routes'), 'routes');
                    $this->addFolderToZip($zip, base_path('public/pic'), 'public/pic');

                    // .env
                    if (File::exists(base_path('.env'))) {
                        $zip->addFile(base_path('.env'), '.env');
                    }

                    $zip->close();

                    // احذف ملف sqlite المؤقت
                    File::delete($backupPath);

                    $filename = $zipFilename;
                }
            }

            AdminAuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'backup_created',
                'description' => "Backup created: {$filename}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', "Backup created successfully: {$filename}");

        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * تحميل نسخة
     */
    public function download($filename)
    {
        $path = $this->backupPath() . '/' . $filename;

        if (!File::exists($path)) {
            return back()->with('error', 'Backup not found.');
        }

        return response()->download($path);
    }

    /**
     * استعادة نسخة
     */
    public function restore(Request $request, $filename)
    {
        $admin = Auth::guard('admin')->user();
        $path = $this->backupPath() . '/' . $filename;

        if (!File::exists($path)) {
            return back()->with('error', 'Backup not found.');
        }

        try {
            // احفظ نسخة احتياطية من الوضع الحالي أولاً (أمان)
            $safetyName = 'before_restore_' . now()->format('Y-m-d_His') . '.sqlite';
            File::copy(database_path('database.sqlite'), $this->backupPath() . '/' . $safetyName);

            if (str_ends_with($filename, '.sqlite')) {
                // استعادة قاعدة البيانات مباشرة
                File::copy($path, database_path('database.sqlite'));
            } elseif (str_ends_with($filename, '.zip')) {
                // استخراج ZIP
                $zip = new \ZipArchive();
                if ($zip->open($path) === true) {
                    // استخرج قاعدة البيانات
                    $dbContent = $zip->getFromName('database/database.sqlite');
                    if ($dbContent !== false) {
                        File::put(database_path('database.sqlite'), $dbContent);
                    }
                    $zip->close();
                }
            }

            AdminAuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'backup_restored',
                'description' => "Backup restored: {$filename}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', "Backup restored: {$filename}. Safety backup: {$safetyName}");

        } catch (\Exception $e) {
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * حذف نسخة
     */
    public function delete($filename)
    {
        $admin = Auth::guard('admin')->user();
        $path = $this->backupPath() . '/' . $filename;

        if (!File::exists($path)) {
            return back()->with('error', 'Backup not found.');
        }

        File::delete($path);

        AdminAuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'backup_deleted',
            'description' => "Backup deleted: {$filename}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Backup deleted successfully!');
    }

    /**
     * إضافة مجلد إلى ZIP
     */
    private function addFolderToZip($zip, $folder, $zipFolder)
    {
        if (!File::exists($folder)) return;

        $files = File::allFiles($folder);
        foreach ($files as $file) {
            $relativePath = $zipFolder . '/' . $file->getRelativePathname();
            $zip->addFile($file->getRealPath(), $relativePath);
        }
    }

    /**
     * تنسيق الحجم
     */
    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}