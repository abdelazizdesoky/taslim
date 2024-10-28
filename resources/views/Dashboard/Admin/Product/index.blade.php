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
								<div class="d-flex justify-content-between">
									<a href="{{route('admin.products.create')}}" class="btn btn-primary">اضافة منتج  جديد</a>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
								
								
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example" class="table key-buttons mg-b-0 text-md-nowrap">
										<thead>
											<tr>
												<th> #</th>
												<th>اسم المنتج</th>
												<th> كود</th>
												<th> اجراءات</th>
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
													<a href="{{route('admin.products.edit',$product->id)}}" class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a>
													<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#Deleted{{$product->id}}"><i class="fas fa-trash"></i></button>

												</td>
											</tr>

											@include('Dashboard.Admin.Product.Deleted')
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
    <!--Internal  Notify js -->
    <script src="{{URL::asset('dashboard/plugins/notify/js/notifIt.js')}}"></script>
    <script src="{{URL::asset('/plugins/notify/js/notifit-custom.js')}}"></script>
@endsection
