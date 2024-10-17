@extends('Dashboard.layouts.master2')
@section('css')
<!--- Internal Fontawesome css-->
<link href="{{URL::asset('dashboard/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<!---Ionicons css-->
<link href="{{URL::asset('dashboard/plugins/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
<!---Internal Typicons css-->
<link href="{{URL::asset('dashboard/plugins/typicons.font/typicons.css')}}" rel="stylesheet">
<!---Internal Feather css-->
<link href="{{URL::asset('dashboard/plugins/feather/feather.css')}}" rel="stylesheet">
<!---Internal Falg-icons css-->
<link href="{{URL::asset('dashboard/plugins/flag-icon-css/css/flag-icon.min.css')}}" rel="stylesheet">
@endsection
@section('content')
		<!-- Main-error-wrapper -->
		<div class="main-error-wrapper  page page-h ">
			<img src="{{URL::asset('dashboard/img/media/404.png')}}" class="error-page" alt="error">
			<h2>عفوا لا يمكن الوصول  الى صفحة  </h2>
			<h6>ربما تكون الصفحة غير موجودة او لا يوجد صالحة للوصول لها  .</h6><a class="btn btn-outline-danger" href="{{route ('login')}}">Back to Home</a>
		</div>
		<!-- /Main-error-wrapper -->
@endsection
@section('js')
@endsection