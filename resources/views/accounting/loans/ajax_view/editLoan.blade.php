<form id="editting_loan_form" action="{{ route('accounting.loan.update', $loan->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-6">
            <label><strong>@lang('Date') : <span class="text-danger">*</span></strong></label>
            <input type="text" name="date" class="form-control" id="e_date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($loan->report_date)) }}">
            <span class="error error_e_date"></span>
        </div>

        <div class="col-md-6">
            <label><b>@lang('Loan A/C') :</b> <span class="text-danger">*</span></label>
            <select required name="loan_account_id" class="form-control" id="loan_account_id">
                <option value="">@lang('Select Loan Account')</option>
                @foreach ($loanAccounts as $loanAc)
                    <option {{ $loanAc->id == $loan->loan_account_id ? 'SELECTED' : '' }} value="{{ $loanAc->id }}">
                        {{ $loanAc->name.' ('.App\Utils\Util::accountType($loanAc->account_type).')' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label><strong>@lang('Company/People') : <span class="text-danger">*</span></strong></label>
            <select name="company_id" class="form-control" id="e_company_id">
                <option value="">@lang('Select Company')</option>
                @foreach ($companies as $company)
                    <option {{ $loan->loan_company_id == $company->id ? 'SELECTED' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_company_id"></span>
        </div>

        <div class="col-md-6">
            <label><b>@lang('Type') :</b> <span class="text-danger">*</span></label>
            <select name="type" class="form-control" id="e_type">
                <option value="">@lang('Select Type')</option>
                <option {{ $loan->type == 1 ? 'SELECTED' : '' }} value="1">@lang('Loan') & Advance</option>
                <option {{ $loan->type == 2 ? 'SELECTED' : '' }} value="2">@lang('Loan') & Liabilities</option>
            </select>
            <span class="error error_e_type"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('Loan Amount') :</b> <span class="text-danger">*</span> </label>
            <input type="number" step="any" name="loan_amount" class="form-control" id="e_loan_amount" placeholder="@lang('Loan Amount')" value="{{ $loan->loan_amount }}"/>
            <span class="error error_e_loan_amount"></span>
        </div>

        <div class="col-md-6">
            <label><b>@lang('Debit/Credit Account') :</b> <span class="text-danger">*</span></label>
            <select name="account_id" class="form-control" id="e_account_id">
                <option value="">@lang('Select Account')</option>
                @foreach ($accounts as $account)
                    <option {{ $loan->account_id == $account->id ? 'SELECTED' : '' }} value="{{ $account->id }}">
                        @php
                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                            $bank = $account->bank ? ', BK : '.$account->bank : '';
                            $ac_no = $account->account_number ? ', A/c No : '.$account->account_number : '';
                            $balance = ', BL : '.$account->balance;
                        @endphp
                        {{ $account->name.$accountType.$bank.$ac_no.$balance }}
                    </option>
                @endforeach
            </select>
            <span class="error error_e_account_id"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>@lang('Loan Reason') :</b> </label>
            <textarea name="loan_reason" class="form-control" id="loan_reason" cols="10" rows="3" placeholder="@lang('Loan Reason')">{{ $loan->loan_reason }}</textarea>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
            <button type="button" class="c-btn btn_orange float-end" id="close_loan_edit_form">@lang('Close')</button>
        </div>
    </div>
</form>

<script>
    // var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    // var _expectedDateFormat = '';
    // _expectedDateFormat = dateFormat.replace('d', 'dd');
    // _expectedDateFormat = _expectedDateFormat.replace('m', 'mm');
    // _expectedDateFormat = _expectedDateFormat.replace('Y', 'yyyy');
    // $('.datepicker').datepicker({ format: _expectedDateFormat })

    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
</script>
