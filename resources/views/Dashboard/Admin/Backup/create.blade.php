<!-- Deleted insurance -->
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> نسخ احتياطى </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.manual-backup')}}" method="post">
                    @method('post')
                    @csrf
                 
                    <div class="row">
                        <div class="col">
                            <p class="h5 text-danger">   عمل نسخة احتياطى </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
