<form id="edit_company_form" action="{{ route('accounting.loan.companies.update', $company->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('Name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="e_name" autocomplete="off"
                placeholder="@lang('Company/People Name')" value="{{ $company->name }}"/>
            <span class="error error_e_name"></span>
        </div>
    </div>

    <div class="col-md-12">
        <label><b>@lang('Phone') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control" id="e_phone" autocomplete="off"
            placeholder="@lang('Phone Number Name')" value="{{ $company->phone }}"/>
        <span class="error error_e_phone"></span>
    </div>

    <div class="col-md-12">
        <label><b>@lang('Address') :</b> </label>
        <textarea name="address" class="form-control" id="e_address" cols="10" rows="3" placeholder="@lang('Address')">{{ $company->address }}</textarea>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
            <button type="button" class="c-btn btn_orange float-end" id="close_com_edit_form">@lang('Close')</button>
        </div>
    </div>
</form>