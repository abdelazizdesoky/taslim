<!-- Deleted insurance -->
<div class="modal fade" id="Deleted{{$customer->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">حذف بيانات  العميل </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.customers.destroy','test')}}" method="post">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="id" value="{{$customer->id}}">
                    <div class="row">
                        <div class="col">
                            <p class="h5 text-danger"> هل انت متاكد من حذف بيانات العميل ؟ </p>
                            <input type="text" class="form-control" readonly value="{{$customer->name}}">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                        <button class="btn btn-success">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
