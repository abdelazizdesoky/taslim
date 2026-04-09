<?php

namespace App\Imports;

use App\Models\SerialNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class SerialNumbersImport implements ToModel, WithHeadingRow
{
    use Importable;

    private $invoice_id;

    public function __construct($invoice_id)
    {
        $this->invoice_id = $invoice_id;
    }

    public function model(array $row)
    {
        if (empty($row['serial_number'])) {
            return null;
        }

        // Check if serial number already exists for this invoice to avoid duplicates
        $exists = SerialNumber::where('invoice_id', $this->invoice_id)
            ->where('serial_number', $row['serial_number'])
            ->exists();

        if ($exists) {
            return null;
        }

        return new SerialNumber([
            'serial_number' => $row['serial_number'],
            'invoice_id'    => $this->invoice_id,
        ]);
    }
}
