@extends('Dashboard.layouts.master2')
@section('title')
برنامج  تسليماتى 
@endsection
@section('css')
{{-- <style>
    @media (max-width: 768px) {
        #form2-tab, #form3-tab {
            display: none; 
        }
    }
</style> --}}
@endsection
@section('content')
<!--lang bottom-->
<div class="container-fluid ">
    <div class="row no-gutter">
        <!-- The image half -->
        <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
            <div class="row wd-100p mx-auto text-center">
                <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
                    <img src="{{URL::asset('dashboard/img/media/login.png')}}" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
                </div>
            </div>
        </div>
        <!-- The content half -->
        <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
            <div class="login d-flex align-items-center py-2">
                <!-- Demo content-->
                <div class="container p-0">
                    <div class="row ">
                        <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                            <div class="card-sigin ">
                                <div class="mb-5 d-flex"> 
                                    <a href="#"><img src="{{URL::asset('dashboard/img/brand/favicon.png')}}" class="sign-favicon ht-40" alt="logo"></a>
                                    <h1 class="main-logo1 ml-1 mr-0 my-auto tx-28"></h1>
                                </div>
                                <div class="card-sigin ">
                                    <div class="main-signup-header">
                                        <h2> مرحبا </h2>
                                        <br>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                            
                                                <form method="POST" action="{{ route('login.admin') }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label> الايميل</label> 
                                                        <input class="form-control" placeholder="ادخل الايميل" type="email" name="email" required autofocus autocomplete="username">
                                                    </div>
                                                    <div class="form-group">
                                                        <label> الباسورد</label> 
                                                        <input class="form-control" placeholder="ادخل الباسورد" type="password" name="password" required autocomplete="current-password" />
                                                    </div>
                                                    <button class="btn btn-main-primary btn-block"> دخول </button>
                                                </form>
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End -->
            </div>
        </div><!-- End -->
    </div>
</div>
@endsection

@section('js')

@endsection
