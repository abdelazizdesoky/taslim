@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">المستخدمين   </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ كل المستخدمين  </span>
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
									<a href="{{route('admin.admins.create')}}" class="btn btn-primary">اضافة مستخدم جديد</a>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
								
								
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example" class="table key-buttons text-md-nowrap">
										<thead>

											<tr>
												<th>#</th>
												<th > ايميل   </th>
												<th > اسم  </th>
												<th > صلاحية   </th>
												<th > الحالة   </th>
												<th > انشئ منذ  </th>
												<th > ألاجراءات  </th>
											</tr>
										</thead>
										<tbody>
                                        @foreach($admins as $admin)
											<tr>
                                                <td>{{$loop->iteration}}</td>
												<td>{{$admin->email}}</td>
												<td>{{$admin->name}}</td>
												<td>
													@switch($admin->permission)
													@case(1)
													ادمن
													@break

													@case(2)
													منسق
													@break

													@case(3)
											        مندوب   
													@break

													@case(4)
													امين مخزن   
													   @break

													@default
													 غير محدد
													@endswitch
																
												</td>
												<td>
													<div
														class="dot-label bg-{{$admin->status == 1 ? 'success':'danger'}} ml-1"></div>
													{{$admin->status == 1 ? 'مفعل' : ' غير مغعل '}}
												</td>
													<td>{{$admin->created_at?$admin->created_at->diffForHumans(): '-'}}</td>
													<td>
													<div class="dropdown">
														<button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-outline-primary btn-sm" data-toggle="dropdown" type="button">الاجراءات<i class="fas fa-caret-down mr-1"></i></button>
														<div class="dropdown-menu tx-13">
															<a class="dropdown-item" href="{{route('admin.admins.edit',$admin->id)}}"><i style="color: #0ba360" class="text-success ti-user"></i>&nbsp;&nbsp;تعديل البيانات</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#update_password{{$admin->id}}"><i   class="text-primary ti-key"></i>&nbsp;&nbsp;تغير كلمة المرور</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#Deleted{{$admin->id}}"><i   class="text-danger  ti-trash"></i>&nbsp;&nbsp;حذف البيانات</a>
														</div>
													</div>
                                                </td>
											</tr>
                                            @include('Dashboard.Admin.admin.Deleted')
											@include('Dashboard.Admin.admin.update_password')
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
