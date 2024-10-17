@extends('Dashboard.layouts.master')
@section('css')
<!--  Owl-carousel css-->
<link href="{{URL::asset('dashboard/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />
<!-- Maps css -->
<link href="{{URL::asset('dashboard/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
		
				
				<!-- /breadcrumb -->
								<!-- breadcrumb -->
								<div class="breadcrumb-header justify-content-between">
									<div class="left-content">
										<div>
										  <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1"> -{{ Auth::user()->name }}</h2>
										</div>
									</div>
									<div class="d-flex my-xl-auto right-content">

										@if ((Auth::user()->type == 2) )
										<div class="pr-1 mb-3 mb-xl-0">
											<a href="{{route('employeeinvoice.index')}}">
											<button type="button" class="btn btn-info  ml-2"><i class="mdi mdi-filter-variant"></i>الاذون تسليم </button>
										</a>
										</div>
										@endif

										@if ((Auth::user()->type ==1) )
										<div class="pr-1 mb-3 mb-xl-0">
											<a href="{{route('employeeinvoice.show',1)}}">
											<button type="button" class="btn btn-warning   ml-2"><i class="mdi mdi-refresh"></i>الاذون استلام </button>
											</a>
										</div>
										@endif

										<div class="pr-1 mb-3 mb-xl-0">
											<a href="{{route('Compinvoice')}}">
											<button type="button" class="btn btn-danger  ml-2"><i class="mdi mdi-star"></i>الاذون مكتملة</button>
										</a>
										</div>
									
									</div>
								</div>
								<!-- /breadcrumb -->
				
@endsection
@section('content')
	<!-- row -->
	
	<div class="row">

	</div>
	<!-- row opened -->
	
	<!-- /row -->
</div>
</div>
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('dashboard/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!-- Moment js -->
<script src="{{URL::asset('dashboard/plugins/raphael/raphael.min.js')}}"></script>
<!--Internal  Flot js-->
<script src="{{URL::asset('dashboard/plugins/jquery.flot/jquery.flot.js')}}"></script>
<script src="{{URL::asset('dashboard/plugins/jquery.flot/jquery.flot.pie.js')}}"></script>
<script src="{{URL::asset('dashboard/plugins/jquery.flot/jquery.flot.resize.js')}}"></script>
<script src="{{URL::asset('dashboard/plugins/jquery.flot/jquery.flot.categories.js')}}"></script>
<script src="{{URL::asset('dashboard/js/dashboard.sampledata.js')}}"></script>
<script src="{{URL::asset('dashboard/js/chart.flot.sampledata.js')}}"></script>
<!--Internal Apexchart js-->
<script src="{{URL::asset('dashboard/js/apexcharts.js')}}"></script>
<!-- Internal Map -->
<script src="{{URL::asset('dashboard/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{URL::asset('dashboard/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<script src="{{URL::asset('dashboard/js/modal-popup.js')}}"></script>
<!--Internal  index js -->
<script src="{{URL::asset('dashboard/js/index.js')}}"></script>
<script src="{{URL::asset('dashboard/js/jquery.vmap.sampledata.js')}}"></script>
@endsection
