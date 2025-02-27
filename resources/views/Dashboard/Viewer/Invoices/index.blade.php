@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        /* إضافة تأثيرات Hover لتغيير لون الـ Pagination عند التمرير */
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-item:hover .page-link {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
@endsection
@section('title')
    الاذون | تسليماتى
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الاذون </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ كل الاذون
                </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('Dashboard.messages_alert')
    <!-- row opened -->
    <div class="row row-sm">
        <!--div-->
        <div class="col-xl-12">
     <!---start --->
     <div class="col-lg-8">
        <div class="row row-sm">
            <div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
                <div class="card ">
                    <div class="card-body">
                        <div class="counter-status d-flex md-mb-0">
                            <div class="counter-icon bg-primary-transparent">
                                <i class="icon-layers text-primary"></i>
                            </div>
                            <div class="mr-auto">
                                <h5 class="tx-13">الاذون</h5>
                                <h2 class="mb-0 tx-22 mb-1 mt-1"> {{ \App\Models\Invoice::count() }}</h2>
                                <p class="text-muted mb-0 tx-11">
                                    <i class="si si-arrow-up-circle text-success mr-1"></i>
                                     استلام - {{ \App\Models\Invoice::where('invoice_type', 1)->count() }}
                                       <i class="si si-arrow-down-circle text-danger mr-1"></i> 
                                      تسليم -  {{ \App\Models\Invoice::where('invoice_type', 2)->count() }}
                                    </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
                <div class="card ">
                    <div class="card-body">
                        <div class="counter-status d-flex md-mb-0">
                            <div class="counter-icon bg-danger-transparent">
                                <i class="icon-paypal text-danger"></i>
                            </div>
                            <div class="mr-auto">
                                <h5 class="tx-13">الاذون المفعلة </h5>
                                <h2 class="mb-0 tx-22 mb-1 mt-1">{{ \App\Models\Invoice::where('invoice_status', 1)->count() }}</h2>
                                <p class="text-muted mb-0 tx-11">
                                    <i class="si si-arrow-up-circle text-success mr-1"></i>
                                     استلام - {{ \App\Models\Invoice::where('invoice_type', 1)->where('invoice_status', 1)->count() }}
                                       <i class="si si-arrow-down-circle text-danger mr-1"></i> 
                                      تسليم -  {{ \App\Models\Invoice::where('invoice_type', 2)->where('invoice_status', 1)->count() }}
                                    </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
                <div class="card ">
                    <div class="card-body">
                        <div class="counter-status d-flex md-mb-0">
                            <div class="counter-icon bg-success-transparent">
                                <i class="icon-rocket text-success"></i>
                            </div>
                            <div class="mr-auto">
                                <h5 class="tx-13">الاذون المكتملة  </h5>
                                <h2 class="mb-0 tx-22 mb-1 mt-1">{{ \App\Models\Invoice::where('invoice_status', 3)->count() }}</h2>
                                <p class="text-muted mb-0 tx-11">
                                    <i class="si si-arrow-up-circle text-success mr-1"></i>
                                     استلام - {{ \App\Models\Invoice::where('invoice_type', 1)->where('invoice_status', 3)->count() }}
                                       <i class="si si-arrow-down-circle text-danger mr-1"></i> 
                                      تسليم -  {{ \App\Models\Invoice::where('invoice_type', 2)->where('invoice_status', 3)->count() }}
                                    </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div> 
    <!---end --->
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        {!! $dataTable->table() !!}
                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->
    </div>

    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    {!! $dataTable->scripts() !!}
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('/plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
