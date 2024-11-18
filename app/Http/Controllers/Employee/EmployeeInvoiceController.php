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
    public function show($id)
    {

     
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

              
    }



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




    public function edit($id)
    {
        $Invoices = Invoice::findorfail($id);

        return view('Dashboard.Employees.Invoices.addserial',compact('Invoices'));
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string|max:255',
                'serials' => 'required|string',
            ]);
    
            $invoiceid = $request->input('id');
            $serials = $request->input('serials');
    
            // تحويل السيريالات إلى مصفوفة بعد تنقيتها
            $serialsArray = array_filter(array_map('trim', explode("\n", $serials)));
    
            foreach ($serialsArray as $serial) {
                // تحقق إذا كان السيريال مكررًا في نفس الفاتورة
                $existingSerial = SerialNumber::where('invoice_id', $invoiceid)
                                              ->where('serial_number', $serial)
                                              ->exists();
    
                if (!$existingSerial) {
                    SerialNumber::create([
                        'invoice_id' => $invoiceid,
                        'serial_number' => $serial,
                    ]);
                } else {
                    // يمكنك تجاهل السيريال المكرر أو إضافة رسالة خطأ مخصصة إذا رغبت
                 // return redirect()->back()->withErrors(['error' => " $serial مكرر "]);
                }
            }
    
            $invoice = Invoice::findOrFail($invoiceid);
            $invoice->invoice_status = 3;
            $invoice->save();
    
            session()->flash('add');
            return redirect()->route('Dashboard.employee');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    

  
}

