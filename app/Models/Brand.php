<?php

namespace App\Models;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $guarded=[];

 
    public function productTypes()
    {
        return $this->hasMany(ProductType::class, 'brand_id');
    }
    
}
