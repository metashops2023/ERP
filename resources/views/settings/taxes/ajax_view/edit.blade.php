<form id="edit_tax_form" action="{{ route('settings.taxes.update') }}">
    <input type="hidden" name="id" id="id" value="{{ $data->id }}">
    <div class="form-group">
        <label><b>@lang('Unit Name') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="tax_name" class="form-control edit_input" data-name="Name" id="e_tax_name" value="{{ $data->tax_name }}" placeholder="@lang('Tax Name')" />
        <span class="error error_e_tax_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('Tax Percent') :</b><span class="text-danger">*</span></label>
        <input type="text" name="tax_percent" class="form-control edit_input" data-name="Tax percent" id="e_tax_percent" value="{{ $data->tax_percent }}" placeholder="@lang('Branch Name')" />
        <span class="error error_e_tax_percent"></span>
    </div>

    <div class="form-group mt-1">
        <b>@lang('Business Name') :</b> <span class="text-danger">*</span>
        <select name="update_branch_id" id="update_branch_id" class="form-control">
            @foreach ($branches as $branch)
            <option value="{{ $branch->id }}">
                {{ $branch->name . '/' . $branch->branch_code }}
            </option>
            @endforeach
        </select>
        <!-- <input type="text" name="business_name" class="form-control" placeholder="@lang('Business name')" /> -->
    </div>

    <div class="form-group text-end mt-3">
        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
        <button type="submit" class="me-0 c-btn button-success float-end">@lang('Save')</button>
        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
    </div>
</form>
