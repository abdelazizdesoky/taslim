@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">سجلات   </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/  عرض سجلات    </span>
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
						<div class="card">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">
									<h4 class="card-title mg-b-0">سجلات </h4>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
								<p class="tx-12 tx-gray-500 mb-2">  تفاصيل السجل  </p>
							</div>
							<div class="card-body">
								<div class="table-responsive hoverable-table">
									
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>ID</th>
                                            <td>{{ $log->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>الوصف</th>
                                            <td>
                                               
                                                @php
                                                $admin = \App\Models\Admin::find($log->causer_id);
                                            @endphp

                                            {{ $admin->name ?? 'Unknown' }}
                                           تم 
                                                
                                            @switch($log->description)
                                             @case('updated')  تعديل  @break 
                                            @case('deleted')  حذف @break 
                                            @case('created')   انشاء  @break 
                                                  
                                             </a>
                                            @default
                                            غير معرف
                                            @endswitch
                                      
                                            فى جدول 
                                                @switch($log->log_name)
                                                @case('Admin') مستخدم  @break 
                                                @case('invoice')  الاذون @break
                                                @case('customers') عملاء @break
                                                @case('suppliers') موردين @break
                                                @case('serial_number') سيريال @break
                                                @case('Product') منتجات @break
                                                @case('InvoiceProduct')  اذن منتجات @break
                                                @case('Location') موقع @break
                                                @case('ProductType') نوع منتج @break
                                                @case('Brand') ماركة  @break
                                              
                                                @default
                                                غير معرف
                                                @endswitch


                                            </td>
                                        </tr>
                                        <tr>
                                            <th>الخصائص</th>
                                            <td>
                                                <pre>{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </td>
                                        </tr>
                                        @if($log->custom_changes)
                                        <tr>
                                            <th>التغييرات</th>
                                            <td>
                                                <ul>
                                                    @foreach($log->custom_changes as $field => $change)
                                                        <li>
                                                            <strong>{{ $field }}</strong>: 
                                                            <span class="text-danger">قديم: {{ $change['old'] }}</span> ->> <span class="text-success">جديد: {{ $change['new'] }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                
                                    <a href="{{ route('admin.activityLogs.index') }}" class="btn btn-primary">عودة إلى السجلات</a>
                                </div>


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
