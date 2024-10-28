@extends('Dashboard.layouts.master')
@section('css')

@endsection
@section('title')
     سيريال بحث | تسليماتى 
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">سيريال  </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ بحث </span>
						</div>
					</div>
					
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- Row -->
				<div class="row">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-header custom-card-header">
								<h6 class="card-title mb-0">    نتائج البحث عن السيريال: {{ $query ?? '' }}</h6>
							</div>
							<div class="card-body">
								<div class="vtimeline">
									@if(isset($invoices) && $invoices->count() > 0)
									@foreach($invoices as $invoice)

									

									<div class="timeline-wrapper  {{$invoice->invoice_type == 1 ? 'timeline-wrapper-success ':'timeline-inverted  timeline-wrapper-info'}} ">
										<div class="timeline-badge {{$invoice->invoice_type == 1 ? 'success ':'info'}}"><img class="timeline-image" alt="img" src="{{URL::asset('dashboard/img/faces/3.jpg')}}"> </div>
										<div class="timeline-panel">
											<div class="timeline-heading">
												<h6 class="timeline-title"><a href="{{route('admin.invoices.show',$invoice->id)}}">{{$invoice->code}}</h6></a>
											</div>
											<div class="timeline-body">
												<p>
													
												  
													@if($invoice->invoice_type == 1) 

													<div class="p-1 bg-success text-white">	 الاستلام  </div>

													@elseif ($invoice->invoice_type == 3)

													<div class="p-1 bg-secondary text-white"> مرتجعات  </div>
													 @else

													 <div class="p-1 bg-info text-white"> تسليم </div>

												
													@endif

												</p>

                                               <p>
												@if($invoice->invoice_type == 2) <!-- إذا كان نوع الفاتورة تسليم -->

                                                {{ $invoice->customer->name ??'-' }} <!-- اسم العميل -->

                                                @elseif($invoice->invoice_type == 1)

											    {{ $invoice->supplier->name ??'-'}}

												@elseif($invoice->invoice_type == 3)

                                                {{ $invoice->supplier->name ??$invoice->customer->name}}
											     @else
											    {{ '-' }}
                                                 @endif
											   </p>

											</div>
											<div class="timeline-footer d-flex align-items-center flex-wrap">
												<i class="fas fa-edit"></i>
												<span>  - {{$invoice->created_at->diffForHumans()}}</span>
												<span class="mr-auto"><i class="fe fe-calendar text-muted mr-1"></i>{{$invoice->invoice_date}} - </span>
												{{ $invoice->location->location_name ??'-' }} 
											</div>
										</div>
									</div>



									@endforeach
									
									@else
									<p>{{ $message ?? 'لا توجد فواتير لعرضها' }}</p>
								@endif
								</div>
							</div>
						</div>
					</div>


					<div class="d-flex justify-content-between">
						<a href="{{route('admin.serial.index')}}" class="btn btn-primary">  رجوع </a>
					  
					</div>
				</div>
				<!-- End Row -->

			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')

@endsection