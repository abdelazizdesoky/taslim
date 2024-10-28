@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('title')
  اضافة مورد  جديد
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">مورد </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة مورد  جديد</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('Dashboard.messages_alert')
<!-- row -->
<div class="row">
    <div class="col-lg-8">
        <div class="card ">
            <div class="card-body">
              
                <form class="form-horizontal" action="{{route('admin.supplier.store')}}" method="post" autocomplete="off">
                    @csrf
                 
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">كود مورد</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="code"  value="{{old('code')}}" class="form-control @error('code') is-invalid @enderror" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">اسم مورد</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="name"  value="{{old('name')}}" class="form-control @error('name') is-invalid @enderror" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label"> العنوان</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="address"  value="{{old('address')}}" class="form-control @error('address') is-invalid @enderror" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label"> تليفون</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="phone"  value="{{old('phone')}}" class="form-control @error('phone') is-invalid @enderror" required>
                                </div>
                            </div>
                        </div>
                        
									
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">حالة المورد </label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control " name="status" >
                                        <option value="1">مفعل </option>
                                        <option value="2">غير مفعل </option>	
                                    </select>
                                </div>
                            </div>	
                        </div>


                
                    <div class="card-footer text-left">
                        <button type="submit" class="btn btn-success waves-effect waves-light">حفظ </button>
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
