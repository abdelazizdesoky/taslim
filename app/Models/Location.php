<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    protected $guarded=[];



    use TracksActivity;

    protected static $logAttributes = ['location_name'];
    protected static $logName = 'Location';
    protected static $logOnlyDirty = true;
}

