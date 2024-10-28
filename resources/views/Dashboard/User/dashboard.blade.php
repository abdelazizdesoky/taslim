@extends('Dashboard.layouts.master')
@section('css')

@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="left-content">
						<div>
						  <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">لوحة التحكم المنسق -{{ Auth::user()->name }}</h2>
						</div>
					</div>
			
				</div>
				<!-- /breadcrumb -->


@endsection
@section('content')
			<!-- row -->
			<div class="row row-sm">
				
				<div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
					<div class="card overflow-hidden sales-card bg-primary-gradient">
						<a  href="{{ route('user.invoices.index') }}">
						<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
							<div class="">
								<h6 class="mb-3 tx-12 text-white">عدد الاذون   </h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white"> {{\App\Models\Invoice::count()}}</h4>
									</div>
								</div>
							</div>
						</div>
					</a>
					</div>
				</div>
				<div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
					<div class="card overflow-hidden sales-card bg-danger-gradient">
						<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
							<div class="">
								<h6 class="mb-3 tx-12 text-white"> الازون المفعلة </h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white">{{\App\Models\Invoice::where('invoice_status', 1)->count()}}</h4>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				<div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
					<div class="card overflow-hidden sales-card bg-success-gradient">
						<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
							<div class="">
								<h6 class="mb-3 tx-12 text-white">المنديب </h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										 <h4 class="tx-20 font-weight-bold mb-1 text-white">  {{\App\Models\Admin::whereIn('permission', [3,4])->count()}}</h4> 
									</div>
								</div>
							</div>
						</div>
				
					</div>
				</div>
			</div>
			<!-- row closed -->

			

			<!-- row opened -->
			<div class="row row-sm">
					
			

			
				<div class="col-md-12 col-lg-8 col-xl-8">
					<div class="card card-table-two">
						<div class="d-flex justify-content-between">
							
							<h4 class="card-title mb-1">اخر فواتير </h4>
							<i class="mdi mdi-dots-horizontal text-gray"></i>
						</div>
						<span class="tx-12 tx-muted mb-3 ">اخر خمس حركات الاذون تمت على النظام .</span>
						<div class="table-responsive country-table">
							<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
								<thead>
									<tr>
										<th class="wd-lg-25p">#</th>
										<th class="wd-lg-25p tx-right"> كود الاذن</th>
										<th class="wd-lg-25p tx-right">عميل/مورد</th>
										<th class="wd-lg-25p tx-right">تاريخ </th>
									</tr>
								</thead>
								<tbody>
								
									@foreach(\App\Models\Invoice::latest()->take(5)->get()  as $invoice)
										<tr>
										<td>{{$loop->iteration}}</td>
										<td class="tx-right tx-medium tx-inverse"> {{$invoice->code}}</td>
										<td class="tx-right tx-medium tx-inverse">		
												@if($invoice->invoice_type == 2) 
												{{ $invoice->customer->name ??'-' }} 
											@else
												{{ $invoice->supplier->name ??'-'}} 
											@endif
										</td>
										<td class="tx-right tx-medium tx-danger">{{$invoice->invoice_date}}</td>
									</tr>
										@endforeach
									
									
								</tbody>
							</table>
						</div>
					</div>
						
			</div>

				<div class="col-xl-4 col-md-12 col-lg-6">
					<div class="card">
						<div class="card-header pb-1">
							<h3 class="card-title mb-2">منديب التسليم  </h3>
							<p class="tx-12 mb-0 text-muted">Sales activities are the tactics that salespeople use to achieve their goals and objective</p>
						</div>
						<div class="product-timeline card-body pt-2 mt-1">
							<ul class="timeline-1 mb-0">

							
								@foreach(\App\Models\admin::whereIn('permission', [3,4])->take(3)->get()  as $employee)

								<li class="mt-0 mb-0"> <i class="icon-note icons bg-primary-gradient text-white product-icon"></i> <span class="font-weight-semibold mb-4 tx-14 ">- {{$employee->name}}</span> <a href="#" class="float-left  tx-11 text-muted"></a>
									   <p class="mb-0 text-muted tx-12">عدد الاذون 
											<p></p>{{\App\Models\Invoice::where('employee_id', $employee->id )->count()}} </p> 
								   </li>
							  @endforeach 

							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- row close -->

			</div>
			<!-- /row -->
		</div>
	</div>
	<!-- Container closed -->
@endsection
@section('js')



@endsection
