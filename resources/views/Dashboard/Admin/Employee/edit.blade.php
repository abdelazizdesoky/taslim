@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('title')
     تعديل موظف
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">موظف</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">تعديل بيانات</span>
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
                <form action="{{route('employee.update','test')}}" method="post">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="id" value="{{$employee->id}}">

                    <div class="pd-30 pd-sm-40 bg-gray-200">

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                    اسم الموظف</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <input class="form-control" name="name" type="text" value="{{$employee->name}}" autofocus>
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                   كود الموظف</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <input class="form-control" name="code"value="{{$employee->code}}"  type="number">
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                    <label class="form-label"> المهام</label>
                                </div>
                                <div class="col-md-9">
                                     <select class="form-control" name="type" required>
                            <option value="1" {{ $employee->type == 2 ? 'selected' : '' }}>امين مخزن</option>
                            <option value="2" {{ $employee->type == 1 ? 'selected' : '' }}>مندوب تسليم </option>
                        </select>
                                </div>
                            </div>    
                        

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                   تليفون</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <input class="form-control" name="phone" type="tel" value="{{$employee->phone}}">
                            </div>
                        </div>

                        
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-1">
                                <label for="exampleInputEmail1">
                                   الحالة</label>
                            </div>
                            <div class="col-md-11 mg-t-5 mg-md-t-0">
                                <select class="form-control" name="status">
                                    <option value="1" {{$employee->status == 1 ? 'selected':''}}>مفعلة</option>
                                    <option value="2" {{$employee->status == 2 ? 'selected':''}}>غير مفعل</option>
                                </select>
    
                            </div>
                        </div>


                        </div>
                        </div>

                          <div>
                               <button type="submit"  class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">اضافة</button>
                    </div>
                    <br>
                </form>
                   
                            
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
