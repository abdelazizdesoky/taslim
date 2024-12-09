<?php

namespace App\Http\Controllers\Employee;

use App\Models\Invoice;
use App\Models\Employee;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeInvoiceController extends Controller
{
    public function index()
    {

    


    }

  

    public function update($id)
    {

     if ($id == 1){
     //اذن التسليم 

          $activeInvoicesCount = Invoice::where('employee_id', Auth::user()->id)
          ->whereIn('invoice_status', [1])//,مرتجع  حالة الفاتورة تحت تسليم  
          ->whereIn('invoice_type',[$id,3]) // إتسليم 
          ->count();

          $Invoices = Invoice::where('employee_id', Auth::user()->id)
          ->whereIn('invoice_status', [1])
          ->whereIn('invoice_type', [$id,3]) // -مرتجعات | تسليم
          ->orderBy('invoice_date', 'desc')
          ->get();   
 

      return view('Dashboard.Employees.Invoices.invoice2',compact('Invoices','activeInvoicesCount') );


     }elseif($id == 2){

   // الاذن الاستلام 

          $activeInvoicesCount = Invoice::where('employee_id', Auth::user()->id)
          ->whereIn('invoice_status', [1])  // حالة الفاتورة تحت استلام 
          ->whereIn('invoice_type', [2,3]) // استلام
          ->count();

          $Invoices = Invoice::where('employee_id', Auth::user()->id)
          ->whereIn('invoice_status', [1])
          ->whereIn('invoice_type',[2,3]) // "استلام"
          ->orderBy('invoice_date', 'desc')
          ->get();   
 

      return view('Dashboard.Employees.Invoices.invoice',compact('Invoices','activeInvoicesCount') );



     }


              
    }

//----------------------------------------------------------------------------

    public function Compinvoice()
    {
       
        //مكتمل 
        
         $activeInvoicesCount = Invoice::where('employee_id', Auth::user()->id)
         ->where('invoice_status', 3)
         ->count();
              

                $Invoices = Invoice::where('employee_id', Auth::user()->id)
                ->where('invoice_status', 3) 
                ->orderBy('invoice_date', 'desc')
                ->get();   
       

            return view('Dashboard.Employees.Invoices.compinvoice',compact('Invoices','activeInvoicesCount') );

    }


    public function show($id)
    {
      
            //اذن  
       
            $invoice = Invoice::with(['products'])->where('id', $id)->firstOrFail();
        
       
             return view('Dashboard.Employees.Invoices.invoicedateils',compact('invoice') );


    }

//---------------------------------------------------
public function edit($id)
{
    // جلب الفاتورة مع المنتجات المرتبطة والكمية من جدول invoice_products
    $Invoices = Invoice::with(['products' => function ($query) use ($id) {
        $query->select(
            'products.id',
            'products.product_name',
            'products.product_code',
            'ip.quantity' // الكمية من الجدول الوسيط
        )
        ->join('invoice_products as ip', 'products.id', '=', 'ip.product_id')
        ->where('ip.invoice_id', $id); // الربط بالفاتورة المحددة
    }])->findOrFail($id);

    // حساب الكمية الإجمالية من جدول invoice_products
    $totalQuantity = DB::table('invoice_products')
        ->where('invoice_id', $id)
        ->sum('quantity');

    // ترتيب البيانات المرتبطة بالفاتورة والمنتجات
    $invoiceProducts = $Invoices->products->map(function ($product) {
        return [
            'id' => $product->id,
            'product_code' => $product->product_code ?? '', // استخدم `product_code` بدلاً من `serial_prefix`
        ];
    });
    

    // إرسال البيانات إلى الـ View
    return view('Dashboard.Employees.Invoices.addserial', compact('Invoices', 'totalQuantity', 'invoiceProducts'));
}





///-----------------------------------------------

public function store(Request $request) {


    
    try {
        $request->validate([
            'id' => 'required|string|max:255', // Invoice ID
            'serials' => 'required|string',    // Serials
        ]);

        $invoiceId = $request->input('id');
        $serials = $request->input('serials');

        // تقسيم السيريالات إلى مصفوفة بعد تنظيفها
        $serialsArray = array_filter(array_map('trim', explode("\n", $serials)));

        // إحضار السيريالات الحالية لهذا الفاتورة
        $existingSerials = SerialNumber::where('invoice_id', $invoiceId)
            ->pluck('serial_number')
            ->toArray();

        $successfulSerials = [];
        $failedSerials = [];

        foreach ($serialsArray as $serial) {
            // تخطي إذا كان السيريال موجود بالفعل
            if (in_array($serial, $existingSerials)) {
                continue;
            }

            try {
                // حفظ السيريال مباشرة دون التحقق من النوع أو الكمية
                SerialNumber::create([
                    'invoice_id' => $invoiceId,
                    'serial_number' => $serial,
                ]);

                $successfulSerials[] = $serial;

                if (!count($successfulSerials) == 0) {
                    $invoice = Invoice::findOrFail($invoiceId);
                    $invoice->invoice_status = 3; // Mark as completed
                    $invoice->save();
                }
            } catch (\Exception $e) {
                $failedSerials[] = $serial . ' (خطأ أثناء الحفظ)';
            }
        }

        // رسائل الفلاش
        if (!empty($successfulSerials)) {
            session()->flash('add');
        }

        if (!empty($failedSerials)) {
            return redirect()->back()->withErrors(['error' => $e->getMessage('warning', 'السيريالات التي لم يتم إدخالها: ' . implode(', ', $failedSerials))]);;
        }

        return redirect()->route('Dashboard.employee');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}

}

