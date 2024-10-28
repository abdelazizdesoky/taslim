<?php

namespace App\Models;


use App\Models\Admin;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\Customers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded=[];


    
   
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    
    public function admin()
    {
        return $this->belongsTo(Admin::class,'employee_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class,'location_id');
    }


    public function serialNumbers()
    {
        return $this->hasMany(SerialNumber::class); 
    }
  
}
