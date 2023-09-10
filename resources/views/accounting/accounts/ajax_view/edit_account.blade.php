<form id="edit_account_form" action="{{ route('accounting.accounts.update', $account->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label><strong>@lang('Name') :</strong> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control edit_input" data-name="Type name" id="e_name"
            placeholder="@lang('Account Name')" value="{{ $account->name }}"/>
        <span class="error error_e_name"></span>
    </div>
    
    <div class="form-group mt-1">
        <label><strong>@lang('Account Type') : <span class="text-danger">*</span></strong></label>
        <select name="account_type" class="form-control edit_input" data-name="Account Type"
            id="e_account_type">
            <option value="">@lang('Select Account type')</option>
            @foreach (App\Utils\Util::allAccountTypes() as $key => $accountType)
                <option {{ $key == $account->account_type ? 'SELECTED' : '' }} value="{{ $key }}">
                    {{ $accountType }}
                </option>
            @endforeach
        </select>
        
        <span class="error error_e_account_type"></span>
    </div>

    @if (auth()->user()->role_type == 1 || auth()->user()->role_type)
        <div class="form-group row mt-1 {{ $account->account_type == 2 ? '' : 'd-none' }} e_bank_account_field">
            <div class="col-md-12">
                <label><strong>@lang('Bank Name') :</strong> <span class="text-danger">*</span> </label>
                <select name="bank_id" class="form-control edit_input" data-name="Bank name" id="bank_id">
                    <option value="">@lang('Select Bank')</option>
                    @foreach ($banks as $bank)
                        <option {{ $bank->id == $account->bank_id ? 'SELECTED' : '' }} value="{{ $bank->id }}">
                            {{ $bank->name . ' (' . $bank->branch_name . ')' }}
                        </option>
                    @endforeach
                </select>
                <span class="error error_bank_id"></span>
            </div>

            <div class="col-md-12">
                <label><strong>@lang('Account Number') : </strong><span class="text-danger">*</span></label>
                <input type="text" name="account_number" class="form-control edit_input"
                    data-name="Account Number" id="e_account_number" placeholder="@lang('Account number')" value="{{ $account->account_number }}"/>
                <span class="error error_e_account_number"></span>
            </div>

            <div class="col-md-12">
                <label><strong>@lang('Access Business Location') :</strong> <span class="text-danger">*</span></label>
                <select name="business_location[]" id="e_business_location" class="form-control select2" multiple="multiple">
                    <option {{ $isExistsHeadOffice ? 'SELECTED' : '' }} value="NULL">
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (HO)
                    </option>

                    @foreach ($branches as $branch)
                        <option 
                            @foreach ($account->accountBranches as $acBranch)
                                {{ $acBranch->branch_id == $branch->id ? 'SELECTED' : '' }}
                            @endforeach
                            value="{{ $branch->id }}"
                        >
                            {{ $branch->name.'/'.$branch->branch_code }}
                        </option>
                    @endforeach
                </select>
                <span class="error error_e_business_location"></span>
            </div>
        </div>
    @endif

    <div class="form-group mt-1">
        <label><strong>@lang('Opening Balance') :</strong></label>
        <input type="number" step="any" name="opening_balance" class="form-control" 
            id="e_opening_balance" value="{{ $account->opening_balance }}"/>
    </div>

    <div class="form-group mt-1">
        <label><strong>@lang('Remarks') :</strong></label>
        <input type="text" name="remark" class="form-control" data-name="Remark" id="e_remarks" value="{{ $account->remark }}"/>
    </div>

    <div class="form-group text-right py-2">
        <button type="button" class="btn loading_button d-none"><i
                class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
        <button type="submit" class="c-btn me-0 button-success submit_button float-end">@lang('Save')</button>
        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
    </div>
</form>

<script>
    $('.select2').select2({
        placeholder: "Select a access business location",
        allowClear: true
    });

    $(document).on('change', '#e_account_type', function() {
        var account_type = $(this).val();
        if (account_type == 2) {
            $('.e_bank_account_field').show();
        }else {
            $('.e_bank_account_field').hide();
        }
    });

    // edit account type by ajax
    $('#edit_account_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
    
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('.loading_button').hide();
                accounts_table.ajax.reload();
                $('#editModal').modal('hide');
            },error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.'); 
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>