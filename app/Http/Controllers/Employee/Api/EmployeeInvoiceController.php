<?php

namespace App\Http\Controllers\Employee\Api;

use App\Models\Invoice;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeInvoiceController extends Controller
{
    public function index()
{
    try {
        // الحصول على عدد الفواتير المفعلة
        $activeInvoicesCount = Invoice::where('employee_id', Auth::user()->id)
            ->where('invoice_status', 1)
            ->where('invoice_type', 2) // إضافة شرط لتكون الفاتورة من نوع "استلام"
            ->count();

        // جلب الفواتير
        $Invoices = Invoice::where('employee_id', Auth::user()->id)
            ->where('invoice_status', 1)
            ->where('invoice_type', 2)
            ->with(['supplier:id,name', 'customer:id,name','location:id,location_name']) 
            ->orderBy('invoice_date', 'desc')
            ->get();

        return response()->json([
            'activeInvoicesCount' => $activeInvoicesCount,
            'invoices' => $Invoices
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



public function show($id)
{
    try {
        // الحصول على عدد الفواتير المفعلة
        $activeInvoicesCount = Invoice::where('employee_id', Auth::user()->id)
            ->where('invoice_status', 1)
            ->where('invoice_type', $id)
            ->count();

        // جلب الفواتير
        $Invoices = Invoice::where('employee_id', Auth::user()->id)
            ->where('invoice_status', 1)
            ->where('invoice_type', $id)
            ->with(['supplier:id,name', 'customer:id,name','location:id,location_name']) 
            ->orderBy('invoice_date', 'desc')
            ->get();

        return response()->json([
            'activeInvoicesCount' => $activeInvoicesCount,
            'invoices' => $Invoices
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


public function Compinvoice()
{
    try {
        // الحصول على عدد الفواتير المكتملة
        $activeInvoicesCount = Invoice::where('employee_id', Auth::user()->id)
            ->where('invoice_status', 3)
            ->count();

        // جلب الفواتير المكتملة
        $Invoices = Invoice::where('employee_id', Auth::user()->id)
            ->where('invoice_status', 3)
            ->with(['supplier:id,name', 'customer:id,name','location:id,location_name']) 
            ->orderBy('invoice_date', 'desc')
            ->get();

        return response()->json([
            'activeInvoicesCount' => $activeInvoicesCount,
            'invoices' => $Invoices
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



public function store(Request $request)
{
    try {
        // التحقق من صحة المدخلات
        $request->validate([
            'id' => 'required|string|max:255',
            'serials' => 'required|string',
        ]);

        // الحصول على رقم الفاتورة والسيريالات
        $invoiceid = $request->input('id');
        $serials = $request->input('serials');

        // تقسيم السيريالات إلى مصفوفة
        $serialsArray = array_filter(array_map('trim', explode("\n", $serials)));

        // إدخال السيريالات في قاعدة البيانات
        foreach ($serialsArray as $serial) {
            SerialNumber::create([
                'invoice_id' => $invoiceid,
                'serial_number' => $serial,
            ]);
        }

        // تحديث حالة الفاتورة إلى مكتملة
        $invoice = Invoice::findOrFail($request->id);
        $invoice->invoice_status = 3;
        $invoice->save();

        return response()->json(['message' => 'Serial numbers added successfully.']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}
