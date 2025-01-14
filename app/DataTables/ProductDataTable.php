<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
 
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('actions', function ($item) {
                return view('Dashboard.Admin.Product._actions', ['product' => $item]);
            })
           
            ->setRowId('id');
    }

 
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
      
    }

  
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('product-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                     ->dom('Bfrtip') 
                   // ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                      
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
            ->title('#')
            ->searchable(false)
            ->orderable(false),
            Column::make('product_name')
            ->title('اسم المنتج'),
            Column::make('product_code')
            ->title('كود المنتج'),
            Column::computed('actions')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-center'),
        ];
    }

 
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
