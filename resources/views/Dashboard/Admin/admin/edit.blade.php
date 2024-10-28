@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('title')
     تعديل بيانات الادمن 
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">ادمن</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">تعديل بيانات</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('Dashboard.messages_alert')
<!-- row -->
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">

                <form action="{{route('admin.admins.update',['id' => $admin->id])}}" method="post">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="id" value="{{$admin->id}}">

                    <div class="pd-30 pd-sm-40 bg-gray-200">

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    اسم </label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <input class="form-control" name="name" type="text" value="{{$admin->name}}" autofocus>
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                   ايميل  </label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <input class="form-control" name="email" value="{{$admin->email}}"  type="email" disabled>
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    صلاحية  </label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                            <select class="form-control" name="permission"  required>
                                <option value="1" {{ $admin->permission == 1 ? 'selected' : '' }}>ادمن</option>
                                <option value="2" {{ $admin->permission == 2 ? 'selected' : '' }}>منسق</option>
                                <option value="3" {{ $admin->permission == 3 ? 'selected' : '' }}> مندوب</option>
                                <option value="3" {{ $admin->permission == 4 ? 'selected' : '' }}> امين مخزن</option>
                               
                            </select>
                        </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    الحالة   </label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                            <select class="form-control" name="status"  required>
                                <option value="1" {{ $admin->status == 1 ? 'selected' : '' }}>مفعل</option>
                                <option value="2" {{ $admin->status == 2 ? 'selected' : '' }}>غير مفعل </option>
                            </select>
                       
                        </div>
                    </div>
                    <div class="col-md-11 mg-t-5 mg-md-t-0">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#update_password{{$admin->id}}"><i   class="text-primary ti-key"></i>&nbsp;&nbsp;تغير كلمة المرور</a>
                   
                    </div>
                        </div>
                        </div>


                          <div>
                               <button type="submit"  class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">تعديل </button>
                    </div>
                    <br>
                </form>
            
                @include('Dashboard.Admin.admin.update_password')
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')
    <script src="{{URL::asset('dashboard/plugins/notify/js/notifIt.js')}}"></script>
    <script src="{{URL::asset('/plugins/notify/js/notifit-custom.js')}}"></script>
@endsection
