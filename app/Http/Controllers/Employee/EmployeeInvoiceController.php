<?php

namespace App\Http\Controllers\Employee;

use App\Models\Invoice;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class EmployeeInvoiceController extends Controller
{
    public function index()
    {

    


    }

    public function show($id)
    {
      
            //اذن  
       
            $invoice = Invoice::with(['products'])->where('id', $id)->firstOrFail();
        
       
             return view('Dashboard.Employees.Invoices.invoicedateils',compact('invoice') );


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



//---------------------------------------------------

    public function edit($id)
    {
        $Invoices = Invoice::findorfail($id);

        return view('Dashboard.Employees.Invoices.addserial',compact('Invoices'));
    }

///-------------------------------------------------------------

  
        public function store(Request $request)
        {
            try {
                $request->validate([
                    'id' => 'required|string|max:255', // رقم الفاتورة
                    'serials' => 'required|string', // السيريالات
                ]);
        
                $invoiceId = $request->input('id');
                $serials = $request->input('serials');
        
                // جلب السيريالات كمصفوفة مع تنقيتها
                $serialsArray = array_filter(array_map('trim', explode("\n", $serials)));
        
                // جلب الفاتورة ومنتجاتها
                $invoice = Invoice::with('products')->findOrFail($invoiceId);
        
                // التحقق من تطابق السيريالات مع المنتجات
                foreach ($serialsArray as $serial) {
                    // تحقق إذا كان السيريال موجودًا مسبقًا في نفس الفاتورة
                    $existingSerial = SerialNumber::where('invoice_id', $invoiceId)
                                                  ->where('serial_number', $serial)
                                                  ->exists();
        
                    if ($existingSerial) {
                        // السيريال مكرر، تجاهله أو أرسل رسالة
                       // return redirect()->back()->withErrors(['error' => "السيريال $serial مكرر بالفعل."]);
                    }
        
                    // تحقق من أن المنتج مرتبط بالفاتورة
                    $productFound = false;

                    $productCode = substr($serial, 0, 7);

                    foreach ($invoice->products as $product) {
                        // افترض أن السيريال يحتوي على كود المنتج بشكل مباشر
                        if ($productCode == $product->product_code) {
                            $productFound = true;
        
                            // تحقق من الكمية المسموحة
                            $usedSerialsCount = SerialNumber::where('invoice_id', $invoiceId)
                                                            ->where('product_id', $product->id)
                                                            ->count();
        
                            if ($usedSerialsCount >= $product->pivot->quantity) {
                                return redirect()->back()->withErrors(['error' => "السيريالات للمنتج {$product->product_name} تجاوزت الكمية المسموحة."]);
                            }
        
                            // أضف السيريال
                            SerialNumber::create([
                                'invoice_id' => $invoiceId,
                                'serial_number' => $serial,
                            ]);
                        }
                    }
        
                    if (!$productFound) {
                        // المنتج غير موجود في الفاتورة
                        return redirect()->back()->withErrors(['error' => "السيريال $serial لا ينتمي إلى أي منتج في الفاتورة."]);
                    }
                }
        
                // تحديث حالة الفاتورة
                $invoice->invoice_status = 3;
                $invoice->save();
        
                session()->flash('add');
                return redirect()->route('Dashboard.employee');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
        }
        
        public function checkSerial($invoiceId, $serial)
        {
            // جلب الفاتورة مع المنتجات المرتبطة
            $invoice = Invoice::with('products')->findOrFail($invoiceId);
        
            // تحقق إذا كان السيريال يحتوي على كود منتج مرتبط بالفاتورة
            foreach ($invoice->products as $product) {
                if (str_contains($serial, $product->product_code)) {
                    // تحقق من الكمية
                    $usedSerialsCount = SerialNumber::where('invoice_id', $invoiceId)
                                                    ->where('product_id', $product->id)
                                                    ->count();
        
                    if ($usedSerialsCount < $product->pivot->quantity) {
                        return response()->json(['valid' => true]);
                    } else {
                        return response()->json(['valid' => false, 'message' => "الكمية المطلوبة للمنتج {$product->product_name} قد تم تجاوزها."]);
                    }
                }
            }
        
            return response()->json(['valid' => false, 'message' => 'السيريال غير مرتبط بأي منتج في الفاتورة.']);
        }
        


  
}

