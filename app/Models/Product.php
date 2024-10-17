<?php

namespace App\Models;

use App\Models\ProductCode;
use App\Models\ProductType;
use App\Models\ProductDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
  


    protected $guarded=[];

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }

    public function brand()
    {
        return $this->hasOneThrough(Brand::class, ProductType::class, 'id', 'id', 'type_id', 'brand_id');
    }


    public function productCodes()
    {
        return $this->hasMany(ProductCode::class, 'product_id');
    }

    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class, 'product_id');

    }


}
