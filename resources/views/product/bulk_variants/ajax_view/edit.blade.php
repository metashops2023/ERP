<div class="form-area px-3 pb-2">
    <form id="edit_variant_form" action="{{ route('product.variants.update') }}">
        <input type="hidden" name="id" id="id" value="{{ $data->id }}">
        <div class="form-group">
            <b>@lang('Name') :</b> <span class="text-danger">*</span>
            <input type="text" name="variant_name" class="form-control edit_input"
                data-name="Brand name" id="e_variant_name" placeholder="@lang('Brand Name')"  value="{{$data->bulk_variant_name}}" />
            <span class="error error_e_variant_name"></span>
        </div>

        <div class="form-group row mt-2">
            <div class="col-md-12"><b>@lang('Variant Childs (Values)') :</b> <span class="text-danger">*</span></div>
            <div class="col-md-10">
                <input type="hidden" name="variant_child_ids[]" id="e_variant_child_id" value="">
                <input required type="text" name="variant_child[]" class="form-control"
                    id="e_variant_child" placeholder="@lang('Variant child')" />
            </div>

            <div class="col-md-2 text-end">
                <a class="btn btn-sm btn-primary add_more_for_edit" href="#">+</a>
            </div>
        </div>

        <div class="form-group more_variant_child_area_edit">

        </div>

        <div class="form-group row mt-2">
            <div class="col-md-12">
                <button type="button" class="btn loading_button d-none"><i
                        class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                <button type="submit" class="c-btn me-0 button-success float-end">@lang('Save Changes')</button>
                <button type="button" data-bs-dismiss="modal"
                    class="c-btn btn_orange float-end" id="close_form">@lang('Close')</button>
            </div>
        </div>
    </form>
</div>
