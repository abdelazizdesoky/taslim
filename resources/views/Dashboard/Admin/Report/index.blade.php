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
        <div class="col-lg-8">
            <div class="card">
				<div class="card-body">

					<h2 class="mb-4">تقرير </h2>

					<div class="card mb-4">
						<div class="card-body">
							
							<p class="card-text">


                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            @if (Auth::guard('admin')->check())
                            @php $permission = Auth::guard('admin')->user()->permission; @endphp
                    
                            @if ($permission == 1)
                            <form class="form" method="POST" action="{{ route('admin.report.generate') }}">
                                @csrf
                            @elseif($permission == 5)
                            <form class="form" method="POST" action="{{ route('viewer.report.generate') }}">
                                @csrf
                            @endif
                    
                        @endif

                    <div class="row mg-t-10">
                            <div class="col-lg-3">
                                <label class="rdiobox">
                                    <input name="stutus" type="radio" value="1" > 
                                    <span>استلام</span>
                                </label>
                            </div>
                            <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                <label class="rdiobox">
                                    <input checked name="stutus" type="radio" value="2" > 
                                    <span>تسليم</span>
                                </label>
                            </div>
                        </div>
             
                        
                            <hr>
                                    <div class="form-group mb-4">
                                        <label for="report_for">التقرير عن:</label>
                                        <select class="form-control" id="report_for" name="report_for" required>
                                            <option value="" disabled selected>اختر التقرير</option>
                                            <option value="completed_invoices">الاذون المكتملة</option>
                                            <option value="pending_invoices">الاذون-تحت تسليم</option>
                                            <option value="reviers_invoices">الاذون مرتجع </option>
                                            <option value="canceled_invoices">الاذون- ملغي</option>
                                            {{-- <option value="customers">العملاء</option>
                                            <option value="suppliers">الموردين</option>
                                            <option value="products">المنتجات</option>
                                            <option value="representatives">المندوب</option> --}}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col">
                                                <label for="start_date">تاريخ من :</label>
                                                <input class="form-control" type="date" id="start_date" name="start_date" required>
                                            </div>
                                            <div class="col">
                                                <label for="end_date">تاريخ الى :</label>
                                                <input class="form-control" type="date" id="end_date" name="end_date" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit">عرض التقرير</button>
                                </form>
						</div>
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
