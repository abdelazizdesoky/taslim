@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">موظفين </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ كل الموظفين</span>
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
									<a href="{{route('employee.create')}}" class="btn btn-primary">اضافة موظف  جديد</a>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
								
								
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example" class="table key-buttons text-md-nowrap">
										<thead>

											<tr>
												<th>#</th>
												<th > كود  </th>
												<th > اسم  </th>
												<th > المهام </th>
												<th> تليفون  </th>
												<th > الحالة </th>
												<th > انشئ منذ  </th>
												<th > ألاجراءات  </th>
											</tr>
										</thead>
										<tbody>
                                        @foreach($employees as $employee)
											<tr>
                                                <td>{{$loop->iteration}}</td>
												<td>{{$employee->code}}</td>
												<td>{{$employee->name}}</td>
												<td>{{$employee->type == 2 ? 'امبن مخزن':'مندوب تسليم '}}</td>
                                                <td>{{$employee->phone}}</td>
												
                                                <td>
													<div
													class="dot-label bg-{{$employee->status == 1 ? 'success':'danger'}} ml-1"></div>
													{{$employee->status == 1 ? 'مفعل':'غير مفعل'}}</td>
                                               
													<td>{{ $employee->created_at->diffForHumans() }}</td>
													<td>
													<div class="dropdown">
														<button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-outline-primary btn-sm" data-toggle="dropdown" type="button">الاجراءات<i class="fas fa-caret-down mr-1"></i></button>
														<div class="dropdown-menu tx-13">
															<a class="dropdown-item" href="{{route('employee.edit',$employee->id)}}"><i style="color: #0ba360" class="text-success ti-user"></i>&nbsp;&nbsp;تعديل البيانات</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#update_password{{$employee->id}}"><i   class="text-primary ti-key"></i>&nbsp;&nbsp;تغير كلمة المرور</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#update_status{{$employee->id}}"><i   class="text-warning ti-back-right"></i>&nbsp;&nbsp;تغير الحالة</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#Deleted{{$employee->id}}"><i   class="text-danger  ti-trash"></i>&nbsp;&nbsp;حذف البيانات</a>
														</div>
													</div>
                                                </td>
											</tr>
                                            @include('Dashboard.Admin.Employee.Deleted')
											@include('Dashboard.Admin.Employee.update_password')
											@include('Dashboard.Admin.Employee..update_status')
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
