<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customers extends Model
{
    use HasFactory;
    protected $guarded=[];
    
    public static function latestProduct(int $count = 5): Collection
    {
        return self::query()
            ->select(
                'id',
                'name',
                'code'
              
            )
            ->latest()
            ->take($count)
            ->get();
    }

    use TracksActivity;

    protected static $logAttributes = ['code', 'name', 'address','phone','status'];
    protected static $logName = 'customers';
    protected static $logOnlyDirty = true;
    
}
