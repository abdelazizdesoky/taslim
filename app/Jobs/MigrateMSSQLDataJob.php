<?php
namespace App\Jobs;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\YourModel;

class MigrateMSSQLDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // DB::connection('sqlsrv')->table('your_table_in_mssql')->chunk(100, function($rows) {
        //     foreach ($rows as $row) {
        //         YourModel::updateOrCreate(
        //             ['column_unique' => $row->column_unique],
        //             ['column1' => $row->column1, 'column2' => $row->column2]
        //         );
        //     }
        // });

        
        // الاتصال بقاعدة بيانات MS SQL وجلب البيانات
        $mssqlData = DB::connection('sqlsrv')->table('your_table_in_mssql')->get();

        // التحقق من وجود بيانات
        if ($mssqlData->isEmpty()) {
            return;
        }

        // الترحيل إلى قاعدة البيانات الرئيسية (MySQL مثلاً)
        foreach ($mssqlData as $row) {
            // التحقق من أن البيانات غير موجودة مسبقًا إذا لزم الأمر
            Invoice::updateOrCreate(
                ['column_unique' => $row->column_unique], // البحث باستخدام عمود فريد
                [
                    'column1' => $row->column1,
                    'column2' => $row->column2,
                    // باقي الأعمدة
                ]
            );
        }
    }
}
