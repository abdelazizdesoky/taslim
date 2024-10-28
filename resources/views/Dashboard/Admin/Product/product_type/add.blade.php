<!-- Modal -->
<div class="modal fade" id="add_product_type" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">اضافة ماركة </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.product-types.store') }}" method="post" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div class="form-group .d-none" id="client-section" >
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">ماركة </label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control " name="brand_id">
                                    <option value="" disabled>--اختر ماركة </option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" >{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>    
                    </div>

                    <label for="exampleInputPassword1">نوع المنتج </label>
                    <input type="text" name="type_name" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button type="submit" class="btn btn-primary">تاكيد</button>
                </div>
            </form>
        </div>
    </div>
</div>