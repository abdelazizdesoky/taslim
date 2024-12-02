<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
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
}