<a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a>
<a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#Deleted{{ $product->id }}"><i  class="fas fa-trash"></i><a>


@include('Dashboard.Admin.Product.Deleted')
