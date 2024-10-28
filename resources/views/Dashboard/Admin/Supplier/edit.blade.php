@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('title')
   اضافة مورد جديدة
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">مورد</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة مورد جديدة</span>
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
                <form action="{{route('admin.supplier.update','test')}}" method="post">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="id" value="{{$Supplier->id}}">
                    <div class="row">
                        <div class="col">
                            <label>كود العميل </label>
                            <input type="text" name="code"  value="{{$Supplier->code}}" class="form-control @error('code') is-invalid @enderror">
                           </div>

                        <div class="col">
                            <label>اسم العميل </label>
                            <input type="text" name="name"  value="{{$Supplier->name}}" class="form-control @error('name') is-invalid @enderror">
                        </div>

                        <div class="col">
                            <label>العنوان </label>
                            <input type="text" name="address"  value="{{$Supplier->address}}" class="form-control @error('address') is-invalid @enderror">
                        </div>

                        <div class="col">
                            <label> الحالة </label>
                            <select class="form-control" name="status">
                                <option value="1" {{$Supplier->status == 1 ? 'selected':''}}>مفعلة</option>
                                <option value="2" {{$Supplier->status == 2 ? 'selected':''}}>غير مفعل</option>
                            </select>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3">
                            <label> التليفون</label>
                            <input type="phone" name="phone"  value="{{$Supplier->phone}}" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                </div>

                    

                    <div class="row">
                        <div class="col">
                            <button class="btn btn-success">حفظ البيانات</button>
                        </div>
                    </div>

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
