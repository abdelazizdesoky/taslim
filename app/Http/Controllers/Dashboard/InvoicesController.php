<?php

namespace App\Http\Controllers\Dashboard;


use Log;
use App\Models\Invoice;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\Customers;
use App\Models\ProductCode;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product;

class InvoicesController extends Controller
{
        //-------------------------------------------------------------------------------------------------------
    public function index()
    {
        $Invoices = Invoice::orderBy('invoice_date', 'desc') ->get();
      
   
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
    
        $employees = Employee::where('status', 1)->get();
        $locations = Location::all();
    
        return view('Dashboard.Admin.Invoices.create', compact('contacts', 'employees', 'locations','customers','suppliers'));
    }
    

    //-------------------------------------------------------------------------------------------------------
    public function show($id)
    {
        
        $invoice = Invoice::findOrFail($id);
    
        $serials = SerialNumber::where('invoice_id', $id)->get();

                    // احصل على السيريالات وقم بتجميعها حسب المنتج
            $serialsGroupedByProduct = $serials->groupBy(function ($serial) {
                $serialPrefix = substr($serial->serial_number, 0, 7);
                $productCode = ProductCode::where('product_code', $serialPrefix)->first();
                return $productCode ? $productCode->product->id : null;
            });

            // حساب عدد السيريالات لكل منتج
            $productSerialCounts = [];
            foreach ($serialsGroupedByProduct as $productId => $groupedSerials) {
                if ($productId) {
                    $product = Product::find($productId);
                    $productSerialCounts[] = [
                        'product_name' =>$product->productType->type_name ." ".$product->productType->brand->brand_name." ". $product->product_name,
                        'serial_count' => $groupedSerials->count()
                    ];
                }
            }

     

    
        return view('Dashboard.Admin.Invoices.showinvoice', compact('invoice', 'serials','productSerialCounts'));
    }


    //-------------------------------------------------------------------------------------------------------
    public function store(request $request)
    {
        $request->validate([
            'code' => 'required|unique:invoices,code',
            'invoice_date' => 'required',
            'invoice_type' => 'required',
            'employee_id' => 'required|exists:employees,id',
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
            return redirect()->route('Invoices.index');

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
        $employees = Employee::where('status', 1)->get();
        $locations = Location::all();
        return view('Dashboard.Admin.Invoices.edit',compact('Invoices','suppliers','customers','employees','locations'));
    }




    //-------------------------------------------------------------------------------------------------------
    public function update(Request $request, $id)
    {
        // العثور على السجل المحدد
        $invoice = Invoice::findOrFail($id);
    
        // التحقق من صحة البيانات
        $validatedData = $request->validate([
            'invoice_date' => 'required', // تغيير القاعدة لتكون أكثر مرونة
            'invoice_type' => 'required|in:1,2',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'customer_id' => 'nullable|exists:customers,id',
            'employee_id' => 'required|exists:employees,id',
            'invoice_status' => 'required',
            'location_id' => 'required',
        ]);
    
        try {
            // محاولة تحليل التاريخ بغض النظر عن الصيغة
            $carbonDate = Carbon::parse($validatedData['invoice_date']);
            $newInvoiceDate = $carbonDate->format('Y-m-d');
    
            // التحقق مما إذا كان التاريخ الجديد مختلفًا عن التاريخ الحالي
            $dateChanged = $newInvoiceDate !== $invoice->invoice_date;
    
            // إعداد بيانات التحديث
            $updateData = [
                'invoice_type' => $validatedData['invoice_type'],
                'supplier_id' => $validatedData['invoice_type'] == 1 ? $validatedData['supplier_id'] : null,
                'customer_id' => $validatedData['invoice_type'] == 2 ? $validatedData['customer_id'] : null,
                'employee_id' => $validatedData['employee_id'],
                'invoice_status' => $validatedData['invoice_status'],
                'location_id' => $validatedData['location_id'],
            ];
    
            // تحديث التاريخ فقط إذا تغير
            if ($dateChanged) {
                $updateData['invoice_date'] = $newInvoiceDate;
            }
    
            // تحديث الفاتورة
            $invoice->update($updateData);
            session()->flash('add');
            return redirect()->route('Invoices.index');

    
            return redirect()->route('Invoices.index');
        } catch (\Exception $e) {
            // تسجيل الخطأ
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            
            // إعادة التوجيه مع رسالة خطأ
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث الفاتورة. الرجاء التأكد من صحة التاريخ المدخل.');
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
            return redirect()->route('Invoices.index');

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
}
