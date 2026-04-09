<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class SerialNumberTemplateExport implements FromArray, WithHeadings
{
    /**
     * Return headings for the export.
     */
    public function headings(): array
    {
        return [
            'serial_number',
        ];
    }

    /**
     * Provide sample data.
     */
    public function array(): array
    {
        return [
            ['1234567890'],
            ['0987654321'],
        ];
    }
}
