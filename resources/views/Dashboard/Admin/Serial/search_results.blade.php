@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
          <!-- Internal Select2 css -->
          <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
          <link href="{{URL::asset('dashboard/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('title')
     سيريال بحث | تسليماتى 
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">سيريال </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ بحث </span>
						</div>
					</div>
					
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')

<!-- row opened -->
<div class="row row-sm">
    <!--div-->
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between"> 
                    نتائج البحث عن السيريال: {{ $query ?? '' }}
                </div>
            </div>
            <div class="card-body">
               
                    <table id="example" class="table key-buttons text-md-nowrap">
                        
                                @if(isset($invoices) && $invoices->count() > 0)
                        
                                <thead>

                                    <tr>
                                        <th>#</th>
                                        <th > كود الاذن </th>
                                        <th >  نوع الاذن </th>
                                        <th> تاريخ الاذن  </th>
                                        <th>  المندوب </th>
                                        <th > العميل  </th>
                                        <th > المورد </th>
                                        <th > حالة الاذن </th>
                                        <th > موقع </th>
                                        <th >  تااريخ تحرير</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($invoices as $invoice)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td><a href="{{route('Invoices.show',$invoice->id)}}">{{$invoice->code}}</a></td>
                                        <td>{{$invoice->invoice_type == 1 ? 'استلام':'تسليم '}}
                                        </td>
                                        <td>{{$invoice->invoice_date}}</td>
                                        <td>{{$invoice->employee->name}}</td>
                                        <td>
                                            @if($invoice->invoice_type == 2) <!-- إذا كان نوع الفاتورة تسليم -->
                                                {{ $invoice->customer->name ??'-' }} <!-- اسم العميل -->
                                            @else
                                                {{ '-' }} <!-- إذا لم يكن العميل متاحاً -->
                                            @endif
                                        </td>
                                        <td>
                                            @if($invoice->invoice_type == 1) <!-- إذا كان نوع الفاتورة استلام -->
                                                {{ $invoice->supplier->name ??'-'}} <!-- اسم المورد -->
                                            @else
                                                {{ '-' }} <!-- إذا لم يكن المورد متاحاً -->
                                            @endif
                                        </td>
                                        <td>
                                            @if($invoice->invoice_status == 1)
                                            <div class="p-1 bg-info text-white">
                                                تحت تسليم 		  </div>
                                            @elseif ($invoice->invoice_status == 2)
                                            <div class="p-1 bg-secondary text-white" >
                                                
                                            فى توصيل    </div>
                                        @elseif ($invoice->invoice_status == 5)
                                        <div class="p-1 bg-danger text-white" >
                                                ملغى	 </div>
                                            @else

                                            <div class="p-1 bg-success text-white" ><!--invoice_status == 3--->
                                                مكتمل
                                            </div>
                                            @endif

                                            
                                        
                                        </td>
                                        <td>
                                        
                                                {{ $invoice->location->location_name ??'-' }} 
                                        
                                        </td>
                                        <td>{{$invoice->created_at->diffForHumans()}}</td>
                                    
                                    </tr>
              
                              @endforeach
                  </tbody>
        </table>
    @else
        <p>{{ $message ?? 'لا توجد فواتير لعرضها' }}</p>
    @endif

<div class="d-flex justify-content-between">
    <a href="{{route('serial.index')}}" class="btn btn-primary">  رجوع </a>
  
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

<!--Internal  Datepicker js -->
<script src="{{URL::asset('dashboard/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{URL::asset('dashboard/plugins/jquery.maskedinput/jquery.maskedinput.js')}}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{URL::asset('dashboard/plugins/spectrum-colorpicker/spectrum.js')}}"></script>
<!-- Internal Select2.min js -->
<script src="{{URL::asset('dashboard/plugins/select2/js/select2.min.js')}}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{URL::asset('dashboard/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{URL::asset('dashboard/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>
<!-- Ionicons js -->
<script src="{{URL::asset('dashboard/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')}}"></script>
<!-- Internal form-elements js -->
<script src="{{URL::asset('dashboard/js/form-elements.js')}}"></script>
@endsection
