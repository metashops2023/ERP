@php
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
    h6.checkbox_input_wrap {border: 1px solid #495677;padding: 0px 7px;}
</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('Add Payment') <span class="type_name"></span></h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <div class="info_area mb-2">
                <div class="row">
                    <div class="col-md-4">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>@lang('Supplier') : </strong><span>{{ $purchase->supplier->name }}</span></li>
                                <li><strong>@lang('Business') : </strong>
                                    <span>{{ $purchase->supplier->business_name }}</span>
                                </li>
                                <li><strong>@lang('phone') : </strong>
                                    <span>{{ $purchase->supplier->phone }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong> @lang('Reference ID') : </strong>{{ $purchase->invoice_id }}</li>
                                <li><strong>@lang('B.Location') : </strong>
                                    {{ $purchase->branch ? $purchase->branch->name . '/' . $purchase->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>@lang('Total Due') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong>
                                    <span class="total_due text-danger"><strong>{{ App\Utils\Converter::format_in_bdt($purchase->due) }}</strong></span>
                                </li>
                                <li>
                                    <strong>@lang('Date') : </strong>
                                    {{
                                        date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time))
                                    }}
                                </li>
                                <li><strong>@lang('Purchase Status') : </strong>
                                    @if ($purchase->purchase_status == 1)
                                        <span class="text-success"><b>@lang('Received')</b></span>
                                    @elseif($purchase->purchase_status == 2){
                                        <span class="text-warning"><b>@lang('Pending')</b></span>
                                    @else
                                        <span class="text-primary"><b>@lang('Ordered')</b></span>
                                    @endif
                                </li>
                                <li><strong>@lang('Payment Status') : </strong>
                                    @php
                                        $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                                    @endphp
                                    @if ($purchase->due <= 0)
                                        <span class="text-success"><b>@lang('Paid')</b></span>
                                    @elseif($purchase->due > 0 && $purchase->due < $payable)
                                        <span class="text-primary"><b>@lang('Partial')</b></span>
                                    @elseif($payable == $purchase->due)
                                        <span class="text-danger "><b>@lang('Due')</b></span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form id="payment_form" action="{{ route('purchases.payment.store', $purchase->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-md-4">
                        <label><strong>@lang('Amount') :</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="far fa-money-bill-alt text-dark input_i"></i>
                                </span>
                            </div>
                            <input type="hidden" id="p_available_amount" value="{{ $purchase->due }}">
                            <input type="number" name="paying_amount" class="form-control p_input" step="any" data-name="Amount" id="p_paying_amount" value="0.00"/>
                        </div>
                        <span class="error error_p_paying_amount"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="p_date"><strong>@lang('Date') :</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-calendar-week text-dark input_i"></i>
                                </span>
                            </div>
                            <input type="text" name="date" class="form-control p_input" autocomplete="off" id="p_date" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
                        </div>
                        <span class="error error_p_date"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>@lang('Payment Method') :</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-money-check text-dark input_i"></i>
                                </span>
                            </div>
                            <select name="payment_method_id" class="form-control" id="p_payment_method_id">
                                @foreach ($methods as $method)
                                    <option
                                        data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                        value="{{ $method->id }}">
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="error error_p_payment_method_id"></span>
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
                                    <option value="{{ $account->id }}">
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
                            <span class="error error_p_account_id"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
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
                        <button type="submit" class="c-btn button-success me-0 float-end">@lang('Save')</button>
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //Add purchase return payment request by ajax
    $('#payment_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var available = $('#p_available_amount').val();
        var paying_amount = $('#p_paying_amount').val();

        if (parseFloat(paying_amount) > parseFloat(available)) {
            $('.error_p_paying_amount').html('Paying amount must not be greater then due amount.');
            $('.loading_button').hide();
            return;
        }

        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    $('.loading_button').hide();
                } else {

                    $('.loading_button').hide();
                    $('#paymentModal').modal('hide');
                    $('#paymentViewModal').modal('hide');
                    toastr.success(data);
                    $('.data_tbl').DataTable().ajax.reload();
                    getSupplier();
                }
            },error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_p_' + key + '').html(error[0]);
                });
            }
        });
    });

    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '';
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

    $('#p_payment_method_id').on('change', function () {

        var account_id = $(this).find('option:selected').data('account_id');
        setMethodAccount(account_id);
    });

    function setMethodAccount(account_id) {

        if (account_id) {

            $('#p_account_id').val(account_id);
        }else if(account_id === ''){

            $('#p_account_id option:first-child').prop("selected", true);
        }
    }

    setMethodAccount($('#p_payment_method_id').find('option:selected').data('account_id'));
</script>
