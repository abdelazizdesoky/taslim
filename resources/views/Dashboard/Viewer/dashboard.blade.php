@extends('Dashboard.layouts.master')
@section('css')

@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="left-content">
						<div>
						  <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">لوحة التحكم مشرف -{{ Auth::user()->name }}</h2>
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
										<td class="tx-right tx-medium tx-inverse"> <a href="{{route('viewer.invoices.show',$invoice->id)}}">{{$invoice->code}}</a></td>
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
									->take(5) // اختيار الثلاثة الأعلى
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
		<div class="row row-sm">
			<div class="col-xl-4 col-md-12 col-lg-6">
				<div class="card card-table-two">
	
					<h4>📊 عدد الفواتير حسب النوع</h4>
					<canvas id="invoiceTypeChart"></canvas>
					<hr>
	
	
				</div>
			</div>
			<div class="col-xl-4 col-md-12 col-lg-6">
				<div class="card card-table-two">
	
					<h4>📊 عدد الفواتير حسب الحالة</h4>
					<canvas id="invoiceStatusChart"></canvas>
	
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
		<!--Internal  Chart.bundle js -->
		<script src="{{ URL::asset('dashboard/plugins/chart.js/Chart.bundle.min.js') }}"></script>
		<!--Internal Apexchart js-->
		<script src="{{ URL::asset('dashboard/js/apexcharts.js') }}"></script>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				fetch('/viewer/invoice-chart-data')
					.then(response => response.json())
					.then(data => {
						console.log("✅ البيانات المسترجعة:", data);
	
						if (!data.invoiceTypes || !data.invoiceStatuses) {
							console.error("🚨 لا توجد بيانات متاحة!");
							return;
						}
	
						renderInvoiceTypeChart(data.invoiceTypes);
						renderInvoiceStatusChart(data.invoiceStatuses);
					})
					.catch(error => console.error("❌ خطأ أثناء جلب البيانات:", error));
			});
	
			function renderInvoiceTypeChart(invoiceTypes) {
				const typeLabels = {
					1: "استلام",
					2: "تسليم",
					3: "مرتجعات"
				};
	
				const labels = invoiceTypes.map(item => typeLabels[item.invoice_type] || `نوع ${item.invoice_type}`);
				const counts = invoiceTypes.map(item => item.count);
	
				new Chart(document.getElementById('invoiceTypeChart').getContext('2d'), {
					type: 'bar',
					data: {
						labels: labels,
						datasets: [{
							label: 'عدد الفواتير لكل نوع',
							data: counts,
							backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
							borderWidth: 1
						}]
					},
					options: {
						responsive: true,
						scales: {
							y: {
								beginAtZero: true
							}
						}
					}
				});
			}
	
			function renderInvoiceStatusChart(invoiceStatuses) {
				const statusLabels = {
					1: "تحت استلام",
					3: "مكتمل",
					5: "ملغى"
				};
	
				const labels = invoiceStatuses.map(item => statusLabels[item.invoice_status] || `حالة ${item.invoice_status}`);
				const counts = invoiceStatuses.map(item => item.count);
	
				new Chart(document.getElementById('invoiceStatusChart').getContext('2d'), {
					type: 'pie',
					data: {
						labels: labels,
						datasets: [{
							label: 'عدد الفواتير حسب الحالة',
							data: counts,
							backgroundColor: ['#4CAF50', '#36A2EB', '#FF6384'],
							borderWidth: 1
						}]
					},
					options: {
						responsive: true
					}
				});
			}
		</script>
	@endsection
	