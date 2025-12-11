@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">تقرير يومى  </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ كل منتجات</span>
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
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								
							</div>
							<div class="card-body">
					<div class="table-responsive">
						<table class="table  table-bordered  mg-b-0">
							<tr>
								<th>التاريخ</th>
								@foreach($invoices as $inv)
									<td>{{ $inv->date }}</td>
								@endforeach
							</tr>
							<tr>
								<th>عدد الاذون</th>
								@foreach($invoices as $inv)
									<td>{{ $inv->count }}</td>
								@endforeach
							</tr>
								<tr>
								<th>________________________</th>
							</tr>
							<tr>
								<th>اجمالى الاستلام</th>
								@foreach($invoices as $inv)
									<td>{{ $inv->total_in }}</td>
								@endforeach
							
								<th>اجمالى التسليم</th>
								@foreach($invoices as $inv)
									<td>{{ $inv->total_out }}</td>
								@endforeach
							
								<th>اجمالى المرتجعات</th>
								@foreach($invoices as $inv)
									<td>{{ $inv->total_return }}</td>
								@endforeach
							</tr>
							<tr>
								<th>________________________</th>
							</tr>
							<tr>
						
								<th>عدد المنتجات المختلفة</th>
								@foreach($invoices as $inv)
									<td>{{ $inv->products_count }}</td>
								@endforeach
							
							
								<th>عدد السيريالات المسحوبة</th>
								@foreach($invoices as $inv)
									<td>{{ $inv->total_scanned_serials }}</td>
								@endforeach
							
								<th>عدد السيريالات المطلوبة</th>
								@foreach($invoices as $inv)
									<td>{{ $inv->total_requested_serials }}</td>
								@endforeach
							
                         </tr>


						</table>
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

@endsection
