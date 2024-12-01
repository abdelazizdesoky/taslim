@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">منتجات </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ كل منتجات</span>
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
									<table id="example" class="table key-buttons mg-b-0 text-md-nowrap">
										<thead>
											<tr>
												<th> #</th>
												<th>اسم المنتج</th>
												<th> كود</th>
												<th> </th>
											</tr>
										</thead>
										<tbody>
											@foreach ($products as $product)
											<tr>

											<td>{{$loop->iteration}}	</td>
											<td>
												{{ $product->productType->type_name }}
												 {{ $product->productType->brand->brand_name }}
												  {{ $product->product_name }}
												   {{ $product->detail_name }} 	
											</td>
											<td>
												{{ $product->product_code }}
											    
											</td>
												
											<td>
												
											    
											</td>
												
											</tr>

								
										@endforeach
										</tbody>
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
