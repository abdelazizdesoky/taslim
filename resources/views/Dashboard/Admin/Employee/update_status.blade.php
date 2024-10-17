<!-- Modal -->
<div class="modal fade" id="update_status{{ $employee->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                   تغير الحالة </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('update_status') }}" method="post" autocomplete="off">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">الحالة </label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="" selected disabled>--اختار الحالة --</option>
                            <option value="1">مفعل</option>
                            <option value="2">غير مفعل </option>
                        </select>
                    </div>

                    <input type="hidden" name="id" value="{{ $employee->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button type="submit" class="btn btn-primary">تاكيد</button>
                </div>
            </form>
        </div>
    </div>
</div>
