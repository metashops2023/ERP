<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
</style>
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong> @lang('Voucher No') : </strong>{{ $expense->invoice_id }} </li>
                    <li><strong>@lang('Business Location') : </strong>
                        {{ $expense->branch ? $expense->branch->name.''.$expense->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'] }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li class="expense_due"><strong>@lang('Total Due') : </strong>{{ $expense->due }} </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<form id="payment_form" action="{{ route('expenses.payment', $expense->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-md-4">
            <label><strong>@lang('Amount') :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-money-bill-alt text-dark input_i"></i></span>
                </div>
                <input type="hidden" id="p_available_amount" value="{{ $expense->due }}">
                <input type="number" name="paying_amount" class="form-control p_input" step="any" data-name="Amount" id="p_amount" value="{{ $expense->due }}"/>
            </div>
            <span class="error error_p_amount"></span>
        </div>

        <div class="col-md-4">
            <label for="p_date"><strong>@lang('Date') :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week text-dark input_i"></i></span>
                </div>
                <input type="text" name="date" class="form-control p_input" autocomplete="off" id="p_date" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
            </div>
            <span class="error error_p_date"></span>
        </div>

        <div class="col-md-4">
            <label><strong>@lang('Payment Method') :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark input_i"></i></span>
                </div>
                <select name="payment_method" class="form-control" id="payment_method">
                    @foreach ($methods as $method)
                        <option value="{{ $method->id }}">
                            {{ $method->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-7">
            <label><strong>@lang('Credit Account') :</strong> </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check-alt text-dark input_i"></i></span>
                </div>
                <select required name="account_id" class="form-control" id="account_id">
                    @foreach ($accounts as $account)
                        @php
                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                            $balance = ' BL : '.$account->balance;
                        @endphp
                        <option value="{{ $account->id }}">{{ $account->name.$accountType.$balance}}</option>
                    @endforeach
                </select>
                <span class="error error_account_id"></span>
            </div>
        </div>

        <div class="col-md-5">
            <label><strong>@lang('Attach document') :</strong> <small class="text-danger">@lang('Note'): Max Size 2MB. </small> </label>
            <input type="file" name="attachment" class="form-control" id="attachment" data-name="Date" >
        </div>
    </div>

    <div class="form-group mt-2">
        <label><strong> @lang('Payment Note') :</strong></label>
        <textarea name="note" class="form-control" id="note" cols="30" rows="3" placeholder="@lang('Note')"></textarea>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success float-end">@lang('Save')</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>
<script>
    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('p_date'),
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
