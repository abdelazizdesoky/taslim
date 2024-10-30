<?php

namespace App\Http\Controllers\Dashboard;



use App\Models\Invoice;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\Customers;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Product;

class InvoicesController extends Controller
{
    //-------------------------------------------------------------------------------------------------------
public function index()
{
    $Invoices = Invoice::with(['supplier', 'customer', 'admin', 'location', 'serialNumbers'])
    ->orderBy('invoice_date', 'desc')
    ->get();

    return view('Dashboard.Admin.Invoices.index',compact('Invoices'));
}
//-------------------------------------------------------------------------------------------------------
public function create()
{
    $customers = Customers::select('id', 'name')->where('status', 1)->get();
    $suppliers = Supplier::select('id', 'name')->where('status', 1)->get();

    // دمج الاثنين في قائمة واحدة
    $contacts = $customers->map(function($customer) {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'type' => 'customer',
        ];
    })->merge(
        $suppliers->map(function($supplier) {
            return [
                'id' => $supplier->id,
                'name' => $supplier->name, // تم تصحيح هنا
                'type' => 'supplier',
            ];
        })
    );

    $admins = Admin::whereIn('permission', [3,4])->get();
    $locations = Location::all();

    return view('Dashboard.Admin.Invoices.create', compact('contacts', 'admins', 'locations','customers','suppliers'));
}



public function store(request $request)
{
    $request->validate([
        'code' => 'required|unique:invoices,code',
        'invoice_date' => 'required',
        'invoice_type' => 'required',
        'employee_id' => 'required|exists:admins,id',
    ]);

    DB::beginTransaction();

    try {
        $invoice = new Invoice();
        $invoice->code = $request->code;
        $invoice->invoice_date = Carbon::createFromFormat('m/d/Y', $request->input('invoice_date'))->format('Y-m-d');
        $invoice->invoice_type = $request->invoice_type;
        $invoice->employee_id = $request->employee_id;
        $invoice->location_id = $request->location_id;


        if ($request->invoice_type == 1) {
            // استلام (Receiving)
            $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
            ]);
            $invoice->supplier_id = $request->supplier_id;
            $invoice->customer_id = null; // إفراغ حقل العميل
        } elseif ($request->invoice_type == 2) {    
            // تسليم (Delivery)
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
            ]);
            $invoice->customer_id = $request->customer_id;
            $invoice->supplier_id = null; // إفراغ حقل المورد
        } elseif ($request->invoice_type == 3) {
            // مرتجع
            $request->validate([
                'contact_id' => 'required',
                'contact_type' => 'required|in:customer,supplier',
            ]);
        
            if ($request->contact_type == 'customer') {
                $invoice->customer_id = $request->contact_id;
                $invoice->supplier_id = null; // إفراغ حقل المورد
            } elseif ($request->contact_type == 'supplier') {
                $invoice->supplier_id = $request->contact_id;
                $invoice->customer_id = null; // إفراغ حقل العميل
            }
        }

        $invoice->save();

        DB::commit();

        session()->flash('add');
        return redirect()->route('admin.invoices.index');

    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }


}

//-------------------------------------------------------------------------------------------------------
public function edit($id)
{
    $Invoices = Invoice::findorfail($id);
    $suppliers = Supplier::where('status', 1)->get();
    $customers = Customers::where('status', 1)->get();
    $admins = Admin::whereIn('permission', [3,4])->get();
    $locations = Location::all();

    // دمج العملاء والموردين في قائمة واحدة
    $contacts = $customers->map(function($customer) {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'type' => 'customer',
        ];
    })->merge(
        $suppliers->map(function($supplier) {
            return [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'type' => 'supplier',
            ];
        })
    );

    return view('Dashboard.Admin.Invoices.edit', compact('Invoices', 'suppliers', 'customers', 'admins', 'locations', 'contacts'));
}

public function update(Request $request, $id)
{
    $invoice = Invoice::findOrFail($id);

    // التحقق من صحة البيانات المدخلة
    $validatedData = $request->validate([
        'invoice_date' => 'required',
        'invoice_type' => 'required|in:1,2,3',
        'employee_id' => 'required|exists:admins,id',
        'invoice_status' => 'required',
        'location_id' => 'required',
    
    ]);

    try {
        // تنسيق تاريخ الفاتورة
        $carbonDate = Carbon::parse($validatedData['invoice_date']);
        $newInvoiceDate = $carbonDate->format('Y-m-d');

        $updateData = [
            'invoice_type' => $validatedData['invoice_type'],
            'employee_id' => $validatedData['employee_id'],
            'invoice_status' => $validatedData['invoice_status'],
            'location_id' => $validatedData['location_id'],
        ];

        // التحقق إذا كانت الفاتورة من نوع استلام
        if ($request->invoice_type == 1) {
            $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
            ]);
            $invoice->supplier_id = $request->supplier_id;
            $invoice->customer_id = null;
        } 
        // التحقق إذا كانت الفاتورة من نوع تسليم
        elseif ($request->invoice_type == 2) {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
            ]);
            $invoice->customer_id = $request->customer_id;
            $invoice->supplier_id = null;
        } 
        // التحقق إذا كانت الفاتورة من نوع مرتجعات
        elseif ($request->invoice_type == 3) {
            $request->validate([
                'contact_id' => 'required',
                'contact_type' => 'required|in:customer,supplier',
            ]);

            // إذا كان العميل هو المرتجع
            if ($request->contact_type == 'customer') {
                $request->validate([
                    'contact_id' => 'exists:customers,id',
                ]);
                $invoice->customer_id = $request->contact_id;
                $invoice->supplier_id = null;
            } 
            // إذا كان المورد هو المرتجع
            else {
                $request->validate([
                    'contact_id' => 'exists:suppliers,id',
                ]);
                $invoice->supplier_id = $request->contact_id;
                $invoice->customer_id = null;
            }
        }

        // التحقق من تحديث تاريخ الفاتورة
        if ($newInvoiceDate !== $invoice->invoice_date) {
            $updateData['invoice_date'] = $newInvoiceDate;
        }

        // تحديث بيانات الفاتورة
        $invoice->update($updateData);

        session()->flash('edit');
        return redirect()->route('admin.invoices.index');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}


//-------------------------------------------------------------------------------------------------------
public function cancel(Request $request)
{
    try {
   
        $invoice = Invoice::findOrFail($request->id);
      
        $invoice->invoice_status = 5;


        $invoice->save();

        session()->flash('edit');
        return redirect()->route('admin.invoices.index');

    }
    catch (\Exception $e) 
    {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }

}

//-------------------------------------------------------------------------------------------------------
public function destroy(request $request)
{
    Invoice::destroy($request->id);
    session()->flash('delete');
    return redirect()->back();
}



//-------------------------------------------------------------------------------------------------------
public function show($id)
{
    $invoice = Invoice::findOrFail($id);

    // استرجاع السيريالات المرتبطة بالفاتورة
    $serials = SerialNumber::where('invoice_id', $id)->get();

    // تجميع السيريالات حسب المنتج
    $serialsGroupedByProduct = $serials->groupBy(function ($serial) {
        $serialPrefix = substr($serial->serial_number, 0, 6); // استخراج أول 6 أرقام
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

    return view('Dashboard.Admin.Invoices.showinvoice', compact('invoice', 'serials', 'productSerialCounts'));
}



//-------------------------------------------------------------------------------------------------------
}
