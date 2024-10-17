<?php

namespace App\Imports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoicesImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        return new Invoice([
            'code' => $row['code'],
            'invoice_type' => $row['invoice_type'],
            'invoice_date' => date('Y-m-d'),
            'employee_id'=> $row['employee_id'],
            'customer_id' => $row['customer_id'],
            'supplier_id' => $row['supplier_id'],
            'invoice_status' => $row['invoice_status'],
        ]);
    }
}

