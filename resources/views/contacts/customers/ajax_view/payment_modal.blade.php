<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;border: 1px solid #dcd1d1;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}

    .due_all_table {min-height: 200px; max-height: 200px; overflow-x: hidden;}
    .due_invoice_table {min-height: 200px; max-height: 200px; overflow-x: hidden;}
    .due_orders_table {min-height: 200px; max-height: 200px; overflow-x: hidden;}
    .seperate_area {border: 1px solid gray;padding: 6px;}
    .invoice_and_order_table_area th {font-size: 8px!important;}
    .invoice_and_order_table_area td {font-size: 9px!important;}
    .invoice_and_order_table_area table tbody tr:hover{background: gray;}
</style>
<div class="modal-dialog modal-dialog five-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('Receive Payment') <span class="type_name"></span></h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="info_area mb-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>@lang('Customer') : </strong><span class="card_text customer_name">{{ $customer->name }}</span></li>
                                <li><strong>@lang('Business') : </strong><span class="card_text customer_business">{{ $customer->business_name }}</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>@lang('Opening Balance') : </strong>
                                    <span class="card_text" id="customer_payment_opening_balance">
                                        {{ App\Utils\Converter::format_in_bdt($amounts['opening_balance']) }}
                                    </span>
                                </li>

                                <li><strong>@lang('Total Sale/Order') : </strong>
                                    <span class="card_text" id="customer_payment_total_sale">
                                        {{ App\Utils\Converter::format_in_bdt($amounts['total_sale']) }}
                                    </span>
                                </li>

                                <li><strong>@lang('Total Return') : </strong>
                                    <span class="card_text" id="customer_payment_total_return">
                                        {{ App\Utils\Converter::format_in_bdt($amounts['total_return']) }}
                                    </span>
                                </li>

                                <li><strong>@lang('Total Paid') : </strong>
                                    <span class="card_text text-success" id="customer_payment_total_paid">
                                        {{ App\Utils\Converter::format_in_bdt($amounts['total_paid']) }}
                                    </span>
                                </li>

                                <li><strong>@lang('Total Due') : </strong>
                                    <span class="card_text text-danger" >
                                        <span id="card_total_due_show">{{ App\Utils\Converter::format_in_bdt($amounts['total_sale_due']) }}</span>
                                        <input type="hidden" id="card_total_due" value="{{ $amounts['total_sale_due'] }}">
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            <form id="payment_form" action="{{ route('customers.payment.add', $customer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-lg-12">
                                <label><strong>@lang('Business Location') : </strong> </label>
                                <input readonly type="text" name="branch_id" class="form-control" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}" style="font-weight: 600; font-size:12px;">
                            </div>

                            <div class="col-md-12 mt-2">
                                <div class="seperate_area">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group mt-1">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap">
                                                            <input type="radio" checked name="payment_against" id="payment_against" class="all"  data-show_table="all_orders_and_invoices_area" value="all"> &nbsp; <b>@lang('All')</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group mt-1">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap">
                                                            <input type="radio" name="payment_against" id="payment_against" class="payment_against"  data-show_table="due_invoice_table_area" value="sale_invoices"> &nbsp; <b>@lang('Receive Against Specific Invoices')</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group mt-1">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap">
                                                        <input type="radio" name="payment_against" id="payment_against" class="payment_against" data-show_table="due_orders_table_area"  value="sale_orders"> &nbsp; <b> @lang('Receive Against Specific Sales Orderes')</b> </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="invoice_and_order_table_area mt-2">
                                        <div class="all_orders_and_invoices_area due_table">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="heading_area">
                                                                <p><strong>@lang('All') </strong></p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <a href="#" id="close" class="btn btn-sm btn-danger float-end">@lang('Unselect All')</a>
                                                        </div>
                                                    </div>

                                                    <div class="due_all_table">
                                                        <table class="table modal-table table-sm mt-1">
                                                            <thead>
                                                                <tr class="bg-primary">
                                                                    <th class="text-start text-white">@lang('Select')</th>
                                                                    <th class="text-start text-white">@lang('Date')</th>
                                                                    <th class="text-start text-white">@lang('Order/Invoice')</th>
                                                                    <th class="text-start text-white">@lang('Status')</th>
                                                                    <th class="text-start text-white">@lang('Pay Status')</th>
                                                                    <th class="text-start text-white">@lang('Sold Amt').</th>
                                                                    <th class="text-start text-white">@lang('Due')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($branchWiseCustomerInvoiceAndOrders['allSalesAndOrders'] as $row)
                                                                    <tr>
                                                                        <td class="text-start"><input type="checkbox" name="sale_ids[]" class="sale_id" value="{{ $row->id }}" id="sale_id" data-due_amount="{{ $row->due }}"></td>
                                                                        <td class="text-start">{{ $row->date }}</td>

                                                                        <td class="text-start">
                                                                            @if ($row->status == 1)

                                                                                <a class="details_button text-info" title="Details" href="{{ route('sales.show', [$row->id]) }}">{{ $row->invoice_id }}</a>
                                                                            @elseif($row->status == 3 || $row->status == 7)

                                                                                <a class="details_button text-info" title="Details" href="{{ route('sales.order.show', [$row->id]) }}">{{ $row->order_id }}</a>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-start">
                                                                            @if ($row->status == 1)
                                                                                Sale
                                                                            @else
                                                                                Order
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-start">
                                                                            @php
                                                                                $payable = $row->total_payable_amount - $row->sale_return_amount;
                                                                            @endphp

                                                                            @if ($row->due <= 0)

                                                                                <span class="text-success"><b>@lang('Paid')</b></span>
                                                                            @elseif ($row->due > 0 && $row->due < $payable)

                                                                                <span class="text-primary"><b>@lang('Partial')</b></span>
                                                                            @elseif ($payable == $row->due)

                                                                                <span class="text-danger"><b>@lang('Due')</b></span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($row->total_payable_amount) }}</td>
                                                                        <td class="text-start text-danger"><strong>{{ App\Utils\Converter::format_in_bdt($row->due) }}</strong></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="due_invoice_table_area due_table d-none">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="heading_area">
                                                                <p><strong>@lang('Due Sale Invoice List')</strong></p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <a href="#" id="close" class="btn btn-sm btn-danger float-end">@lang('Unselect All')</a>
                                                        </div>
                                                    </div>

                                                    <div class="due_invoice_table">
                                                        <table class="table modal-table table-sm mt-1 custom-tbl">
                                                            <thead>
                                                                <tr class="bg-primary">
                                                                    <th class="text-start text-white">@lang('Select')</th>
                                                                    <th class="text-start text-white">@lang('Date')</th>
                                                                    <th class="text-start text-white">@lang('Invoice ID')</th>
                                                                    <th class="text-start text-white">@lang('Pay Status')</th>
                                                                    <th class="text-start text-white">@lang('Sold Amt').</th>
                                                                    <th class="text-start text-white">@lang('Due Amount')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($branchWiseCustomerInvoiceAndOrders['invoices'] as $invoice)
                                                                    <tr>
                                                                        <td class="text-start"><input type="checkbox" name="sale_ids[]" class="sale_id" value="{{ $invoice->id }}" id="sale_id" data-due_amount="{{ $invoice->due }}"></td>
                                                                        <td class="text-start">{{ $invoice->date }}</td>
                                                                        <td>
                                                                            <a class="details_button text-info" title="Details" href="{{ route('sales.show', [$invoice->id]) }}">{{ $invoice->invoice_id }}</a>
                                                                        </td>
                                                                        <td class="text-start">
                                                                            @php
                                                                                $payable = $invoice->total_payable_amount - $invoice->sale_return_amount;
                                                                            @endphp

                                                                            @if ($invoice->due <= 0)

                                                                                <span class="text-success"><b>@lang('Paid')</b></span>
                                                                            @elseif ($invoice->due > 0 && $invoice->due < $payable)

                                                                                <span class="text-primary"><b>@lang('Partial')</b></span>
                                                                            @elseif ($payable == $invoice->due)

                                                                                <span class="text-danger"><b>@lang('Due')</b></span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($row->total_payable_amount) }}</td>
                                                                        <td class="text-start text-danger"><strong>{{ App\Utils\Converter::format_in_bdt($invoice->due) }}</strong></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="due_orders_table_area due_table d-none">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="heading_area">
                                                                <p><strong>@lang('Due Sales Order List')</strong> </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <a href="#" id="close" class="btn btn-sm btn-danger float-end">@lang('Unselect All')</a>
                                                        </div>
                                                    </div>

                                                    <div class="due_orders_table">
                                                        <table class="table modal-table table-sm mt-1">
                                                            <thead>
                                                                <tr class="bg-primary">
                                                                    <th class="text-start text-white">@lang('Select')</th>
                                                                    <th class="text-start text-white">@lang('Date')</th>
                                                                    <th class="text-start text-white">@lang('Order ID')</th>
                                                                    <th class="text-start text-white">@lang('Pay Status')</th>
                                                                    <th class="text-start text-white">@lang('Sold Amt').</th>
                                                                    <th class="text-start text-white">@lang('Due Amount')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($branchWiseCustomerInvoiceAndOrders['orders'] as $order)
                                                                    <tr>
                                                                        <td class="text-start"><input type="checkbox" name="sale_ids[]" class="sale_id" value="{{ $order->id }}" id="sale_id" data-due_amount="{{ $order->due }}"></td>
                                                                        <td class="text-start">{{ $order->order_date }}</td>
                                                                        <td class="text-start">
                                                                            <a class="details_button text-info" title="Details" href="{{ route('sales.order.show', [$order->id]) }}">{{ $order->order_id }}</a>
                                                                        </td>
                                                                        <td class="text-start">
                                                                            @php
                                                                                $payable = $order->total_payable_amount - $order->sale_return_amount;
                                                                            @endphp

                                                                            @if ($order->due <= 0)

                                                                                <span class="text-success"><b>@lang('Paid')</b></span>
                                                                            @elseif ($order->due > 0 && $order->due < $payable)

                                                                                 <span class="text-primary"><b>@lang('Partial')</b></span>
                                                                            @elseif ($payable == $order->due)

                                                                                <span class="text-danger"><b>@lang('Due')</b></span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($row->total_payable_amount) }}</td>
                                                                        <td class="text-start text-danger"><strong>{{ App\Utils\Converter::format_in_bdt($order->due) }}</strong></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="total_amount_area mt-1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Total Amount') : </strong> <span id="total_amount">0.00</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label><strong>@lang('Amount') :</strong> <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="far fa-money-bill-alt text-dark input_f"></i></span>
                                    </div>
                                    <input type="hidden" id="cp_available_amount" value="{{ $customer->total_sale_due }}">
                                    <input type="number" name="paying_amount" class="form-control cp_input" step="any" data-name="Amount" id="cp_paying_amount" value="" autocomplete="off" autofocus/>
                                </div>
                                <span class="error error_cp_paying_amount"></span>
                            </div>

                            <div class="col-md-4">
                                <label for="cp_date"><strong>@lang('Date') :</strong> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week text-dark input_f"></i></span>
                                    </div>

                                    <input type="text" name="date" class="form-control cp_input" id="cp_date" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" autocomplete="off">
                                </div>
                                <span class="error error_cp_date"></span>
                            </div>

                            <div class="col-md-4">
                                <label><strong>@lang('Reference') :</strong> </label>
                                <input type="text" name="reference" class="form-control" placeholder="@lang('Payment Reference')" autocomplete="off"/>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-4">
                                <label><strong>@lang('Less Amount') :</strong> </label>
                                <input type="number" step="any" name="less_amount" class="form-control" id="cp_less_amount" placeholder="@lang('Less Amount')" autocomplete="off"/>
                            </div>

                            <div class="col-md-4">
                                <label><strong>@lang('Payment Method') :</strong> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark input_i"></i></span>
                                    </div>
                                    <select name="payment_method_id" class="form-control" id="cp_payment_method_id">
                                        @foreach ($methods as $method)
                                            <option data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}" value="{{ $method->id }}">
                                                {{ $method->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error error_cp_payment_method_id"></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label><strong>@lang('Payment Account') :</strong> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check-alt text-dark input_i"></i></span>
                                    </div>

                                    <select name="account_id" class="form-control" id="cp_account_id">
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">
                                                @php
                                                    $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                                    $balance = ' BL : '.$account->balance;
                                                @endphp
                                                {{ $account->name.$accountType.$balance}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error error_cp_account_id"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-4">
                                <label><strong>@lang('Attach document') :</strong> <small class="text-danger">@lang('Note'): Max Size 2MB. </small> </label>
                                <input type="file" name="attachment" class="form-control" id="attachment">
                            </div>

                            <div class="col-md-8">
                                <label><strong> @lang('Payment Note') :</strong></label>
                                <textarea name="note" class="form-control" id="note" cols="30" rows="3" placeholder="@lang('Note')"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <label><strong>@lang('IN WORD') : </strong> <strong><span class="text-danger text-uppercase" id="in_word"></span></strong></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12">
                        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                        <button name="action" value="save" type="submit" class="c-btn button-success float-end">@lang('Save')</button>
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //Add Customer payment request by ajax
    $('#payment_form').on('submit',  function(e) {
        e.preventDefault();

        $('.loading_button').show();

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
                    toastr.success(data);
                    $('.data_tbl').DataTable().ajax.reload();

                    var filterObj = {
                        branch_id : $('#payment_branch_id').val(),
                        from_date : $('#payment_from_date').val(),
                        to_date : $('#payment_to_date').val(),
                    };

                    getCustomerAmountsBranchWise(filterObj, 'cus_payments_', false);

                    filterObj = {
                        branch_id : $('#ledger_branch_id').val(),
                        from_date : $('.from_date').val(),
                        to_date : $('.to_date').val(),
                    };

                    getCustomerAmountsBranchWise(filterObj, 'ladger_', false);

                    filterObj = {
                        branch_id : $('#sale_branch_id').val(),
                        from_date : $('#from_sale_date').val(),
                        to_date : $('#to_sale_date').val(),
                    };

                    getCustomerAmountsBranchWise(filterObj, 'sales_', false);
                }
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Please check the connection.');
                    return;
                }else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_cp_' + key + '').html(error[0]);
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
        element: document.getElementById('cp_date'),
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

    $('#cp_payment_method_id').on('change', function () {

        var account_id = $(this).find('option:selected').data('account_id');
        setMethodAccount(account_id);
    });

    function setMethodAccount(account_id) {

        if (account_id) {

            $('#cp_account_id').val(account_id);
        }else if(account_id === ''){

            $('#cp_account_id option:first-child').prop("selected", true);
        }
    }

    setMethodAccount($('#cp_payment_method_id').find('option:selected').data('account_id'));

    $(document).on('click', '#payment_against', function() {

        var saleIds = document.querySelectorAll('#sale_id');

        saleIds.forEach(function(input){

            $(input).prop('checked', false);
        });

        var show_table = $(this).data('show_table');
        $('.due_table').hide();
        $('.'+show_table).show(300);
        $('#total_amount').html(0.00);
        $('#cp_paying_amount').val(parseFloat(0).toFixed(2));
        var card_total_due = $('#card_total_due').val() ? $('#card_total_due').val() : 0;
        $('#card_total_due_show').text(bdFormat(card_total_due));
    });

    $(document).on('click', '#sale_id', function() {

        var saleIds = document.querySelectorAll('#sale_id');

        var total = 0;
        saleIds.forEach(function(input){

            if ($(input).is(':CHECKED', true)) {

                total += parseFloat($(input).data('due_amount'));
            }
        });

        $('#total_amount').html(parseFloat(total).toFixed(2));
        $('#cp_paying_amount').val(parseFloat(total).toFixed(2));

        calculateTotalDue();
    });


    $(document).on('click', '#close', function (e) {
        e.preventDefault();

        var saleIds = document.querySelectorAll('#sale_id');

        saleIds.forEach(function(input){

            $(input).prop('checked', false);
        });

        $('#total_amount').html(0.00);
        $('#cp_paying_amount').val(0.00);
        calculateTotalDue();
    });

    $(document).on('input', '#cp_paying_amount', function (e) {

        calculateTotalDue();
    });

    $(document).on('input', '#cp_less_amount', function (e) {

        calculateTotalDue();
    });

    function calculateTotalDue() {

        var cp_paying_amount = $('#cp_paying_amount').val() ? $('#cp_paying_amount').val() : 0;
        var card_total_due = $('#card_total_due').val() ? $('#card_total_due').val() : 0;
        var cp_less_amount = $('#cp_less_amount').val() ? $('#cp_less_amount').val() : 0;

        var totalDue = parseFloat(card_total_due) - parseFloat(cp_paying_amount) - parseFloat(cp_less_amount);

        $('#card_total_due_show').text(bdFormat(totalDue));

        if (parseFloat(cp_paying_amount) && parseFloat(cp_paying_amount) > 0) {

            document.getElementById('in_word').innerHTML = inWords(parseInt(cp_paying_amount)) + 'ONLY';
        }else {

            document.getElementById('in_word').innerHTML = '';
        }
    }
</script>

<script>
    var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
    var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

      function inWords (num) {
          if ((num = num.toString()).length > 9) return 'overflow';
          n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
          if (!n) return; var str = '';
          str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
          str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
          str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
          str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
          str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + ' ' : '';
          return str;
      }

        $(document).on('click', '.invoice_and_order_table_area table tbody tr', function () {
            $('.invoice_and_order_table_area table tbody tr').removeClass('active_tr');
            $(this).addClass('active_tr');
        });
</script>
