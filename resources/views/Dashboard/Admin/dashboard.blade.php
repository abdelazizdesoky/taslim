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
							<label class="tx-13"> عدد سيريال    </label>
							<h5>{{\App\Models\SerialNumber::count()}}</h5>
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
						<h3 class="card-title mb-2">المندوبون الأكثر مسحًا للسيريالات</h3>
						<p class="tx-12 mb-0 text-muted">أعلى  مندوبين قاموا بمسح أكبر عدد من السيريالات</p>
						
					</div>
					<div class="product-timeline card-body pt-2 mt-1">
						<ul class="timeline-1 mb-0">
							@php
								// استعلام للحصول على المندوبين وعدد العمليات
								$topEmployees = \App\Models\Admin::select('admins.*')
									->join('invoices', 'admins.id', '=', 'invoices.employee_id')
									->selectRaw('COUNT(invoices.id) as scan_count')
									->whereIn('admins.permission', [3, 4]) // التأكد من صلاحية المندوب
									->groupBy('admins.id') // تجميع حسب المندوب
									->orderByDesc('scan_count') // ترتيب تنازلي
									->take(4) // اختيار الثلاثة الأعلى
									->get();
							@endphp
			
							@foreach($topEmployees as $employee)
								<li class="mt-0 mb-0">
									<i class="icon-note icons bg-primary-gradient text-white product-icon"></i>
									<span class="font-weight-semibold mb-4 tx-14">- {{ $employee->name }}</span>
									<p class="mb-0 text-muted tx-12">
									|	عدد عمليات المسح: {{ $employee->scan_count }}
									</p>
									<br>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</div>
			<!-- row close -->
			
			<!-- row opened -->
{{-- <div class="row row-sm row-deck">
    <div class="col-md-12 col-lg-4 col-xl-4">
        <div class="card card-dashboard-eight pb-2">
            <h6 class="card-title">المنتجات   </h6>
            <span class="d-block mg-b-10 text-muted tx-12"> اكبر عدد منتجات تم ادخال سيريالات لها بالسيستم   .</span>
            <div class="list-group">
			@php
				use App\Models\SerialNumber;
				use App\Models\Product;

				// جلب السيريالات
				$serials = SerialNumber::all();

				// تجميع السيريالات حسب أول 7 أرقام بعد إزالة الأصفار
				$products = $serials->groupBy(function ($serial) {
					// إزالة الأصفار الزائدة من السيريال واستخراج أول 7 أرقام
					$serialPrefix = ltrim(substr($serial->serial_number, 0, 7), '0');
					
					// البحث عن المنتج بناءً على الـ product_code
					return Product::where('product_code', $serialPrefix)->first(); // إرجاع المنتج المطابق
				})->filter();

				// حساب عدد السيريالات لكل منتج
				$productSerialCounts = $products->map(function ($serials, $product) {
					return [
						'product' => $product, // تأكد من أن المنتج ليس فارغًا
						'serial_count' => $serials->count(),
					];
				})->sortByDesc('serial_count')->take(5); // ترتيب حسب عدد السيريالات واختيار الخمس الأوائل
			
		
			@endphp

				<div class="list-group">
					@foreach($productSerialCounts as $productData)
					@php
						// فك ترميز المنتج إذا كان JSON
						$product = $productData['product'];
						if (is_string($product)) {
							$product = json_decode($product); // تحويل JSON إلى كائن
						}
					@endphp
					<div class="list-group-item border-top-0">
						<i class="fe fe-shopping-cart tx-20"></i>
						<p>
							@if ($product && property_exists($product, 'product_name')) {{-- تحقق من وجود اسم المنتج --}}
								{{-- {{ $product->product_name }}
							@else
								غير معرف بالمنتجات
							@endif
						</p>
						<span>{{ $productData['serial_count'] }} سيريال</span>
					</div>
				@endforeach

				</div>

				
            </div>
        </div>
    </div> --}} 


	<div class="col-md-12 col-lg-8 col-xl-8">
		<div class="card card-table-two">
			<div class="d-flex justify-content-between">
				<h4 class="card-title mb-1">Logs System Taslim </h4>
				<i class="mdi mdi-dots-horizontal text-gray"></i>
			</div>
			<span class="tx-12 tx-muted mb-3 ">This is Last 5 Logs in the System .. </span>
			<div class="table-responsive country-table">
				<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
					<thead>
						<tr>
										 <th>#</th>
										<th>السجل</th>
									  <th>التاريخ</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							@foreach(\Spatie\Activitylog\Models\Activity::latest()->take(5)->get() as $log)
							<tr>
								<td>{{ $log->id }}</td>
								<td>
									@php
										$admin = \App\Models\Admin::find($log->causer_id);
										$actionMap = [
											'updated' => ' تعديل',
											'deleted' => 'حذف',
											'created' => '  إنشاء'
										];
										$tableMap = [
											'Admin' => 'مستخدم',
											'invoice' => 'الأذون',
											'customers' => 'عملاء',
											'suppliers' => 'موردين',
											'serial_number' => 'سيريال',
											'Product' => 'منتجات',
											'InvoiceProduct' => 'أذن منتجات',
											'Location' => 'موقع',
											'ProductType' => 'نوع منتج'
										];
									@endphp
	
									{{ $admin->name ?? 'Unknown' }} - 
									{{ $actionMap[$log->description] ?? 'غير معرف' }} 
									في جدول 
									{{ $tableMap[$log->log_name] ?? 'غير معرف' }}
								</td>
	
								<td>{{ $log->created_at }}</td>
							</tr>
						@endforeach
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
