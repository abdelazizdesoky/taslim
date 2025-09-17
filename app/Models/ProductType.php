<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Product;
use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductType extends Model
{
    use HasFactory;
      protected $fillable = [
        
        'type_name',
        'brand_id'
    ];

    
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'type_id');  // type_id في جدول products يشير إلى product_types
    }

    use TracksActivity;

protected static $logAttributes = ['type_name','brand_id'];
protected static $logName = 'ProductType';
protected static $logOnlyDirty = true;

}
