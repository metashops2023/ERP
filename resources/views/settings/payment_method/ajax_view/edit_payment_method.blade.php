<form id="edit_payment_method_form" class="p-2" action="{{ route('settings.payment.method.update', $method->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('Method Name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="e_name"
                placeholder="@lang('Payment Method Name')" value="{{ $method->name }}"/>
            <span class="error error_e_name"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i
                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save Changes')</button>
            <button type="reset" data-bs-dismiss="modal"
                class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>
