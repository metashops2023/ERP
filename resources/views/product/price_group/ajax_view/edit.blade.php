<form id="edit_price_group_form" action="{{ route('product.selling.price.groups.update', $pg->id) }}" method="POST">
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('Name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="e_name" placeholder="@lang('Name')" value="{{ $pg->name }}"/>
            <span class="error error_e_name"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>@lang('Description') :</b></label>
            <textarea name="description" class="form-control" cols="10" rows="3" placeholder="@lang('Price Group Description')">{{ $pg->description }}</textarea>
            <span class="error error_photo"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save Change')</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>
