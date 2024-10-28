<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Invoice;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SerialNumberController extends Controller
{


    public function  index(){

     return view('Dashboard.Admin.Serial.index');
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
                    return view('Dashboard.Admin.Serial.show', compact('invoices', 'query'));
                } else {
                    return view('Dashboard.Admin.Serial.show')->with('message', 'لا توجد فواتير مرتبطة بهذا السيريال.');
                }
            } else {
                return view('Dashboard.Admin.Serial.show')->with('message', 'لا توجد فواتير مرتبطة بهذا السيريال.');
            }
        } else {
            return view('Dashboard.Admin.Serial.show')->with('message', 'لم يتم العثور على السيريال.');
        }
    }
    

}
