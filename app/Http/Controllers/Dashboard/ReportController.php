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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // تحديد الفترة الزمنية
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // تحديد نوع التقرير
        $reportFor = $request->report_for;

        // إنشاء التقرير بناءً على الخيار المحدد
        switch ($reportFor) {
            case 'completed_invoices':
                $data = $this->getCompletedInvoices($startDate, $endDate);
                $title = 'الاذن  المكتملة';
             
                break;

            case 'pending_invoices':
                $data = $this->getPendingInvoices($startDate, $endDate);
                $title = 'الاذن  تحت التسليم';
               
                break;

            case 'canceled_invoices':
                $data = $this->getCanceledInvoices($startDate, $endDate);
                $title = 'الاذن  الملغية';
              
                break;

            case 'reviers_invoices':
                $data = $this->getreversInvoices($startDate, $endDate);
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
    private function getCompletedInvoices($startDate, $endDate)
    {
        return Invoice::where('invoice_status', 3)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->with(['supplier', 'customer', 'admin', 'location', 'serialNumbers'])
            ->withCount('serialNumbers')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // دالة لجلب الاذن  تحت التسليم
    private function getPendingInvoices($startDate, $endDate)
    {
        return Invoice::where('invoice_status', 1)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->with(['supplier', 'customer', 'admin', 'location', 'serialNumbers'])
            ->withCount('serialNumbers')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // دالة لجلب الاذن  الملغية
    private function getCanceledInvoices($startDate, $endDate)
    {
        return Invoice::where('invoice_status', 5)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->with(['supplier', 'customer', 'admin', 'location', 'serialNumbers'])
            ->withCount('serialNumbers')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // دالة لجلب الاذن  مرتجع
    private function getreversInvoices($startDate, $endDate)
    {
        return Invoice::where('invoice_status', 4)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->with(['supplier', 'customer', 'admin', 'location', 'serialNumbers'])
            ->withCount('serialNumbers')
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
        // جلب عدد السيريالات لكل منتج مباشرة من قاعدة البيانات
        $productSerialCounts = DB::table('serial_numbers')
            ->select(
                'products.id as product_id',
                'products.product_name',
                DB::raw('COUNT(serial_numbers.id) as serial_count')
            )
            ->join('products', function ($join) {
                $join->on(DB::raw('SUBSTRING(serial_numbers.serial_number, 1, 7)'), '=', 'products.product_code')
                    ->orWhere(DB::raw('SUBSTRING(serial_numbers.serial_number, 1, 7)'), '=', DB::raw('LPAD(products.product_code, 7, "0")'));
            })
            ->groupBy('products.id', 'products.product_name')
            ->orderByDesc('serial_count')
            ->paginate(500); // الترقيم مباشرة من الاستعلام

        // حساب المجموع الكلي للسيريالات
        $totalSerials = DB::table('serial_numbers')->count();

        // تمرير البيانات إلى الـ View
        return view('Dashboard.Admin.Report.inventory', [
            'productSerialCounts' => $productSerialCounts,
            'totalSerials' => $totalSerials,
        ]);
    }
}
