<!-- Deleted insurance -->
<div class="modal fade" id="cancelserial{{$serial->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">  حذف سيريال    </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.invoices.cancelserial','test')}}"method="post">
                    {{ csrf_field() }}
                    @csrf
                    <input type="hidden" name="id" value="{{$serial->id}}">
                    <div class="row">
                        <div class="col">
                            <p class="h5 text-danger"> هل انت متاكد من  حذف سيريال  ؟ </p>
                            <input type="text" class="form-control" readonly value="{{$serial->serial_number}}">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                        <button class="btn btn-info">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
