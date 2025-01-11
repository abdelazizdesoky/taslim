<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin  extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use TracksActivity;

    protected static $logAttributes = ['name', 'email','password','permission','status'];
    protected static $logName = 'Admin';
    protected static $logOnlyDirty = true;
    protected $guarded=[];

    protected $hidden = [
        'password',
        'remember_token',
    ];

  
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class,'employee_id');
    }

    public static function mostSerialTakers(int $count = 4)
    {
        return self::select('name','id')
            ->whereIn('permission', [3, 4]) // التأكد من صلاحية المندوب
            ->withCount('invoices')
            ->orderByDesc('invoices_count') // ترتيب تنازلي
            ->take($count) // اختيار الثلاثة الأعلى
            ->get();
    }
}