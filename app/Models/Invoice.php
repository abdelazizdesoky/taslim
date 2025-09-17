<?php

namespace App\Models;


use App\Models\Admin;
use App\Models\Product;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\Customers;
use App\Models\SerialNumber;
use App\Traits\TracksActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;




class Invoice extends Model
{
    use HasFactory;
       protected $fillable = [
        'code',
        'invoice_date',
        'invoice_status',
        'invoice_type',
        'location_id',
        'employee_id',
        'supplier_id',
        'customer_id',
        'created_by',
        'products_data'
    ];

    protected static $logAttributes = [
        'code',
        'invoice_date',
        'invoice_status',
        'invoice_type',
        'location_id',
        'employee_id',
        'supplier_id',
        'customer_id',
        'created_by',
    ];

    protected static $logName = 'invoice';
    protected static $logOnlyDirty = true;

    use TracksActivity;

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'employee_id');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function serialNumbers()
    {
        return $this->hasMany(SerialNumber::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'invoice_products')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function productsSumQuantity()
    {
        return $this->products()
            ->selectRaw('sum(invoice_products.quantity) as total_quantity')
            ->groupBy('invoice_products.invoice_id');
    }

    public static function latestInvoices(int $count = 5): Collection
    {
        return self::query()
            ->with('customer:id,name')
            ->with('supplier:id,name')
            ->withCount('serialNumbers')
            ->select(
                'id',
                'code',
                'invoice_date',
                'invoice_type',
            )
            ->latest()
            ->take($count)
            ->get();
    }
}
