<?php

namespace App\Http\Controllers\Dashboard;



use App\Models\Admin;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\Customers;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
    $products = Product::all(); // جلب المنتجات المتاحة
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

    return view('Dashboard.Admin.Invoices.create', compact('contacts', 'admins', 'locations','customers','suppliers','products'));
}

//-----------------------------------
public function store(Request $request)
{
    $request->validate([
        'code' => 'required|unique:invoices,code',
        'invoice_date' => 'required',
        'invoice_type' => 'required',
        'employee_id' => 'required|exists:admins,id',
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    DB::beginTransaction();

    try {
        // إنشاء الفاتورة
        $invoice = new Invoice();
        $invoice->code = $request->code;
        $invoice->invoice_date = Carbon::createFromFormat('m/d/Y', $request->invoice_date)->format('Y-m-d');
        $invoice->invoice_type = $request->invoice_type;
        $invoice->employee_id = $request->employee_id;
        $invoice->created_by = Auth::user()->id;
        $invoice->location_id = $request->location_id;

        // تعيين المورد أو العميل حسب نوع الفاتورة
        if ($request->invoice_type == 1) {
            $request->validate(['supplier_id' => 'required|exists:suppliers,id']);
            $invoice->supplier_id = $request->supplier_id;
        } elseif ($request->invoice_type == 2) {
            $request->validate(['customer_id' => 'required|exists:customers,id']);
            $invoice->customer_id = $request->customer_id;
        } elseif ($request->invoice_type == 3) {
            $request->validate([
                'contact_id' => 'required',
                'contact_type' => 'required|in:customer,supplier'
            ]);
            $invoice->contact_type = $request->contact_type;
            $invoice->contact_id = $request->contact_id;
        }

        $invoice->save();

        // حفظ المنتجات
        $productsData = json_decode($request->products_data, true);

        if (!empty($productsData)) {
            foreach ($productsData as &$productData) {
                $productData['invoice_id'] = $invoice->id;
            }
            InvoiceProduct::insert($productsData);
        }

        DB::commit();

        session()->flash('add', 'تم حفظ الفاتورة بنجاح');
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
    $creators = Admin::whereIn('permission', [1,2])->get();
    $locations = Location::all();
    $invoiceProducts= InvoiceProduct::where('invoice_id', $id )->get();
    $allProducts = Product::all(); // جلب المنتجات المتاحة
  
  
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

    return view('Dashboard.Admin.Invoices.edit', compact('Invoices', 'suppliers', 'customers', 'admins', 'locations', 'contacts','creators','invoiceProducts','allProducts'));
}


//--------------------------------------------------


public function update(Request $request, $id)
{
    $invoice = Invoice::findOrFail($id);

    // Validate input data
    $validatedData = $request->validate([
        'invoice_date' => 'required',
        'invoice_type' => 'required|in:1,2,3',
        'employee_id' => 'required|exists:admins,id',
        'created_by' => 'required|exists:admins,id',
        'invoice_status' => 'required',
        'location_id' => 'required',
        'products' => 'required|array|min:1',
        'quantities' => 'required|array|min:1',
        'products.*' => 'required|exists:products,id',
        'quantities.*' => 'required|numeric|min:1',
    ]);

    try {
        DB::beginTransaction();

        // Format invoice date
        $carbonDate = Carbon::parse($validatedData['invoice_date']);
        $newInvoiceDate = $carbonDate->format('Y-m-d');

        $updateData = [
            'invoice_type' => $validatedData['invoice_type'],
            'employee_id' => $validatedData['employee_id'],
            'created_by' => $validatedData['created_by'],
            'invoice_status' => $validatedData['invoice_status'],
            'location_id' => $validatedData['location_id'],
        ];

        // Handle invoice type specific validation and updates
        if ($request->invoice_type == 1) {
            $request->validate(['supplier_id' => 'required|exists:suppliers,id']);
            $updateData['supplier_id'] = $request->supplier_id;
            $updateData['customer_id'] = null;
        } elseif ($request->invoice_type == 2) {
            $request->validate(['customer_id' => 'required|exists:customers,id']);
            $updateData['customer_id'] = $request->customer_id;
            $updateData['supplier_id'] = null;
        } elseif ($request->invoice_type == 3) {
            $request->validate([
                'contact_id' => 'required',
                'contact_type' => 'required|in:customer,supplier',
            ]);

            if ($request->contact_type == 'customer') {
                $request->validate(['contact_id' => 'exists:customers,id']);
                $updateData['customer_id'] = $request->contact_id;
                $updateData['supplier_id'] = null;
            } else {
                $request->validate(['contact_id' => 'exists:suppliers,id']);
                $updateData['supplier_id'] = $request->contact_id;
                $updateData['customer_id'] = null;
            }
        }

        // Update invoice date if changed
        if ($newInvoiceDate !== $invoice->invoice_date) {
            $updateData['invoice_date'] = $newInvoiceDate;
        }

        // Update invoice
        $invoice->update($updateData);

        // Get existing products
        $existingProducts = InvoiceProduct::where('invoice_id', $invoice->id)
            ->pluck('quantity', 'product_id')
            ->toArray();

        // Prepare new products data
        $newProducts = [];
        foreach ($request->products as $index => $productId) {
            $newProducts[$productId] = [
                'quantity' => $request->quantities[$index],
                'processed' => false
            ];
        }

        // Process products
        foreach ($newProducts as $productId => $data) {
            if (isset($existingProducts[$productId])) {
                // Update existing product if quantity changed
                if ($existingProducts[$productId] != $data['quantity']) {
                    InvoiceProduct::where('invoice_id', $invoice->id)
                        ->where('product_id', $productId)
                        ->update(['quantity' => $data['quantity']]);
                }
                unset($existingProducts[$productId]);
            } else {
                // Add new product
                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $productId,
                    'quantity' => $data['quantity']
                ]);
            }
        }

        // Remove products that are no longer in the invoice
        if (!empty($existingProducts)) {
            InvoiceProduct::where('invoice_id', $invoice->id)
                ->whereIn('product_id', array_keys($existingProducts))
                ->delete();
        }

        DB::commit();
        session()->flash('edit');
        return redirect()->route('admin.invoices.index');

    } catch (\Exception $e) {
        DB::rollBack();
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

    return view('Dashboard.Admin.Invoices.showinvoice', compact('invoice', 'serials', 'productSerialCounts'));
}



//-------------------------------------------------------------------------------------------------------
}
/*
public function update(Request $request, $id)
{
    try {
        DB::beginTransaction();
        
        $invoice = Invoice::findOrFail($id);

        // التحقق من البيانات
        $validatedData = $request->validate([
            'invoice_date' => 'required',
            'invoice_type' => 'required|in:1,2,3',
            'employee_id' => 'required|exists:admins,id',
            'created_by' => 'required|exists:admins,id',
            'invoice_status' => 'required',
            'location_id' => 'required',
            'products' => 'required|array|min:1',
            'quantities' => 'required|array|min:1',
            'products.*' => 'required|exists:products,id',
            'quantities.*' => 'required|numeric|min:1',
        ]);

        // تنسيق تاريخ الفاتورة
        $carbonDate = Carbon::parse($validatedData['invoice_date']);
        $newInvoiceDate = $carbonDate->format('Y-m-d');

        // تحديث بيانات الفاتورة الأساسية
        $updateData = [
            'invoice_date' => $newInvoiceDate,
            'invoice_type' => $validatedData['invoice_type'],
            'employee_id' => $validatedData['employee_id'],
            'created_by' => $validatedData['created_by'],
            'invoice_status' => $validatedData['invoice_status'],
            'location_id' => $validatedData['location_id'],
        ];

        // معالجة نوع الفاتورة
        switch ($request->invoice_type) {
            case '1': // مورد
                $updateData['supplier_id'] = $request->supplier_id;
                $updateData['customer_id'] = null;
                break;
            case '2': // عميل
                $updateData['customer_id'] = $request->customer_id;
                $updateData['supplier_id'] = null;
                break;
            case '3': // مرتجعات
                if ($request->contact_type == 'customer') {
                    $updateData['customer_id'] = $request->contact_id;
                    $updateData['supplier_id'] = null;
                } else {
                    $updateData['supplier_id'] = $request->contact_id;
                    $updateData['customer_id'] = null;
                }
                break;
        }

        // تحديث الفاتورة
        $invoice->update($updateData);

        // حذف جميع المنتجات القديمة
        InvoiceProduct::where('invoice_id', $invoice->id)->delete();

        // إضافة المنتجات الجديدة
        foreach ($request->products as $index => $productId) {
            InvoiceProduct::create([
                'invoice_id' => $invoice->id,
                'product_id' => $productId,
                'quantity' => $request->quantities[$index],
            ]);
        }

        DB::commit();
        session()->flash('edit', 'تم تحديث الفاتورة بنجاح');
        return redirect()->route('admin.invoices.index');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}


document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        // تأكد من وجود منتجات
        const products = document.querySelectorAll('select[name="products[]"]');
        const quantities = document.querySelectorAll('input[name="quantities[]"]');
        
        if (products.length === 0) {
            e.preventDefault();
            alert('يجب إضافة منتج واحد على الأقل');
            return false;
        }

        // التحقق من البيانات قبل الإرسال
        let isValid = true;
        products.forEach((product, index) => {
            if (!product.value || !quantities[index].value || quantities[index].value < 1) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('يرجى التأكد من اختيار المنتج وإدخال كمية صحيحة لجميع المنتجات');
            return false;
        }
    });

    // تأكد من تهيئة select2 بشكل صحيح
    $('.select2').select2({
        width: '100%',
        placeholder: 'اختر...',
        allowClear: true
    });
});


*/