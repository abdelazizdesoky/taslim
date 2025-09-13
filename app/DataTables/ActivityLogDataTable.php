<?php

namespace App\DataTables;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Spatie\Activitylog\Models\Activity;

class ActivityLogDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
           return (new EloquentDataTable($query))
        ->addColumn('user', function(Activity $log) {
            $admin = \App\Models\Admin::find($log->causer_id);
            return '<a href="' . route('admin.logs.show', $log->id) . '">' . 
                   ($admin->name ?? 'Unknown') . '</a>';
        })
        ->addColumn('status', function(Activity $log) {
            switch($log->description) {
                case 'updated':
                    return '<span class="bg-info text-white">تعديل</span>';
                case 'deleted':
                    return '<span class="bg-danger text-white">حذف</span>';
                case 'created':
                    return '<span class="bg-success text-white">انشاء</span>';
                default:
                    return 'غير معرف';
            }
        })
         ->addColumn('table_name', function(Activity $log) {
            switch($log->log_name) {
                case 'Admin': return 'مستخدم';
                case 'invoice': return 'الاذون';
                case 'customers': return 'عملاء';
                case 'suppliers': return 'موردين';
                case 'serial_number': return 'سيريال';
                case 'Product': return 'منتجات';
                case 'InvoiceProduct': return 'اذن منتجات';
                case 'Location': return 'موقع';
                case 'ProductType': return 'نوع منتج';
                case 'Brand': return 'ماركة';
                default: return 'غير معرف';
            }
        })
        ->rawColumns(['user', 'status'])
         ->editColumn('created_at', fn($item) => $item->created_at->diffForHumans())
        ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ActivityLog $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Activity $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('activitylog-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                  //  ->dom('Bfrtip')
                    ->orderBy(4, 'desc')
                    ->selectStyleSingle()
                     // إضافة هذا السطر لتحديد عدد الصفوف
                    ->pageLength(20)
                    ->buttons([
                        // Button::make('excel'),
                        // Button::make('csv'),
                        // Button::make('pdf'),
                        // Button::make('print'),
                        // Button::make('reset'),
                        // Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
           Column::make('id'),
        Column::make('user')
            ->title('المستخدم')
            ->searchable(false)
            ->orderable(false),
        Column::make('status')
            ->title('الحالة')
            ->searchable(false)
            ->orderable(false),
        Column::make('table_name')
            ->title('الجدول')
            ->searchable(false)
            ->orderable(false),
        Column::make ('created_at')
            ->title('التاريخ'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ActivityLog_' . date('YmdHis');
    }
}
