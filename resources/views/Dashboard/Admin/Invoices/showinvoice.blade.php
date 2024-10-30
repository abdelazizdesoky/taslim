@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الاذن </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عرض الاذن </span>
						</div>
					</div>
				
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row row-sm">
					<div class="col-md-12 col-xl-12">
						<div class=" main-content-body-invoice">
							<div class="card card-invoice">
								<div class="card-body">
									<div class="invoice-header">
										<h1 class="invoice-title">اذن 
											@switch($invoice->invoice_type)
											@case(1)
											استلام
											@break

											@case(2)
											تسليم
											@break

											@default
											مرتجعات
											@endswitch

										</h1>
										<div class="billed-from">
											<h6>العربية جروب    </h6>
											        16399
											<h6>  ناقل للتوزيع  </h6>
											<p>مخازن رمسيس </p>
											
										</div><!-- billed-from -->
									</div><!-- invoice-header -->
									<div class="row mg-t-20">
										<div class="col-md">
											<label class="tx-gray-600"> </label>
											<div class="billed-to">
												<h6></h6>
												<p><br>
												<br>
												
												</p>
											</div>
										</div>
										<div class="col-md">
											<label class="tx-gray-600"> تفاصيل الاذن </label>
											<p class="invoice-info-row"><span>الاذن  </span> <span>{{ $invoice->code }}</span></p>
											<p class="invoice-info-row"><span>تاريخ  </span> <span>{{ $invoice->invoice_date }}</span></p>

											@switch($invoice->invoice_type)
											@case(1)
											<p class="invoice-info-row"><span>المورد  :</span> <span>{{ $invoice->supplier->name ??'-' }}-{{ $invoice->supplier->phone ??'-' }}</span></p>
											@break

											@case(2)
											<p class="invoice-info-row"><span>العميل  :</span> <span>{{ $invoice->customer->name ??'-' }}-{{ $invoice->customer->phone ??'-' }}</span></p>
											@break

											@default
											<p class="invoice-info-row"><span>مورد/العميل   :</span> <span>{{ $invoice->customer->name ??$invoice->supplier->name }}-{{ $invoice->customer->phone ??$invoice->supplier->phone }}</span></p>
											@endswitch
												
											
										
											<p class="invoice-info-row"><span>المندوب :</span> <span>{{ $invoice->admin->name ??'-' }}</span></p>
											<p class="invoice-info-row"><span>مجموع سيريالات المسحوبة  :</span> <span>{{$serials->count()}}</span></p>
										</div>
									</div>
									<div class="table-responsive mg-t-20">
										<table class="table table-striped mg-b-0 text-md-nowrap">
											<thead>
												<tr>
													<th class="wd-10p">#</th>
													<th class="wd-20p">سيريال </th>
													<th class="wd-20p">المنتج </th>
													<th class="wd-20p">تاريخ سحب </th>
												
												</tr>
											</thead>
											<tbody>


												@foreach($serials as $serial)
												<tr>
													<td>{{ $loop->iteration }}</td>
													<td>{{ $serial->serial_number }}</td>
													<td>
														@php
															// استخراج أول 6 أرقام من السيريال
															$serialPrefix = substr($serial->serial_number, 0, 6);
															// البحث عن المنتج باستخدام الكود المستخرج
															$product = \App\Models\Product::where('product_code', $serialPrefix)->first();
														@endphp
											
														@if ($product)
															{{-- عرض تفاصيل المنتج إذا كان المنتج موجوداً --}}
															{{ $product->productType->type_name ?? 'نوع غير موجود' }}
															{{ $product->productType->brand->brand_name ?? 'ماركة غير موجودة' }}
															{{ $product->product_name ?? 'اسم المنتج غير موجود' }}
															{{ $product->detail_name ?? 'تفاصيل غير موجودة' }}
														@else
															{{-- عرض رسالة في حال عدم وجود المنتج --}}
															{{ 'غير موجود بالمنتجات' }}
														@endif
													</td>    
													<td>{{ $serial->created_at }}</td>
												</tr>
											@endforeach

												
											
											<tr>
												<th class="wd-10p">#</th>
												<th class="wd-20p">المنتج  </th>
												<th class="wd-20p">العدد  </th>
												
											</tr>
											@foreach($productSerialCounts as $productData)
											<tr>
												<td>{{ $loop->iteration }}</td>
												<td>{{ $productData['product_name'] ?? 'اسم المنتج غير موجود' }}</td>
												<td>{{ $productData['serial_count'] }}</td>
											</tr>
										@endforeach
										
											</tbody>
										</table>
									</div>
									<hr class="mg-b-40">
									
									<a href="#" class="btn btn-danger float-left mt-3 mr-2">
										<i class="mdi mdi-printer ml-1"></i>Print
									</a>
							
									<a href="#" class="btn btn-success float-left mt-3" id="save-excel">
										<i class="mdi mdi-telegram ml-1"></i>Save as Excel
									</a>
								</div>
							</div>
						</div>
					</div><!-- COL-END -->
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')

	<!-- Add SheetJS library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>


    <!-- Print functionality -->
    <script>
	// Print functionality
document.querySelector('.btn-danger').addEventListener('click', function () {
    window.print();
});

// Save to Excel functionality
document.querySelector('.btn-success').addEventListener('click', function () {
    // Fetch the table and invoice details
    var table = document.querySelector('.table-striped'); // Update the selector if necessary
    var invoiceCode = "{{ $invoice->code }}";
    var customerName = "{{ $invoice->customer->name ?? '-' }}";
    var employeeName = "{{ $invoice->employee->name ?? '-' }}";
    var serialsCount = "{{ $serials->count() }}";

    // Create a new workbook and add a worksheet
    var wb = XLSX.utils.book_new();

    // Add invoice details and serial numbers to the same sheet
    var wsData = [];

    // Add invoice details
    wsData.push(["كود الفاتورة:", invoiceCode]);
    wsData.push(["العميل:", customerName]);
    wsData.push(["المندوب:", employeeName]);
    wsData.push(["مجموع سيريالات المسحوبة:", serialsCount]); // Add serial count

    // Add an empty row for spacing
    wsData.push([]);

    // Add table headers
    wsData.push(["#", "سيريال", "المنتج", "تاريخ سحب"]);

    // Add table data
    var rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        var cells = row.querySelectorAll('td');
        var rowData = [];
        cells.forEach(cell => {
            rowData.push(cell.innerText);
        });
        wsData.push(rowData);
    });

    // Create a sheet and add the data
    var ws = XLSX.utils.aoa_to_sheet(wsData);
    XLSX.utils.book_append_sheet(wb, ws, "Invoice Data");

    // Save the workbook
    XLSX.writeFile(wb, invoiceCode + '-invoice.xlsx');
});

    </script>

<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
@endsection