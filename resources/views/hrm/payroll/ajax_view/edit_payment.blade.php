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
        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>@lang('Customer') : </strong>{{ $payment->payroll->employee->prefix.' '.$payment->payroll->employee->name.' '.$payment->payroll->employee->last_name}}</li>
                    <li><strong>@lang('Branch/Business') : </strong>
                        <span>
                            @if ($payment->payroll->employee->branch)
                                {{ $payment->payroll->employee->branch->name.'/'.$payment->payroll->employee->branch->branch_code }}
                            @else
                                {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>@lang('Head Office')</b>)
                            @endif
                        </span>  
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong> @lang('Referance No') : </strong><span>{{ $payment->payroll->reference_no }}</span> </li>
                    
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li class="sale_due">
                        <strong>@lang('Total Due') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong>
                        <span>{{ $payment->payroll->due }}</span> </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<form id="payroll_payment_form" action="{{ route('hrm.payrolls.payment.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-md-4">
            <label><strong>@lang('Amount') :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-money-bill-alt text-dark input_i"></i></span>
                </div>
                <input type="hidden" id="available_amount" value="{{ $payment->payroll->due+$payment->paid }}">
                <input type="number" name="paying_amount" class="form-control p_input" step="any" data-name="Amount" id="p_paying_amount" value="{{ $payment->paid }}" autocomplete="off"/>
            </div>
            <span class="error error_p_paying_amount"></span>
        </div>

        <div class="col-md-4">
            <label for="p_date"><strong>@lang('Date') :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week text-dark input_i"></i></span>
                </div>
                <input type="text" name="date" class="form-control p_input" autocomplete="off" id="p_date" data-name="Date" value="{{ date("Y-m-d", strtotime($payment->date)) }}">
            </div>
            <span class="error error_p_date"></span>
        </div>

        <div class="col-md-4">
            <label><strong>@lang('Payment Method') :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark input_i"></i></span>
                </div>
                <select name="payment_method_id" class="form-control"  id="p_payment_method_id">
                    @foreach ($methods as $method)
                        <option {{ $method->id == $payment->payment_method_id ? 'SELECTED' : '' }} value="{{ $method->id }}">
                            {{ $method->name }}
                        </option>
                    @endforeach
                </select>
                <span class="error error_p_date"></span>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-4">
            <label><strong>@lang('Credit Account') :</strong> </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check-alt text-dark input_i"></i></span>
                </div>
                <select name="account_id" class="form-control" id="p_account_id">
                    @foreach ($accounts as $account)
                        <option {{ $payment->account_id == $account->id ? 'SELECTED' : '' }} value="{{ $account->id }}">
                            @php
                                $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                $balance = ' BL : '.$account->balance;
                            @endphp
                            {{ $account->name.$accountType.$balance }}
                        </option>
                    @endforeach
                </select>
                <span class="error error_p_account_id"></span>
            </div>
        </div>

        <div class="col-md-4">
            <label><strong>@lang('Attach document') :</strong> <small class="text-danger">@lang('Note'): Max Size 2MB. </small> </label>
            <input type="file" name="attachment" class="form-control" id="attachment" data-name="Date" >
        </div>
    </div>

    <div class="form-group">
        <label><strong> @lang('Payment Note') :</strong></label>
        <textarea name="note" class="form-control" id="note" cols="30" rows="3" placeholder="@lang('Note')">{{ $payment->note }}</textarea>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>

<script>
    //Add sale payment request by ajax
    $('#payroll_payment_form').on('submit', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var available_amount = $('#available_amount').val();
        var paying_amount = $('#p_paying_amount').val();
        if (parseFloat(paying_amount) > parseFloat(available_amount)) {
            $('.error_p_paying_amount').html('Paying amount must not be greater then due amount.');
            $('.loading_button').hide();
            return;
        }

        var url = $(this).attr('action');
        $('.submit_button').prop('type', 'button');
        $.ajax({
            url:url,
            type:'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data){
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'ERROR'); 
                    $('.submit_button').prop('type', 'submit');
                    $('.loading_button').hide();
                }else{
                    $('.modal').modal('hide');
                    table.ajax.reload();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    toastr.success(data); 
                }
            },error: function(err) {
                $('.submit_button').prop('type', 'submit');
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.'); 
                    return;
                }else if (err.status == 500) {
                    toastr.error('Server Error. Please contact the support team.'); 
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_p_' + key + '').html(error[0]);
                });
            }
        });
    });

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