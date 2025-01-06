<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Backup extends Model
{
    use HasFactory;
    protected $guarded=[];


    use TracksActivity;

    protected static $logAttributes = ['id','path', 'size', 'type','created_at'];
    protected static $logName = 'Backup';
    protected static $logOnlyDirty = true;
}
