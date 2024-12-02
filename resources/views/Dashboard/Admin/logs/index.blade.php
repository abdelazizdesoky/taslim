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
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
								
								
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example" class="table key-buttons text-md-nowrap">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>اسم السجل</th>
                                                <th>الوصف</th>
                                                <th>المستخدم الذي قام بالتغيير</th>
                                                <th>البيانات القديمة</th>
                                                <th>البيانات الجديدة</th>
                                                <th>التاريخ</th>
                                                <th>التعديلات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($logs as $log)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $log->log_name }}</td>
                                                    <td>{{ $log->description }}</td>
                                                    <td>{{ $log->causer_type }} - {{ $log->causer_id }}</td>
                    
                                                    {{-- عرض البيانات القديمة --}}
                                                    <td>
                                                        @if(isset($log->properties['old']))
                                                            <pre>{{ json_encode($log->properties['old'], JSON_PRETTY_PRINT) }}</pre>
                                                        @else
                                                            لا توجد بيانات قديمة
                                                        @endif
                                                    </td>
                    
                                                    {{-- عرض البيانات الجديدة --}}
                                                    <td>
                                                        @if(isset($log->properties['attributes']))
                                                            <pre>{{ json_encode($log->properties['attributes'], JSON_PRETTY_PRINT) }}</pre>
                                                        @else
                                                            لا توجد بيانات جديدة
                                                        @endif
                                                    </td>
                    
                                                    <td>{{ $log->created_at }}</td>
                    
                                                    {{-- عرض التعديلات بين البيانات القديمة والجديدة --}}
                                                    <td>
                                                        @if($log->custom_changes)
                                                            <ul>
                                                                @foreach($log->custom_changes as $field => $change)
                                                                    <li>
                                                                        <strong>{{ $field }}:</strong>
                                                                        <span class="text-danger">قديم: {{ $change['old'] }}</span> |
                                                                        <span class="text-success">جديد: {{ $change['new'] }}</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            لا توجد تعديلات
                                                        @endif
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
    <!--Internal  Notify js -->
    <script src="{{URL::asset('dashboard/plugins/notify/js/notifIt.js')}}"></script>
    <script src="{{URL::asset('/plugins/notify/js/notifit-custom.js')}}"></script>
@endsection
