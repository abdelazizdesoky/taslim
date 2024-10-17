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
				<!-- row -->
  <div class="row">
       <!-- Col -->
      <div class="col-lg-8">
          <div class="card">
               <div class="card-body">

								<form action="{{ route('serial.invoices.search') }}" method="POST">
									@csrf
									<div class="input-group mb-2">
										<input type="text" class="form-control" name="query" placeholder="أدخل السيريال" autocomplete="off">
									</div>
									<button type="submit" class="btn btn-primary">بحث</button>
								</form>
								
								</div>

							 </form>
							</div>	
						</div>

					</div>
				</div>
				<!-- row closed -->
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