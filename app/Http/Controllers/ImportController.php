<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\InvoicesImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceTemplateExport;

class ImportController extends Controller
{
    public function showForm()
    {
        return view('import');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new InvoicesImport, $request->file('file'));

        return redirect()->back()->with('success', 'Invoices Imported Successfully!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new InvoiceTemplateExport, 'invoice_template.xlsx');
    }
}
