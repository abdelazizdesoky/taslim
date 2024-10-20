@extends('Dashboard.layouts.master')
@section('css')
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الاذن </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تسليم</span>
						</div>
					</div>
					<div class="d-flex my-xl-auto right-content">
						
						<div class="pr-1 mb-3 mb-xl-0">
							<a href="{{route('Dashboard.employee')}}">
							<button type="button" class="btn btn-warning  btn-icon ml-2"><i class="mdi mdi-refresh"></i></button>
							</a>
						</div>
					
						
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
			<!-- row opened -->
	<div class="row row-sm">
		<div class="col-md-12 col-lg-8 col-xl-8">
			<div class="card card-table-two">
				<div class="d-flex justify-content-between">
					<h4 class="card-title mb-1"><h4>الاذون التسليم    </h4></h4>
					<i class="mdi mdi-truck-fast text-gray"><label class="tx-13"> عدد الاوذون -{{$activeInvoicesCount}}</label>	</i>
				</div>
				<span class="tx-12 tx-muted mb-3 ">		</span>
				<div class="table-responsive country-table">
					<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
						
						<thead>
							<tr>
								<th class="wd-lg-25p">كود الاذن </th>
								<th class="wd-lg-25p tx-right">تاريخ  </th>
								<th class="wd-lg-25p tx-right">العميل /عميل  </th>
								<th class="wd-lg-25p tx-right">حالة </th>
								<th class="wd-lg-25p tx-right">اجراء </th>

							</tr>
						</thead>

					
						<tbody>
							@if(isset($Invoices) && $Invoices->isNotEmpty())
							@foreach($Invoices as $invoice)
							<tr>
								<td>{{$invoice->code}}</td>
								<td class="tx-right tx-medium tx-inverse">{{ $invoice->invoice_date}}</td>
								<td class="tx-right tx-medium tx-inverse">{{ $invoice->customer->name??$invoice->supplier->name}}</td>
								<td class="tx-right tx-medium tx-danger">
									@if($invoice->invoice_status == 1)
									{{ $invoice->invoice_type == 2 ?'تحت تسليم ':'مرتجع '}}     
									
										@endif
								</td>
								<td class="tx-right tx-medium tx-danger">
									@if($invoice->invoice_status == 1 )
									<a href="{{route ('employeeinvoice.edit',$invoice->id)}}" class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a>	
									@else
									@endif
					
								</td>
							</tr>
							@endforeach
							@else
								<tr>
									<td colspan="5">لا توجد الاذون لعرضها.</td>
								</tr>
							@endif
						</tbody>
					</table>
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