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
        <!-- Col -->
        <div class="col">
            <div class="card">
                <div class="card-body">



                    <h2 class="mb-4">تقرير {{ $title }}</h2>

                    <div class="card ">
                        <div class="card-body">
                            <h5 class="card-title">تفاصيل التقرير</h5>
                            <p class="card-text">
                            <p>من {{ $startDate->format('Y-m-d') }} إلى {{ $endDate->format('Y-m-d') }}</p>
                            </p>
                        </div>
                    </div>
                    <div class="card-body">
                    <!-- جدول عرض الفواتير -->
                    <table class="table  key-buttons table-bordered mg-b-0 text-md-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم الاذن </th>
                                <th>التاريخ</th>
                                <th>المورد</th>
                                <th>العميل</th>
                                <th>الموقع</th>
                                <th>عدد السيريالات</th>
                                <th>مندوب </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $invoice)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $invoice->code }}</td>
                                    <td>{{ $invoice->invoice_date }}</td>
                                    <td>{{ $invoice->supplier->name ?? '-' }}</td>
                                    <td>{{ $invoice->customer->name ?? '-' }}</td>
                                    <td>{{ $invoice->location->name ?? '-' }}</td>
                                    <td>{{ $invoice->serial_numbers_count }}</td>
                                    <td>{{ $invoice->Admin->name?? '-'}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- الترقيم -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links('pagination::bootstrap-4') }}
                    </div> 
                </div>
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
