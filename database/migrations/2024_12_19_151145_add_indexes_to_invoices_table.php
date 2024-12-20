<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->index('code'); // إضافة فهرس للـ code
            $table->index('invoice_type'); // إضافة فهرس للـ invoice_type
            $table->index('invoice_date'); // فهرس لتاريخ الفاتورة
            $table->index('invoice_status'); // فهرس لحالة الفاتورة
            $table->index('location_id'); // فهرس للمفتاح الأجنبي location_id
            $table->index('employee_id'); // فهرس للمفتاح الأجنبي employee_id
            $table->index('supplier_id'); // فهرس للمفتاح الأجنبي supplier_id
            $table->index('customer_id'); // فهرس للمفتاح الأجنبي customer_id
        });
    }
    
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['code']);
            $table->dropIndex(['invoice_type']);
            $table->dropIndex(['invoice_date']);
            $table->dropIndex(['invoice_status']);
            $table->dropIndex(['location_id']);
            $table->dropIndex(['employee_id']);
            $table->dropIndex(['supplier_id']);
            $table->dropIndex(['customer_id']);
        });
    }
    
};
