<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceProduct extends Model
{
    use HasFactory;
    protected $guarded=[];

   use TracksActivity;
   
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected static $logAttributes = ['invoice_id', 'product_id', 'quantity'];
    protected static $logName = 'InvoiceProduct';
    protected static $logOnlyDirty = true;
  
}
