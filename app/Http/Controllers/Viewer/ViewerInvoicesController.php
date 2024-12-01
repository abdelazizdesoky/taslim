<?php

namespace App\Http\Controllers\Viewer;



use App\Models\Invoice;
use App\Models\Product;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ViewerInvoicesController extends Controller
{
        //-------------------------------------------------------------------------------------------------------
  
        public function index()
        {
            $Invoices = Invoice::with(['supplier', 'customer', 'admin', 'location', 'serialNumbers'])
                ->orderBy('invoice_date', 'desc')
                ->withCount('serialNumbers')
                ->get();
        
            return view('Dashboard.Viewer.Invoices.index', compact('Invoices'));
        }
        
  
    //-------------------------------------------------------------------------------------------------------
    public function create()
    {

        return view('Dashboard.Viewer.Serial.index');
    }



   
   public function searchInvoices(Request $request)

   {

   

       $query = $request->input('query');
   
       // البحث عن السيريالات المطابقة
       $serials = SerialNumber::where('serial_number', $query)->get();
   
       if ($serials->count() > 0) {
           // جلب معرفات الفواتير المرتبطة بالسيريالات
           $invoiceIds = $serials->pluck('invoice_id')->unique();
   
           // التأكد من وجود معرفات فواتير
           if ($invoiceIds->isNotEmpty()) {
               // جلب الفواتير
               $invoices = Invoice::whereIn('id', $invoiceIds)->get();
   
               // التأكد من وجود فواتير
               if ($invoices->count() > 0) {

                   return view('Dashboard.Viewer.Serial.show', compact('invoices', 'query'));

               } else {
                   return view('Dashboard.Viewer.Serial.show')->with('message', 'لا توجد فواتير مرتبطة بهذا السيريال.');
               }
           } else {
               return view('Dashboard.Viewer.Serial.show')->with('message', 'لا توجد فواتير مرتبطة بهذا السيريال.');
           }
       } else {
           return view('Dashboard.Viewer.Serial.show')->with('message', 'لم يتم العثور على السيريال.');
       }
   }
   






//-------------------------------------------------------------------------------------------------------
public function show($id)
{
    $invoice = Invoice::findOrFail($id);

    // استرجاع السيريالات المرتبطة بالفاتورة
    $serials = SerialNumber::where('invoice_id', $id)->get();

    // تجميع السيريالات حسب المنتج
    $serialsGroupedByProduct = $serials->groupBy(function ($serial) {
        $serialPrefix = substr($serial->serial_number, 0, 7); // استخراج أول 6 أرقام
        $product = \App\Models\Product::where('product_code', $serialPrefix)->first();
        return $product ? $product->id : null; // إرجاع معرف المنتج إذا وجد
    });

    // حساب عدد السيريالات لكل منتج
    $productSerialCounts = [];
    foreach ($serialsGroupedByProduct as $productId => $groupedSerials) {
        if ($productId) {
            $product = Product::find($productId);
            $productSerialCounts[] = [
                'product_name' => $product->productType->type_name . " " . $product->productType->brand->brand_name . " " . $product->product_name,
                'serial_count' => $groupedSerials->count()
            ];
        }
    }

    return view('Dashboard.Viewer.Invoices.showinvoice', compact('invoice', 'serials', 'productSerialCounts'));
}



//-------------------------------------------------------------------------------------------------------

public function viewProduct()
{

    $products = Product::with(['productType.brand'])->get();

    return view('Dashboard.Viewer.Product.index',compact('products'));

}


}
