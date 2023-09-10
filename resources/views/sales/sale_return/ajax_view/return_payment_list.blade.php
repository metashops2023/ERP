<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;}
    .payment_list_table {position: relative;}
    .payment_details_contant{ background: azure!important; }
</style>
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>@lang('Customer') : </strong>
                        {{ $return->customer ? $return->customer->name : 'Walk-In-Customer' }}
                    </li>

                    <li><strong>@lang('Business') : </strong>
                        {{ $return->customer ? $return->customer->business_name : '' }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong> @lang('Return Invoice ID') : </strong>{{ $return->invoice_id }}</li>
                    <li><strong>@lang('Return Date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($return->date)) }}</li>
                    <li><strong>@lang('Business Location'): </strong>

                        @if ($return->branch)

                            {{ $return->branch->name . '/' . $return->branch->branch_code }}
                        @else

                            {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head
                            Office</b>)
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li>
                        <strong>Total Return : {{ json_decode($generalSettings->business, true)['currency'] }}
                        </strong>{{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}
                    </li>
                    <li>
                        <strong>@lang('Total Paid')<Refunded Amount : {{ json_decode($generalSettings->business, true)['currency'] }}
                        </strong>{{ App\Utils\Converter::format_in_bdt($return->total_return_due_pay) }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="payment_list_table">
    <div class="data_preloader payment_list_preloader">
        <h6><i class="fas fa-spinner"></i> @lang('Processing')...</h6>
    </div>
    <div class="table-responsive">
        <table class="table modal-table table-sm table-striped">
            <thead>
                <tr class="bg-primary">
                    <th class="text-white text-start">@lang('Date')</th>
                    <th class="text-white text-start">@lang('Voucher No')</th>
                    <th class="text-white text-start">@lang('Method')</th>
                    <th class="text-white text-start">@lang('Account')</th>
                    <th class="text-white text-end">@lang('Amount')</th>
                    <th class="text-white text-start">@lang('Action')</th>
                </tr>
            </thead>
            <tbody id="payment_list_body">
                @if (count($return->payments) > 0)
                    @foreach ($return->payments as $payment)
                        <tr data-info="{{ $payment }}">
                            <td class="text-start">
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) }}
                            </td>

                            <td class="text-start">{{ $payment->invoice_id }}</td>
                            
                            <td class="text-start">{{ $payment->paymentMethod ? $payment->paymentMethod->name : $payment->pay_mode  }}</td>
                            
                            <td class="text-start">{{ $payment->account ? $payment->account->name.' (A/C :'.$payment->account->account_number.')' : 'N/A' }}</td>
                            
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}
                            </td>

                            <td>
                                <a href="{{ route('sales.return.payment.edit', $payment->id) }}"
                                        id="edit_return_payment" class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                            
                                <a href="{{ route('sales.payment.details', $payment->id) }}" id="payment_details"
                                    class="btn-sm"><i class="fas fa-eye text-primary"></i></a>

                                @if ($payment->customer_payment_id == null)
                                    <a href="{{ route('sales.payment.delete', $payment->id) }}" id="delete_payment"
                                    class="btn-sm"><i class="far fa-trash-alt text-danger"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">@lang('No Data Found')</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <form id="payment_deleted_form" action="" method="post">
            @method('DELETE')
            @csrf
        </form>
    </div>
</div>