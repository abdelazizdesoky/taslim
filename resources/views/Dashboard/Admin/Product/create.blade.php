@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('title')
  اضافة منتج  جديد
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">منتج </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة منتج  جديد</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('Dashboard.messages_alert')
@include('Dashboard.Admin.Product.brand.add')
@include('Dashboard.Admin.Product.product_type.add')

<!-- row -->
<div class="row">
    <div class="col-lg-8">
        <div class="card ">
            <div class="card-body">
                <form class="form-horizontal" action="{{route('products.store')}}" method="post" autocomplete="off">
                    @csrf
             <!--------------------------------------------------------------->   

             <div class="form-group" id="client-section">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">ماركة -  <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_brand"><i class="fas fa-edit"></i></a></label>
                    </div>
                    <div class="col-md-9">
                        <select class="form-control" name="brand_id" id="brand-select" onchange="updateProductTypes()">
                            <option value="" disabled selected>--اختر ماركة--</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                        <br>
                    </div>
                </div>
            </div>
            
            <div class="form-group" id="client-section">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">نوع المنتج - <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_product_type"><i class="fas fa-edit"></i></a></label>
                    </div>
                    <div class="col-md-9">
                        <select class="form-control" name="type_id" id="product-type-select">
                            <option value="" disabled selected>--اختر نوع المنتج--</option>
                            @foreach($productTypes as $productType)
                                <option value="{{ $productType->id }}" data-brand-id="{{ $productType->brand_id }}">
                                    {{ $productType->type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group" id="client-section">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">المنتج</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="product_name"  value="{{old('product_name')}}" class="form-control @error('product_name') is-invalid @enderror" required>
                    </div>
                </div>
            </div>

            <div class="form-group" id="client-section">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">اسم المنتج  </label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="detail_name"  value="{{old('detail_name')}}" class="form-control @error('detail_name') is-invalid @enderror" required>
                    </div>
                </div>
            </div>

            <div class="form-group" id="client-section">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">كود  المنتج   </label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="product_code"  value="{{old('product_code')}}" class="form-control @error('product_code') is-invalid @enderror" required>
                    </div>
                </div>
            </div>
        
         <!--------------------------------------------------------------->  
                   
                
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
</div>
</div>

<!-- row closed -->
@endsection
@section('js')

<script>
  function updateProductTypes() {
    var brandSelect = document.getElementById('brand-select');
    var productTypeSelect = document.getElementById('product-type-select');

    // احصل على الماركة المختارة
    var selectedBrandId = brandSelect.value;

    // مر على جميع خيارات أنواع المنتجات
    var productTypeOptions = productTypeSelect.options;

    for (var i = 0; i < productTypeOptions.length; i++) {
        var option = productTypeOptions[i];

        // تحقق إذا كانت الماركة المختارة تتوافق مع الـ data-brand-id
        if (option.getAttribute('data-brand-id') == selectedBrandId) {
            option.style.display = ''; // إظهار النوع إذا كانت الماركة مطابقة
        } else {
            option.style.display = 'none'; // إخفاء النوع إذا لم تكن الماركة مطابقة
        }
    }

    // إعادة التحديد بعد التحديث
    productTypeSelect.selectedIndex = 0;
}

</script>


    <script src="{{URL::asset('dashboard/plugins/notify/js/notifIt.js')}}"></script>
    <script src="{{URL::asset('/plugins/notify/js/notifit-custom.js')}}"></script>
@endsection
