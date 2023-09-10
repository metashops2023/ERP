<form id="edit_supplier_form" action="{{ route('contacts.supplier.update') }}">
    <input type="hidden" name="id" id="id" value="{{ $supplier->id }}">
    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><b>@lang('Name') :</b></label><span class="text-danger">*</span>
            <input type="text" name="name" class="form-control edit_input" data-name="Supplier name" id="e_name" placeholder="@lang('Supplier Name')" value="{{ $supplier->name }}" />
            <span class="error error_e_name"></span>
        </div>

        <div class="col-md-3">
            <b>@lang('Phone') :</b> <span class="text-danger">*</span>
            <input type="text" name="phone" class="form-control  edit_input" data-name="Phone number" id="e_phone" placeholder="@lang('Phone Number')" value="{{ $supplier->phone }}" />
            <span class="error error_e_phone"></span>
        </div>

        <div class="col-md-3">
            <b>@lang('Supplier ID') :</b>
            <input readonly type="text" name="contact_id" class="form-control" placeholder="@lang('Contact ID')" id="e_contact_id" value="{{ $supplier->contact_id }}" />
        </div>

        <div class="col-md-3">
            <b>@lang('Business Name') :</b> <span class="text-danger">*</span>

            @if($branch)
            <input type="text" id="update_branch_id" value="{{ $branch->name }}" disabled>
            <input type="hidden" name="update_branch_id" value="{{ $branch->id }}">
            @endif

            <!-- <input type="text" name="business_name" class="form-control" placeholder="@lang('Business Name')" id="e_business_name" value="{{ $supplier->business_name }}"/> -->
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <b>@lang('Alternative Number') :</b>
            <input type="text" name="alternative_phone" class="form-control " placeholder="@lang('Alternative Phone Number')" id="e_alternative_phone" value="{{ $supplier->alternative_phone }}" />
        </div>

        <div class="col-md-3">
            <b>@lang('Landline') :</b>
            <input type="text" name="landline" class="form-control " placeholder="@lang('Landline Number')" id="e_landline" value="{{ $supplier->landline }}" />
        </div>

        <div class="col-md-3">
            <b>@lang('Email') :</b>
            <input type="text" name="email" class="form-control" placeholder="@lang('Email Address')" id="e_email" value="{{ $supplier->email }}" />
        </div>

        <div class="col-md-3">
            <b>@lang('Date Of Birth') :</b>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                </div>
                <input type="text" name="date_of_birth" class="form-control date-of-birth-picker" autocomplete="off" id="e_date_of_birth" value="{{ $supplier->date_of_birth }}" placeholder="@lang('YYYY-MM-DD')">
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <b>@lang('Tax Number') :</b>
            <input type="text" name="tax_number" class="form-control " placeholder="@lang('Tax number')" id="e_tax_number" value="{{ $supplier->tax_number }}" />
        </div>

        <div class="col-md-3">
            <label><b>@lang('Opening Balance') :</b></label>
            <input type="text" name="opening_balance" class="form-control " placeholder="@lang('Opening Balance')" id="e_opening_balance" value="{{ $branchOpeningBalance ? $branchOpeningBalance->amount : 0.00 }}" />
        </div>

        <div class="col-md-3">
            <label><b>@lang('Pay Term')</b> : </label>
            <div class="row">
                <div class="col-md-5">
                    <input type="number" step="any" name="pay_term_number" class="form-control" id="e_pay_term_number" value="{{ $supplier->pay_term_number }}" placeholder="@lang('Number')" />
                </div>

                <div class="col-md-7">
                    <select name="pay_term" class="form-control">
                        <option value="">@lang('Days/Months')</option>
                        <option {{ $supplier->pay_term == 1 ? 'SELECTED' : '' }} value="1">@lang('Days')</option>
                        <option {{ $supplier->pay_term == 2 ? 'SELECTED' : '' }} value="2">@lang('Months')</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-9">
            <b>@lang('Address') :</b>
            <input type="text" name="address" class="form-control" placeholder="@lang('Address')" id="e_address" value="{{ $supplier->address }}">
        </div>

        <div class="col-md-3">
            <b>@lang('Prefix') :</b>
            <input readonly type="text" name="prefix" id="e_prefix" class="form-control " placeholder="@lang('prefix')" value="{{ $supplier->prefix }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <b>@lang('City') :</b>
            <input type="text" name="city" class="form-control " placeholder="@lang('City')" id="e_city" value="{{ $supplier->city }}" />
        </div>

        <div class="col-md-3">
            <b>@lang('State') :</b>
            <input type="text" name="state" class="form-control " placeholder="@lang('State')" id="e_state" value="{{ $supplier->state }}" />
        </div>

        <div class="col-md-3">
            <b>@lang('Country') :</b>
            <input type="text" name="country" class="form-control " placeholder="@lang('Country')" id="e_country" value="{{ $supplier->country }}" />
        </div>

        <div class="col-md-3">
            <b>@lang('Zip-Code') :</b>
            <input type="text" name="zip_code" class="form-control " placeholder="@lang('Zip-Code')" id="e_zip_code" value="{{ $supplier->zip_code }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-5">
            <b>@lang('Shipping Address') :</b>
            <input type="text" name="shipping_address" class="form-control " placeholder="@lang('Shipping address')" id="e_shipping_address" value="{{ $supplier->shipping_address }}" />
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end">@lang('Save Change')</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>
