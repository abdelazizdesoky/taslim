<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    //--------------------------------------------------------------
  
    public function manualBackup()
    {
        try {
            // تنفيذ أمر النسخ الاحتياطي
            Artisan::call('db:backup');
    
            // التحقق من نجاح العملية
            $lastBackup = DB::table('backups')->latest('id')->first();
    
            if ($lastBackup) {
                return redirect()->route('admin.show-backups')->with('success', 'تم النسخ الاحتياطي بنجاح');
            }
    
            return redirect()->route('admin.show-backups')->with('error', 'فشل النسخ الاحتياطي. لم يتم العثور على نسخة.');
        } catch (\Exception $e) {
            return redirect()->route('admin.show-backups')->with('error', 'حدث خطأ أثناء النسخ الاحتياطي.');
        }
    }
    
//--------------------------------------------------------------

    public function showBackups()
    {
        $backups = DB::table('backups')->orderBy('created_at', 'desc')->get();
        return view('Dashboard.Admin.Backup.index', compact('backups'));
    }

    //--------------------------------------------------------------

    public function downloadBackup($id)
    {
        $backup = DB::table('backups')->where('id', $id)->first();

        if (!$backup) {
            return redirect()->back()->with('error', 'النسخة الاحتياطية غير موجودة.');
        }

        if (!file_exists($backup->path)) {
            return redirect()->back()->with('error', 'ملف النسخة الاحتياطية غير موجود.');
        }

        return response()->download($backup->path);
    }

    //--------------------------------------------------------------

    public function deleteBackup($id)
    {
        $backup = DB::table('backups')->where('id', $id)->first();


        if (!$backup) {
            return redirect()->back()->with('error', 'النسخة الاحتياطية غير موجودة.');
        }

        // حذف الملف من التخزين
        if (file_exists($backup->path)) {
            unlink($backup->path);
        }

        // حذف السجل من قاعدة البيانات
        DB::table('backups')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'تم حذف النسخة الاحتياطية بنجاح.');
    }
}
