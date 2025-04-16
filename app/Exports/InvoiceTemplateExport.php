<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class InvoiceTemplateExport implements FromArray, WithHeadings
{
    /**
     * إرجاع عناوين الأعمدة
     */
    public function headings(): array
    {
        return [
            'code',
            'invoice_type',
            'invoice_date',
            'location_id',
            'employee_id',  
            'customer_code', 
            'supplier_code',  
            'invoice_status',
            'product_code',
            'quantity',
        ];
    }

    /**
     * توفير بيانات تجريبية (اختياري)
     */
    public function array(): array
    {
        return [
            ['code 01', '2', '2024-03-17', '1', '6', '1002', '', '1', '1011112', '5'],
            ['code 01', '', '', '', '', '', '', '', '8311580', '5'],
            ['code 02', '1', '2024-03-17', '1', '6', '', '100', '1', '1011112', '5'],
            ['code 02', '', '', '', '', '', '', '', '8311580', '5'],
           
        ];
    }
}
