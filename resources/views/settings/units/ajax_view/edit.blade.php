 <!--begin::Form-->
 <form id="edit_unit_form" class="p-2" action="{{ route('settings.units.update') }}">
    <input type="hidden" name="id" id="id" value="{{ $data->id }}">
    <div class="form-group">
        <label><b>@lang('Unit Name') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" data-name="Name" id="e_name" value="{{$data->name}}" placeholder="@lang('Unit Name')"  />
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('Short Name') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control" data-name="Code name" id="e_code"  value="{{$data->code_name}}" placeholder="@lang('Short Name')" />
        <span class="error error_e_code"></span>
    </div>

    <div class="form-group mt-1">
        <b>@lang('Business Name') :</b> <span class="text-danger">*</span>
        <select name="add_branch_id" id="add_branch_id" class="form-control">
            @foreach ($branches as $branch)
            <option value="{{ $branch->id }}" @if(isset($data->branch_id))
                @if($data->branch_id == $branch->id)
                selected
                @endif
                @endif>
                {{ $branch->name . '/' . $branch->branch_code }}
            </option>
            @endforeach
        </select>
        <!-- <input type="text" name="business_name" class="form-control" placeholder="@lang('Business name')" /> -->
    </div>


    <div class="form-group text-end mt-3">
        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
        <button type="submit" class="me-0 c-btn button-success float-end">@lang('Save')</button>
        <button type="button" id="close_form" class="c-btn btn_orange float-end">@lang('Close')</button>
    </div>
</form>
