<a href="{{route('admin.invoices.edit',$Invoice->id)}}" class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a>
<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#Deleted{{$Invoice->id}}"><i class="fas fa-trash"></i></button>
<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#cancel{{$Invoice->id}}"><i class="fas ti-close"></i></button>

@include('Dashboard.Admin.Invoices.Deleted')
@include('Dashboard.Admin.Invoices.cancel')
