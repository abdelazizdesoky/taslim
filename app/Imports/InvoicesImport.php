<?php

namespace App\Imports;

use App\Models\Admin;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customers;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoicesImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
       
        $validator = Validator::make($row, [
            'code' => 'required|string|max:255',
            'invoice_type' => 'required|in:1,2,3',
            'location_id' => 'required|integer',
            'employee_id' => 'required|integer|exists:admins,id',
            'customer_code' => 'exists:customers,code|nullable',
            'supplier_code' => 'exists:suppliers,code|nullable',
            'invoice_status' => 'required|in:1,2,3,4,5',
            'product_code' => 'required|string|max:255|exists:products,code',
            'quantity' => 'required|integer|min:1',
        ], [
            'code.required' => 'The code field is required.',
            'invoice_type.required' => 'The invoice type field is required.',
            'invoice_type.in' => 'The invoice type must be one of the following types: 1, 2, 3.',
            'location_id.required' => 'The location ID field is required.',
            'employee_id.required' => 'The employee ID field is required.',
            'employee_id.exists' => 'The selected employee ID is invalid.',
            'customer_code.exists' => 'The selected customer code is invalid.',
            'supplier_code.exists' => 'The selected supplier code is invalid.',
            'invoice_status.required' => 'The invoice status field is required.',
            'invoice_status.in' => 'The invoice status must be one of the following statuses: 1, 2, 3, 4, 5.',
            'product_code.required' => 'The product code field is required.',
            'product_code.exists' => 'The selected product code is invalid.',
            'quantity.required' => 'The quantity field is required.',
            'quantity.integer' => 'The quantity must be an integer.',
            'quantity.min' => 'The quantity must be at least 1.',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed for row: ' . json_encode($row));
            return null;
        }

       
    
        $customer = Customers::where('code', $row['customer_code'])->first();
        $supplier = Supplier::where('code', $row['supplier_code'])->first();
        $product = Product::where('product_code', $row['product_code'])->first();

        $Invoice = Invoice::firstOrCreate(
            [
                'code' => $row['code'], 
            ],
            [
                'invoice_type' => $row['invoice_type'],
                'invoice_date' => date('Y-m-d'),
                'location_id'=> $row['location_id'],
                'employee_id'=> $row['employee_id'],
                'customer_id' =>  $customer->id ?? null,
                'supplier_id' =>  $supplier->id ?? null,
                'invoice_status' => $row['invoice_status'],
                'created_by' =>  Auth::user()->id,
            ]
        );

        if ($product) {
            InvoiceProduct::create([
                'invoice_id' => $Invoice->id,
                'product_id' => $product->id,
                'quantity' => $row['quantity'],
            ]);
        }

        return $Invoice;
    }
}

