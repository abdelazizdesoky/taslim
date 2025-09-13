<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use App\DataTables\ActivityLogDataTable;

class ActivityLogController extends Controller
{


     public function index(ActivityLogDataTable $datatable)
    {
        if (request()->ajax()) {
            return $datatable->ajax();
        }
        return $datatable->render('Dashboard.Admin.logs.index');
 
    }

    // دالة للمقارنة بين البيانات القديمة والجديدة
    private function compareOldAndNew($old, $new)
    {
        $changes = [];
        foreach ($new as $key => $newValue) {
            if (isset($old[$key]) && $old[$key] != $newValue) {
                $changes[$key] = [
                    'old' => $old[$key],
                    'new' => $newValue
                ];
            }
        }
        return $changes;
    }


    public function show($id)
{
    // استرجاع اللوج المطلوب
    $log = Activity::findOrFail($id);

    // فك تشفير الـ properties
    $log->properties = json_decode($log->properties, true);

    // التأكد من وجود البيانات القديمة والجديدة
    $changes = null;
    if (isset($log->properties['old']) && isset($log->properties['attributes'])) {
        $changes = $this->compareOldAndNew($log->properties['old'], $log->properties['attributes']);
    }

    // إضافة التغييرات إلى الكائن
    $log->setAttribute('custom_changes', $changes);

    // عرض صفحة التفاصيل مع البيانات
    return view('Dashboard.Admin.logs.show', compact('log'));
}


}


