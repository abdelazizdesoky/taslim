@extends('Dashboard.layouts.master')
@section('css')

@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="left-content">
						<div>
						  <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">لوحة التحكم الادمن -{{ Auth::user()->name }}</h2>
						</div>
					</div>
					<div class="main-dashboard-header-right">
						<div>
							<label class="tx-13">عدد الاذون   </label>
							<h5>{{\App\Models\Invoice::count()}}</h5>
						</div>
						<div>
							<label class="tx-13">عدد المنديب   </label>
							<h5>{{\App\Models\Admin::whereIn('permission', [3,4])->count()}}</h5>
						</div>
					</div>
				</div>
				<!-- /breadcrumb -->


@endsection
@section('content')
			<!-- row -->
			<div class="row row-sm">
				<div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
					<div class="card overflow-hidden sales-card bg-primary-gradient">
						<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
							<div class="">
								<h6 class="mb-3 tx-12 text-white">الاذون المفعلة  </h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white"> {{\App\Models\Invoice::where('invoice_status', 1)->count()}}</h4>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				<div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
					<div class="card overflow-hidden sales-card bg-danger-gradient">
						<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
							<div class="">
								<h6 class="mb-3 tx-12 text-white">عدد الموردين </h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white">{{\App\Models\Supplier::count()}}</h4>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				<div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
					<div class="card overflow-hidden sales-card bg-success-gradient">
						<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
							<div class="">
								<h6 class="mb-3 tx-12 text-white">العملاء</h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white">{{\App\Models\Customers::count()}}</h4>
									</div>
								</div>
							</div>
						</div>
				
					</div>
				</div>
			</div>
			<!-- row closed -->

			

			<!-- row opened -->
			<div class="row row-sm">
					
			

			
				<div class="col-md-12 col-lg-8 col-xl-8">
					<div class="card card-table-two">
						<div class="d-flex justify-content-between">
							<h4 class="card-title mb-1">اخر فواتير </h4>
							<i class="mdi mdi-dots-horizontal text-gray"></i>
						</div>
						<span class="tx-12 tx-muted mb-3 ">اخر خمس حركات الاذون تمت على النظام .</span>
						<div class="table-responsive country-table">
							<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
								<thead>
									<tr>
										<th class="wd-lg-25p">تاريخ الفاتورة</th>
										<th class="wd-lg-25p tx-right"> كود الاذن</th>
										<th class="wd-lg-25p tx-right">عميل/مورد</th>
										<th class="wd-lg-25p tx-right">عدد السيريات المسحوبة  </th>
									</tr>
								</thead>
								<tbody>
								
									@foreach(\App\Models\Invoice::latest()->take(5)->get()  as $invoice)
										<tr>
										<td>{{$invoice->invoice_date}}</td>
										<td class="tx-right tx-medium tx-inverse"> <a href="{{route('admin.invoices.show',$invoice->id)}}">{{$invoice->code}}</a></td>
										<td class="tx-right tx-medium tx-inverse">		
												@if($invoice->invoice_type == 2) 
												{{ $invoice->customer->name ??'-' }} 
											@else
												{{ $invoice->supplier->name ??'-'}} 
											@endif
										</td>
										<td class="tx-right tx-medium tx-danger">{{App\Models\SerialNumber::where('invoice_id',$invoice->id )->count()}}</td>
									</tr>
										@endforeach
									
									
								</tbody>
							</table>
						</div>
					</div>
						
			</div>

				<div class="col-xl-4 col-md-12 col-lg-6">
					<div class="card">
						<div class="card-header pb-1">
							<h3 class="card-title mb-2">منديب التسليم  </h3>
							<p class="tx-12 mb-0 text-muted">اخر ثلاثة منديب تم اضافتهم على السيستم </p>
					<br>
						</div>
						<div class="product-timeline card-body pt-2 mt-1">
							<ul class="timeline-1 mb-0">

								 @foreach(\App\Models\admin::whereIn('permission', [3,4])->take(3)->get()  as $employee)

							 <li class="mt-0 mb-0"> <i class="icon-note icons bg-primary-gradient text-white product-icon"></i> <span class="font-weight-semibold mb-4 tx-14 ">- {{$employee->name}}</span> <a href="#" class="float-left  tx-11 text-muted"></a>
									<p class="mb-0 text-muted tx-12">عدد الاذون 
										 <p></p>{{\App\Models\Invoice::where('employee_id', $employee->id )->count()}} </p> 
								</li>
                           @endforeach 
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- row close -->
			
			<!-- row opened -->
<div class="row row-sm row-deck">
    <div class="col-md-12 col-lg-4 col-xl-4">
        <div class="card card-dashboard-eight pb-2">
            <h6 class="card-title">المنتجات   </h6>
            <span class="d-block mg-b-10 text-muted tx-12">اكبر عدد منتجات تم ادخال سيريالات لها بالسيستم   .</span>
            <div class="list-group">

                @php
                // جلب كل السيريالات
                $serials = \App\Models\SerialNumber::all(); // تأكد من استخدام الموديل الصحيح

                // تجميع السيريالات حسب المنتج
                $products = $serials->groupBy(function ($serial) {
                    // استخراج أول 6 أرقام من السيريال
                    $serialPrefix = substr($serial->serial_number, 0, 6);
                    $productCode = \App\Models\Product::where('product_code', $serialPrefix)->first();
                    return $productCode && $productCode->product ? $productCode->product : null;
                })->filter();

                // حساب عدد السيريالات لكل منتج وترتيبها بشكل تنازلي واختيار الخمس الأوائل
                $productSerialCounts = $products->map(function ($serials, $product) {
                    return [
                        'product' => $product,
                        'serial_count' => $serials->count(),
                    ];
                })->sortByDesc('serial_count')->take(5);
                @endphp

                @foreach($productSerialCounts as $index => $productData)
                    @php
                        $product = $productData['product'];
                    @endphp
                    <div class="list-group-item border-top-0">
						<i class="fe fe-shopping-cart tx-20"></i>
                        <p>

						@foreach (\App\Models\Product::all() as $product2)
                            
							@if ($product2 ==  $product )

							{{ $product2->productType->type_name }}
							{{ $product2->productType->brand->brand_name }}
							 {{ $product2->product_name }}

						   @foreach($product2->productDetails as $detail)
						   {{ $detail->detail_name }} 
						   @endforeach
					
							@endif
							
						   @endforeach

						   @if (! $product)
                          غير معرف بالمنتجات 
                          @endif

                        </p>
                        <span>{{ $productData['serial_count'] }} سيريال</span>
                    </div>
                @endforeach

            </div>
        </div>
    </div>


<div class="col-md-12 col-lg-8 col-xl-8">
	<div class="card card-table-two">
		<div class="d-flex justify-content-between">
			<h4 class="card-title mb-1">Your Most Recent Earnings</h4>
			<i class="mdi mdi-dots-horizontal text-gray"></i>
		</div>
		<span class="tx-12 tx-muted mb-3 ">This is your most recent earnings for today's date.</span>
		<div class="table-responsive country-table">
			<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
				<thead>
					<tr>
						<th class="wd-lg-25p">Date</th>
						<th class="wd-lg-25p tx-right">Sales Count</th>
						<th class="wd-lg-25p tx-right">Earnings</th>
						<th class="wd-lg-25p tx-right">Tax Witheld</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>05 Dec 2019</td>
						<td class="tx-right tx-medium tx-inverse">34</td>
						<td class="tx-right tx-medium tx-inverse">$658.20</td>
						<td class="tx-right tx-medium tx-danger">-$45.10</td>
					</tr>
					<tr>
						<td>06 Dec 2019</td>
						<td class="tx-right tx-medium tx-inverse">26</td>
						<td class="tx-right tx-medium tx-inverse">$453.25</td>
						<td class="tx-right tx-medium tx-danger">-$15.02</td>
					</tr>
					<tr>
						<td>07 Dec 2019</td>
						<td class="tx-right tx-medium tx-inverse">34</td>
						<td class="tx-right tx-medium tx-inverse">$653.12</td>
						<td class="tx-right tx-medium tx-danger">-$13.45</td>
					</tr>
					<tr>
						<td>08 Dec 2019</td>
						<td class="tx-right tx-medium tx-inverse">45</td>
						<td class="tx-right tx-medium tx-inverse">$546.47</td>
						<td class="tx-right tx-medium tx-danger">-$24.22</td>
					</tr>
					<tr>
						<td>09 Dec 2019</td>
						<td class="tx-right tx-medium tx-inverse">31</td>
						<td class="tx-right tx-medium tx-inverse">$425.72</td>
						<td class="tx-right tx-medium tx-danger">-$25.01</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>
	

			</div>
			<!-- /row -->
		</div>
	</div>
	<!-- Container closed -->
@endsection
@section('js')

@endsection
