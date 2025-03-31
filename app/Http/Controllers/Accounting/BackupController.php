<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Backup\Tasks\Backup\BackupJob;
use Spatie\Backup\BackupDestination\BackupDestination;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class BackupController extends Controller
{


    public function index(){

        return view('financialaccounting.settings.index');
    }

    public function createBackup()
    {
        try {

            Log::info('Backup disks config: ' . json_encode(config('backup.destination.disks')));
            Log::info('Backup name config: ' . config('backup.backup.name'));
            Log::info('Full backup config: ' . json_encode(config('backup')));

            $code =  Artisan::call('backup:run');
            dd($code);
            sleep(5);

            $disks = config('backup.backup.destination.disks');
            if (empty($disks) || !is_array($disks)) {
                throw new \Exception('لم يتم تعيين أي قرص للنسخ الاحتياطي في الإعدادات!');
            }
            $disk = $disks[0];

            $backupName = config('backup.backup.name');
            if (!$backupName) {
                throw new \Exception('اسم النسخ الاحتياطي غير مُعرف في الإعدادات!');
            }

            $backupDir = $backupName;

            $files = Storage::disk($disk)->files($backupDir);
            $latestFile = collect($files)
                ->filter(function ($file) {
                    return str_ends_with($file, '.zip');
                })
                ->sortByDesc(function ($file) use ($disk) {
                    return Storage::disk($disk)->lastModified($file);
                })
                ->first();

            if (!$latestFile) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على ملف النسخة الاحتياطية!'
                ], 404);
            }

            $filePath = Storage::disk($disk)->path($latestFile);
            return response()->download($filePath, basename($latestFile));

        } catch (\Exception $e) {
            Log::error('Backup creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'فشل إنشاء النسخة الاحتياطية: ' . $e->getMessage()
            ], 500);
        }
    }

}
