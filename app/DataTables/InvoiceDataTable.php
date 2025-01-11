<?php

namespace App\DataTables;

use App\Models\Invoice;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

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

            ->addIndexColumn()
            ->editColumn('invoice_type', function ($item) {
                return match ($item->invoice_type) {
                    1 => 'استلام',
                    2 => 'تسليم',
                    3 => 'مرتجعات عامه',
                    default => 'غير معرف',
                };
            })
            ->editColumn('created_at', fn($item) => $item->created_at->diffForHumans())
            ->editColumn('customer.name', fn($item) => $item->customer?->name ?? '--')
            ->editColumn('admin.name', fn($item) => $item->admin?->name ?? '--')
            ->editColumn('supplier.name', fn($item) => $item->supplier?->name ?? '--')
            ->editColumn('invoice_status', function ($item) {
                return match ($item->invoice_status) {
                    1 => match ($item->invoice_type) {
                        1 => 'تحت استلام',
                        2 => 'تحت تسليم',
                        default => 'مرتجع',
                    },
                    3 => 'مكتمل',
                    4 => 'مرتجع', 
                    5 => 'ملغى',
                    default => 'غير محدد',
                };
            })

            ->editColumn('actions', function ($item) {
                    return view('Dashboard.Admin.Invoices._actions', ['Invoice' => $item]);
            })

            ->editColumn('code', function ($item) {
                    return '<a href="' . route('admin.invoices.show', $item->id) . '">' . $item->code . '</a>';
            })
            ->rawColumns(['code']) // Ensure the code column is rendered as HTML
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
            ->withCount('serialNumbers');
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
            //->responsive()
             ->dom('Bfrtip')
            ->orderBy([11, 'desc'])
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
               
            ])
            ->createdRow('function(row, data, dataIndex) {
                var statusCell = $("td:eq(7)", row); // Adjust the index based on the position of the invoice_status column
                var statusText = statusCell.text();
                var colorClass = "";
                if (statusText === "تحت تسليم" ) {
                    colorClass = "bg-info text-white";
                } else if (statusText === "تحت استلام" ) {
                    colorClass = "bg-warning text-dark";
                } else if (statusText == "مكتمل" ) {
                    colorClass = "bg-success text-white";
                } else if (statusText === "مرتجع" ) {
                    colorClass = "bg-secondary text-white";
                } else if (statusText == "ملغى" ) {
                    colorClass = "bg-danger text-white";
                }
                statusCell.html(`<span class="d-inline-block p-2 rounded ${colorClass}">${statusText}</span>`);
            }');
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('#')
                ->searchable(false)
                ->orderable(false),
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
            Column::make('serial_numbers_count')
                ->title('سيريال ')
                ->searchable(false),
            Column::make('creator.name')
                ->title('منسق'),
            Column::make('created_at')
                ->title('تاريخ تحرير'),
            Column::make('actions')
                ->title('ألاجراءات')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->width(60)
                ->addClass('text-center'),
            
          
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
