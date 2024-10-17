<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            
            'code'=> $this->code,
            'invoice_date' =>  $this->invoice_date,
            'invoice_type'=> $this->invoice_type,
            'supplier_id' =>  $this->supplier_id,
            'customer_id' => $this->customer_id ,
            'employee_id' =>  $this->employee_id,
            'invoice_status' => $this->invoice_status ,
            'location_id' => $this-> location_id,
        
        ];
    }
}
