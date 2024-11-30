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
    // جلب الفاتورة مع المنتجات المرتبطة
    $Invoices = Invoice::with('products')->findOrFail($id);
    
   $totalQuantity = DB::table('invoice_products')
    ->where('invoice_id', $id)
    ->sum('quantity');

    return view('Dashboard.Employees.Invoices.addserial', compact('Invoices','totalQuantity'));
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

        // Clean and filter serials
        $serialsArray = array_filter(array_map('trim', explode("\n", $serials)));

        // Fetch invoice products with related products
        $invoiceProducts = InvoiceProduct::with('product')
                            ->where('invoice_id', $invoiceId)
                            ->get();

        if ($invoiceProducts->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'الفاتورة غير موجودة أو لا تحتوي على منتجات.']);
        }

        // Fetch existing serials for this invoice
        $existingSerials = SerialNumber::where('invoice_id', $invoiceId)
                                       ->pluck('serial_number')
                                       ->toArray();

        // Tracking product serial counts
        $productSerialCounts = [];

        // Pre-populate product serial counts with existing serials
        foreach ($existingSerials as $existingSerialsForProduct) {
            $serialNumber = ltrim($existingSerialsForProduct, '0');
            $serialPrefix = substr($serialNumber, 0, 7);

            foreach ($invoiceProducts as $invoiceProduct) {
                $product = $invoiceProduct->product;
                if ($product && $serialPrefix === $product->product_code) {
                    if (!isset($productSerialCounts[$product->id])) {
                        $productSerialCounts[$product->id] = 0;
                    }
                    $productSerialCounts[$product->id]++;
                    break;
                }
            }
        }

        $successfulSerials = [];
        $failedSerials = [];

        foreach ($serialsArray as $serial) {
            // Skip if serial already exists
            if (in_array($serial, $existingSerials)) {
                continue;
            }

            // Extract first 7 digits for product code matching
            $serialNumber = ltrim($serial, '0');
            $serialPrefix = substr($serialNumber, 0, 7);

            $productFound = false;
            foreach ($invoiceProducts as $invoiceProduct) {
                $product = $invoiceProduct->product;

                if ($product && $serialPrefix === $product->product_code) {
                    // Initialize product serial count if not exists
                    if (!isset($productSerialCounts[$product->id])) {
                        $productSerialCounts[$product->id] = 0;
                    }

                    // Check if we've exceeded the allowed quantity for this product
                    if ($productSerialCounts[$product->id] < $invoiceProduct->quantity) {
                        // Add serial number
                        SerialNumber::create([
                            'invoice_id' => $invoiceId,
                            'serial_number' => $serial,
                        ]);

                        // Increment product serial count
                        $productSerialCounts[$product->id]++;
                        $productFound = true;
                        $successfulSerials[] = $serial;
                        break;
                    } else {
                        $failedSerials[] = $serial . ' (تجاوز الكمية المسموحة)';
                        break;
                    }
                }
            }

            if (!$productFound) {
                $failedSerials[] = $serial . ' (لا يتطابق مع أي منتج)';
            }
        }

        // Check if all products have their required number of serials
        $incompleteProducts = [];
        foreach ($invoiceProducts as $invoiceProduct) {
            $requiredQuantity = $invoiceProduct->quantity;
            $currentQuantity = $productSerialCounts[$invoiceProduct->product_id] ?? 0;

            if ($currentQuantity < $requiredQuantity) {
                $incompleteProducts[] = "{$invoiceProduct->product->product_name} (مدخل:  المطلوب: $requiredQuantity)";
            }
        }

        // Update invoice status if all serials are added
        if (count($incompleteProducts) == 0) {
            $invoice = Invoice::findOrFail($invoiceId);
            $invoice->invoice_status = 3; // Mark as completed
            $invoice->save();
        }

        // Prepare flash messages
        if (!empty($successfulSerials)) {
            session()->flash('add', 'تم إضافة السيريالات: ' . implode(', ', $successfulSerials));
        }

        if (!empty($failedSerials)) {
            session()->flash('warning', 'السيريالات التي لم يتم إدخالها: ' . implode(', ', $failedSerials));
        }

        if (!empty($incompleteProducts)) {
            session()->flash('error', 'المنتجات غير المكتملة: ' . implode(', ', $incompleteProducts));
        }

        return redirect()->route('Dashboard.employee');

    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}
///-------------------------------------------------------------

// public function store(Request $request) {
//     try {
//         $request->validate([
//             'id' => 'required|string|max:255', // Invoice ID
//             'serials' => 'required|string',    // Serials
//         ]);

//         $invoiceId = $request->input('id');
//         $serials = $request->input('serials');

//         // Clean and filter serials
//         $serialsArray = array_filter(array_map('trim', explode("\n", $serials)));

//         // Fetch invoice products with related products
//         $invoiceProducts = InvoiceProduct::with('product')
//                             ->where('invoice_id', $invoiceId)
//                             ->get();

//         if ($invoiceProducts->isEmpty()) {
//             return redirect()->back()->withErrors(['error' => 'الفاتورة غير موجودة أو لا تحتوي على منتجات.']);
//         }

//         // Tracking product serial counts
//         $productSerialCounts = [];

//         foreach ($serialsArray as $serial) {
//             // Check if serial is already used in this invoice
//             $existingSerial = SerialNumber::where('invoice_id', $invoiceId)
//                                           ->where('serial_number', $serial)
//                                           ->exists();

//             if ($existingSerial) {
//                 return redirect()->back()->withErrors(['error' => "السيريال $serial مكرر بالفعل في هذه الفاتورة."]);
//             }

//             // Check if serial is used in any invoice's database
//             // $globalExistingSerial = SerialNumber::where('serial_number', $serial)->exists();
//             // if ($globalExistingSerial) {
//             //     return redirect()->back()->withErrors(['error' => "السيريال $serial مستخدم بالفعل في فاتورة أخرى."]);
//             // }

//             // Extract first 7 digits for product code matching
//             $serialNumber = ltrim($serial, '0');
//             $serialPrefix = substr($serialNumber, 0, 7);

//             $productFound = false;
//             foreach ($invoiceProducts as $invoiceProduct) {
//                 $product = $invoiceProduct->product;

//                 if ($product && $serialPrefix === $product->product_code) {
//                     // Initialize product serial count if not exists
//                     if (!isset($productSerialCounts[$product->id])) {
//                         $productSerialCounts[$product->id] = 0;
//                     }

//                     // Check if we've exceeded the allowed quantity for this product
//                     if ($productSerialCounts[$product->id] < $invoiceProduct->quantity) {
//                         // Add serial number
//                         SerialNumber::create([
//                             'invoice_id' => $invoiceId,
//                             'serial_number' => $serial,
//                         ]);

//                         // Increment product serial count
//                         $productSerialCounts[$product->id]++;
//                         $productFound = true;
//                         break;
//                     } else {
//                         return redirect()->back()->withErrors([
//                             'error' => "تم تجاوز الكمية المسموحة للمنتج {$product->product_name}. الكمية المحددة: {$invoiceProduct->quantity}"
//                         ]);
//                     }
//                 }
//             }

//             if (!$productFound) {
//                 return redirect()->back()->withErrors([
//                     'error' => "السيريال $serial لا يتطابق مع أي منتج في الفاتورة. تأكد من أن أول 7 أرقام من السيريال تطابق كود المنتج."
//                 ]);
//             }
//         }

//         // Check if all products have their required number of serials
//         foreach ($invoiceProducts as $invoiceProduct) {
//             $requiredQuantity = $invoiceProduct->quantity;
//             $currentQuantity = $productSerialCounts[$invoiceProduct->product_id] ?? 0;

//             if ($currentQuantity < $requiredQuantity) {
//                 return redirect()->back()->withErrors([
//                     'error' => "لم يتم إدخال جميع السيريالات للمنتج {$invoiceProduct->product->product_name}. المطلوب:  تم إدخال: $currentQuantity"
//                 ]);
//             }
//         }

//         // Update invoice status
//         $invoice = Invoice::findOrFail($invoiceId);
//         $invoice->invoice_status = 3; // Mark as completed
//         $invoice->save();

//         session()->flash('add', 'تم إضافة السيريالات بنجاح');
//         return redirect()->route('Dashboard.employee');

//     } catch (\Exception $e) {
//         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
//     }
// }

  
}

