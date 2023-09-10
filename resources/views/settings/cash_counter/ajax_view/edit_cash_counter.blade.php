<form id="edit_cash_counter_form" action="{{ route('settings.cash.counter.update', $cc->id) }}" method="POST"
    enctype="multipart/form-data">
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('Counter Name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="counter_name" class="form-control" id="e_counter_name"
                placeholder="@lang('Cash Counter name')" value="{{ $cc->counter_name }}"/>
            <span class="error error_e_counter_name"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <label for=""><b>@lang('Short Name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="short_name" class="form-control" id="e_short_name" placeholder="@lang('Short Name')" value="{{ $cc->short_name }}"/>
            <span class="error error_e_short_name"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end">@lang('Update')</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>