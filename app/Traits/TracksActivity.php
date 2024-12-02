<?php

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

trait TracksActivity
{
    use LogsActivity;

    /**
     * تخصيص الأعمدة والخصائص التي يتم تتبعها
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(static::$logAttributes ?? []) // الأعمدة التي سيتم تتبعها
            ->logOnlyDirty(static::$logOnlyDirty ?? true) // تسجيل التغييرات فقط
            ->useLogName(static::$logName ?? 'default'); // اسم السجل الافتراضي

    }

   
}
