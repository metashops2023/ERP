<form id="edit_allowance_form" action="{{ route('hrm.allowance.update') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{ $allowance->id }}">
    <div class="form-group">
        <label><b>@lang('Description or Title') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="description" class="form-control form-control-sm" data-name="description"
            placeholder="@lang('Description or Title')" value="{{ $allowance->description }}"/>
        <span class="error error_e_description"></span>
    </div>

    <div class="form-group">
        <label><b>@lang('Type') :</b></label>
        <select class="form-control form-control-sm" name="type">
            <option {{ $allowance->type == 'Allowance' ? 'SELECTED' : '' }} value="Allowance">@lang('Allowance')</option>
            <option {{ $allowance->type == 'Deduction' ? 'SELECTED' : '' }} value="Deduction">@lang('Deduction')</option>
        </select>
    </div>

    <div class="row">
        <div class="form-group col-6">
            <label><b>@lang('Amount Type') :</b> </label>
            <select class="form-control form-control-sm" name="amount_type">
                <option {{ $allowance->type == 1 ? 'SELECTED' : '' }} value="1">@lang('Fixed') (0.0)</option>
                <option {{ $allowance->type == 2 ? 'SELECTED' : '' }} value="2">@lang('Percentage') (%)</option>
            </select>
        </div>
        <div class="form-group col-6">
            <label><b>@lang('Amount') : </b> <span class="text-danger">*</span></label>
            <input type="text" name="amount" class="form-control form-control-sm" placeholder="Amount"
                value="{{ $allowance->amount }}" />
            <span class="error error_e_amount"></span>
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i
                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn me-0 button-success float-end">@lang('Save Change')</button>
            <button type="reset" data-bs-dismiss="modal"
                class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>
