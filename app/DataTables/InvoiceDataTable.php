<?php

namespace App\DataTables;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class InvoiceDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('invoice_type', function ($item) {
                return match ($item->invoice_type) {
                    '1' => 'استلام',
                    '2' => 'تسليم',
                    '3' => 'مرتجعات عامه',
                    default => 'غير معرف',
                };
            })
            ->editColumn('created_at', fn($item) => $item->created_at->diffForHumans())
            ->editColumn('customer.name', fn($item) => $item->customer?->name ?? '--')
            ->editColumn('admin.name', fn($item) => $item->admin?->name ?? '--')
            ->editColumn('supplier.name', fn($item) => $item->supplier?->name ?? '--')
            ->editColumn('actions', fn($item) => view('Dashboard.Admin.Invoices._actions', ['Invoice' => $item]))
            /*
            ->filterColumn('location', function ($query, $keyword) {
                $query->orWhereHas('location', fn($q) => $q->where('location_name', 'LIKE', "%$keyword%"));
            })*/
            ->addColumn('action', 'invoice.action')
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param Invoice $model
     * @return QueryBuilder
     */
    public function query(Invoice $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('supplier:id,name')
            ->with('customer:id,name')
            ->with('location:id,location_name')
            ->with('admin:id,name')
            ->with('creator:id,name')
            ->withCount('serial_numbers');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('invoice-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive()
            ->dom('Bfrtip')
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
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
            Column::make('id')
                ->title('#'),
            Column::make('code')
                ->title('رقم الاذن'),
            Column::make('invoice_type')
                ->title('نوع الاذن'),
            Column::make('invoice_date')
                ->title('تاريخ الاذن'),
            Column::make('admin.name')
                ->title('المندوب'),
            Column::make('customer.name')
                ->title('العميل'),
            Column::make('supplier.name')
                ->title('المورد'),
            Column::make('invoice_status')
                ->title('حالة الاذن'),
            Column::make('location.location_name')
                ->title('موقع'),
            Column::make('created_at')
                ->title('تااريخ تحرير'),
            Column::make('serial_numbers_count')
                ->title('سيريال مسحوب')
                ->searchable(false),
            Column::make('creator.name')
                ->title('منسق'),
            Column::make('actions')
                ->title('ألاجراءات')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->width(60)
                ->addClass('text-center')
            ,
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Invoice_' . date('YmdHis');
    }
}
