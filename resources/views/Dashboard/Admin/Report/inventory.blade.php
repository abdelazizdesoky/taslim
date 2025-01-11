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
    <div class="row">
        <!--div-->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">عرض تقرير المخزون</h3>
                </div>
<div class="card-body">

                    <!-- عرض المجموع الكلي للسيريالات -->
                    <div class="alert alert-info" role="alert">
                        <strong>المجموع الكلي للسيريالات:</strong> {{ $totalSerials }} سيريال
                    </div>
                    <br>
                
                    <div class="table-responsive">
                        <table id="example" class="table key-buttons text-md-nowrap">
                            <thead>

                                <tr>
                                    <th>#</th>
                                    <th>اسم المنتج</th>
                                    <th>عدد السيريالات</th>
                                    <th>* </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productSerialCounts as $index => $productData)
                                    <tr>
                                        <td>{{ $productSerialCounts->firstItem() + $index }}</td>
                                        <td>{{ $productData->product_name }}</td>
                                        <td>{{ $productData->serial_count }} سيريال</td>
                                        <td>*</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>



                        <!-- روابط الترقيم -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $productSerialCounts->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            
            </div>
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
