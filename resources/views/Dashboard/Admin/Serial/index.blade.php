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
							<h4 class="content-title mb-0 my-auto">سيريال </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ بحث </span>
						</div>
					</div>
					
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
  <div class="row">
       <!-- Col -->
      <div class="col-lg-8">
          <div class="card">
               <div class="card-body">

								<form action="{{ route('admin.invoices.search') }}" method="POST">
									@csrf
									<div class="input-group mb-2">
										<input type="text" class="form-control" name="query" placeholder="أدخل السيريال" autocomplete="off">
									</div>
									<button type="submit" class="btn btn-primary">بحث</button>
								</form>
								
								</div>

							 </form>
							</div>	
						</div>

					</div>
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')

@endsection