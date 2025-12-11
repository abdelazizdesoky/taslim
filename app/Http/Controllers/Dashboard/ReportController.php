<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Admin;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customers;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class ReportController extends Controller
{
    public function index()


    {


        return view('Dashboard.Admin.Report.index');
    }






    public function generate(Request $request)
    {

        // التحقق من صحة البيانات
        $request->validate([
            'report_for' => 'required|string',
            'stutus' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);


        // تحديد الفترة الزمنية
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // تحديد نوع التقرير
        $reportFor = $request->report_for;
        $stutus = $request->stutus;
        if (!is_array($stutus)) {
            $stutus = [$stutus];
        }

        // إنشاء التقرير بناءً على الخيار المحدد
        switch ($reportFor) {
            case 'completed_invoices':
                $data = $this->getCompletedInvoices($startDate, $endDate, $stutus);
                $title = 'الاذن  المكتملة';
             
                break;

            case 'pending_invoices':
                $data = $this->getPendingInvoices($startDate, $endDate, $stutus);
                $title = 'الاذن  تحت التسليم';
               
                break;
                

            case 'canceled_invoices':
                $data = $this->getCanceledInvoices($startDate, $endDate, $stutus);
                $title = 'الاذن  الملغية';
              
                break;

            case 'reviers_invoices':
                $data = $this->getreversInvoices($startDate, $endDate, $stutus);
                $title = 'الاذن  المرتجعة';
               
                break;

            case 'customers':
                $data = $this->getCustomers($startDate, $endDate);
                $view = 'Dashboard.Admin.Report.customers';
                break;

            case 'suppliers':
                $data = $this->getSuppliers($startDate, $endDate);
                $view = 'Dashboard.Admin.Report.suppliers';
                break;

            case 'products':
                $data = $this->getProducts($startDate, $endDate);
                $view = 'Dashboard.Admin.Report.products';
                break;

            case 'representatives':
                $data = $this->getRepresentatives($startDate, $endDate);
                $view = 'Dashboard.Admin.Report.representatives';
                break;

            default:
                return redirect()->back()->with('error', 'نوع التقرير غير صحيح.');
        }

        // عرض التقرير
        return view('Dashboard.Admin.Report.reportinvoice', compact('data', 'title', 'startDate', 'endDate'));
    }

    // دالة لجلب الاذن  المكتملة
    private function getCompletedInvoices($startDate, $endDate, $status)
    {
        return Invoice::where('invoice_status', 3)
            ->whereIn('invoice_type', $status)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->with(['supplier', 'customer', 'admin', 'location'])
            ->withCount([
                'serialNumbers as total_serials_pulled', // عدد السيريالات المرتبطة
            ])
            ->withSum('products as products_sum_quantity', 'invoice_products.quantity') // مجموع الكميات المطلوبة من المنتجات
            ->orderBy('created_at', 'desc')
            ->get();
    }
    

    // دالة لجلب الاذن  تحت التسليم
    private function getPendingInvoices($startDate, $endDate,$stutus)
    {
        return Invoice::where('invoice_status', 1)
        ->whereIn('invoice_type',$stutus)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->with(['supplier', 'customer', 'admin', 'location', 'serialNumbers'])
            ->withCount([
                'serialNumbers as total_serials_pulled', // عدد السيريالات المرتبطة
            ])
            ->withSum('products as products_sum_quantity', 'invoice_products.quantity') // مجموع الكميات المطلوبة من المنتجات
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // دالة لجلب الاذن  الملغية
    private function getCanceledInvoices($startDate, $endDate,$stutus)
    {
        return Invoice::where('invoice_status', 5)
        ->whereIn('invoice_type',$stutus)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->with(['supplier', 'customer', 'admin', 'location', 'serialNumbers'])
            ->withCount([
                'serialNumbers as total_serials_pulled', // عدد السيريالات المرتبطة
            ])
            ->withSum('products as products_sum_quantity', 'invoice_products.quantity') // مجموع الكميات المطلوبة من المنتجات
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // دالة لجلب الاذن  مرتجع
    private function getreversInvoices($startDate, $endDate,$stutus)
    {
        return Invoice::where('invoice_status', 4)
        ->whereIn('invoice_type',$stutus)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->with(['supplier', 'customer', 'admin', 'location', 'serialNumbers'])
            ->withCount([
                'serialNumbers as total_serials_pulled', // عدد السيريالات المرتبطة
            ])
            ->withSum('products as products_sum_quantity', 'invoice_products.quantity') // مجموع الكميات المطلوبة من المنتجات
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // دالة لجلب العملاء
    private function getCustomers($startDate, $endDate)
    {
        return Customers::whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }

    // دالة لجلب الموردين
    private function getSuppliers($startDate, $endDate)
    {
        return Supplier::whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }

    // دالة لجلب المنتجات
    private function getProducts($startDate, $endDate)
    {
        return Product::whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }

    // دالة لجلب المندوبين
    private function getRepresentatives($startDate, $endDate)
    {
        return Admin::whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }

    // تقرير المخزون




    public function inventoryReport(Request $request)
    {
    
        $productSerialCounts = DB::table('products')
            ->select(
                'products.id as product_id',
                'products.product_name',
                DB::raw('COUNT(serial_numbers.id) as total_serial_count'),
                DB::raw('SUM(CASE WHEN invoices.invoice_type = 1 THEN 1 ELSE 0 END) as delivery_serial_count'),
                DB::raw('SUM(CASE WHEN invoices.invoice_type = 2 THEN 1 ELSE 0 END) as receipt_serial_count')
            )
            ->leftJoin('serial_numbers', function ($join) {
                $join->on(DB::raw('SUBSTRING(serial_numbers.serial_number, 1, 7)'), '=', 'products.product_code')
                    ->orWhere(DB::raw('SUBSTRING(serial_numbers.serial_number, 1, 7)'), '=', DB::raw('LPAD(products.product_code, 7, "0")'));
            })
            ->leftJoin('invoices', 'serial_numbers.invoice_id', '=', 'invoices.id') 
            ->groupBy('products.id', 'products.product_name')
            ->orderByDesc('total_serial_count')
            ->paginate(20);

        $totalSerials = DB::table('serial_numbers')->count();

      
        return view('Dashboard.Admin.Report.inventory', [
            'productSerialCounts' => $productSerialCounts,
            'totalSerials' => $totalSerials,
        ]);
    }


public function invReport(Request $request)
{
    // استخدام التاريخ المرسل أو تاريخ اليوم
    $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::today();

    $invoices = Invoice::whereDate('invoices.invoice_date', $date)
        ->leftJoin('invoice_products', 'invoices.id', '=', 'invoice_products.invoice_id')
        ->leftJoin('products', 'products.id', '=', 'invoice_products.product_id')
        ->leftJoin('serial_numbers', 'invoices.id', '=', 'serial_numbers.invoice_id')
        ->select([
            'invoices.invoice_date as date',
            DB::raw('COUNT(DISTINCT invoices.id) as count'), 
            DB::raw('COUNT(DISTINCT CASE WHEN invoices.invoice_type = 2 THEN invoices.id END) as total_in'), 
            DB::raw('COUNT(DISTINCT CASE WHEN invoices.invoice_type = 1 THEN invoices.id END) as total_out'), 
            DB::raw('COUNT(DISTINCT CASE WHEN invoices.invoice_status = 4 THEN invoices.id END) as total_return'), 
            DB::raw('COUNT(DISTINCT products.id) as products_count'), 
            DB::raw('SUM(invoice_products.quantity) as total_requested_serials'),
            DB::raw('COUNT(DISTINCT serial_numbers.id) as total_scanned_serials') 
        ])
        ->groupBy('invoices.invoice_date')
        ->get();

    return view('Dashboard.Admin.Report.invreport', compact('invoices', 'date'));
}

}
