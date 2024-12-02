<?php

namespace App\Http\Controllers\Dashboard;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = Activity::all()->map(function($log) {
            $log->properties = json_decode($log->properties, true);

            // التأكد من وجود البيانات القديمة والجديدة
            $changes = null;
            if (isset($log->properties['old']) && isset($log->properties['attributes'])) {
                $changes = $this->compareOldAndNew($log->properties['old'], $log->properties['attributes']);
            }
           
            // إضافة التغييرات إلى الكائن دون تعديل الخاصية `changes`
            $log->setAttribute('custom_changes', $changes);

            return $log;
        });

        return view('Dashboard.Admin.logs.index', compact('logs'));
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
}


