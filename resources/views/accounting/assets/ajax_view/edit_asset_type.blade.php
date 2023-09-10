<form id="edit_assset_type_form" action="{{ route('accounting.assets.asset.type.update', $type->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('Type Name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="asset_type_name" class="form-control" id="e_asset_type_name"
                placeholder="@lang('Asset Type name')" value="{{ $type->asset_type_name }}"/>
            <span class="error error_e_asset_type_name"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>@lang('Type Code') :</b> </label>
            <input type="text" name="asset_type_code" class="form-control" placeholder="@lang('Asset Type Code')" value="{{ $type->asset_type_code }}"/>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i
                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end">@lang('Save Change')</button>
            <button type="reset" data-bs-dismiss="modal"
                class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>
