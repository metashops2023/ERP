<form id="edit_group_form" action="{{ route('contacts.customers.groups.update') }}">
    <input type="hidden" name="id" id="id" value="{{$data->id}}">
    <div class="form-group mt-2">
        <label><strong>@lang('Name') :</strong> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control edit_input" data-name="Group name" id="e_name" value="{{$data->group_name}}" />
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-2">
        <label><strong>@lang('Calculation Percent') (%) :</strong></label>
        <input type="number" step="any" name="calculation_percent" class="form-control" id="e_calculation_percent" value="{{$data->calc_percentage}}" />
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

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end">@lang('Save')</button>
            <button type="button" id="close_form" class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>

