@extends('Dashboard.layouts.master')
@section('css')
@endsection
@section('title')
    تقارير | تسليماتى
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">تقارير </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عرض </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    <!-- row -->
    <div class="row row-sm">
        <!--div-->
        <div class="col-xl-12">

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mg-b-0">عرض تقرير المخزون</h4>
                            <i class="mdi mdi-dots-horizontal text-gray"></i>
                        </div>
                        <p>مجموع السيريالات : {{ $totalSerials }}</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-md-nowrap" id="example">
                                <thead>
                                    <thead>
                                        <tr>
                                            <th>منتج </th>
                                            <th>مجموع سيريال </th>
                                            <th>  استلام </th>
                                            <th>  التسليم </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productSerialCounts as $product)
                                    <tr>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ $product->total_serial_count }}</td>
                                        <td>{{ $product->delivery_serial_count }}</td>
                                        <td>{{ $product->receipt_serial_count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                          
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                        {{ $productSerialCounts->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
            <!--/div-->
        </div>
      
    </div>

    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
@endsection
