    <form id="edit_warranty_form" action="{{ route('product.warranties.update') }}">
        <input type="hidden" name="id" id="id" value="{{$data->id}}">
        <div class="form-group">
            <strong>@lang('Name') :</strong> <span class="text-danger">*</span>
            <input type="text" name="name" class="form-control edit_input" data-name="Bank name" id="e_name"  value="{{$data->name}}" placeholder="@lang('Bank name')" />
            <span class="error error_e_name"></span>
        </div>

        <div class="row mt-1">
            <div class="col-md-4">
                <strong>@lang('Type') :</strong> <span class="text-danger">*</span>
                <select name="type" class="form-control" id="e_type">
                    <option value="1">@lang('Warranty')</option>
                    <option value="2">@lang('Guaranty')</option>
                </select>
            </div>

            <div class="col-md-8">
                <strong>@lang('Duration') :</strong> <span class="text-danger">*</span>
                <div class="row">
                    <div class="col-md-6">
                        <input type="number" name="duration" class="form-control edit_input" data-name="Warranty duration" id="e_duration">
                        <span class="error error_e_duration"></span>
                    </div>

                    <div class="col-md-6">
                        <select name="duration_type" class="form-control" id="e_duration_type">
                            <option value="Months">@lang('Months')</option>
                            <option value="Days">@lang('Days')</option>
                            <option value="Years">@lang('Years')</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mt-2">
            <strong>@lang('Description') :</strong>
            <textarea name="description" id="e_description" class="form-control form-control-sm" cols="10" rows="3" placeholder="@lang('Warranty description')"></textarea>
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

        <div class="form-group row mt-2">
            <div class="col-md-12">
                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save Changes')</button>
                <button type="button" class="c-btn btn_orange float-end" id="close_form">@lang('Close')</button>
            </div>
        </div>
    </form>

