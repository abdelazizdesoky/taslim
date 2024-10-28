@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">موردين </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ كل موردين</span>
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
									<a href="{{route('admin.supplier.create')}}" class="btn btn-primary">اضافة مورد  جديد</a>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
								
								
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example" class="table key-buttons text-md-nowrap">
										<thead>

											<tr>
												<th>#</th>
												<th > رقم مورد </th>
												<th > اسم مورد </th>
												<th> عنوانة </th>
												<th> تليفون مورد </th>
												<th > الحالة </th>
												<th > ألاجراءات  </th>
											</tr>
										</thead>
										<tbody>
                                        @foreach($Suppliers as $Supplier)
											<tr>
                                                <td>{{$loop->iteration}}</td>
												<td>{{$Supplier->code}}</td>
												<td>{{$Supplier->name}}</td>
                                                <td>{{$Supplier->address}}</td>
                                                <td>{{$Supplier->phone}}</td>
                                                <td>
													<div
													class="dot-label bg-{{$Supplier->status == 1 ? 'success':'danger'}} ml-1"></div>
													{{$Supplier->status == 1 ? 'مفعلة':'غير مفعلة'}}</td>
                                               
                                                <td>
                                                    <a href="{{route('admin.supplier.edit',$Supplier->id)}}" class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a>
                                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#Deleted{{$Supplier->id}}"><i class="fas fa-trash"></i></button>
                                                </td>
											</tr>
                                            @include('Dashboard.Admin.Supplier.Deleted')
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
