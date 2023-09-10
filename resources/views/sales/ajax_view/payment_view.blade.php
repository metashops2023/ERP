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
                    <li><strong>@lang('Customer') : </strong>
                        {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                    </li>
                    <li><strong>@lang('Business') : </strong>
                        {{ $sale->customer ? $sale->customer->business_name : '' }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong> @lang('Invoice ID') : </strong>{{ $sale->invoice_id }}</li>
                    <li><strong>@lang('Business Location'): </strong>
                        @if ($sale->branch)
                            {{ $sale->branch->name . '/' . $sale->branch->branch_code }}
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
                    <li><strong>Total Due : {{ json_decode($generalSettings->business, true)['currency'] }}
                        </strong>{{ $sale->due }}</li>
                    <li><strong>@lang('Date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($sale->date)) }}</li>
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
        <table class="display modal-table table-sm table-striped">
            <thead>
                <tr class="bg-primary">
                    <th class="text-white">@lang('Date')</th>
                    <th class="text-white">@lang('Voucher No')</th>
                    <th class="text-white">@lang('Amount')</th>
                    <th class="text-white">@lang('Method')</th>
                    <th class="text-white">@lang('Type')</th>
                    <th class="text-white">@lang('Account')</th>
                    <th class="text-white">@lang('Action')</th>
                </tr>
            </thead>
            <tbody id="payment_list_body">
                @if (count($sale->sale_payments) > 0)
                    @foreach ($sale->sale_payments as $payment)
                        <tr data-info="{{ $payment }}">
                            <td>
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) }}
                            </td>
                            <td>{{ $payment->invoice_id }}</td>
                            <td>{{ json_decode($generalSettings->business, true)['currency'] . ' ' . $payment->paid_amount }}
                            </td>
                            <td>{{ $payment->paymentMethod ? $payment->paymentMethod->name : $payment->pay_mode }}</td>
                            <td>{{ $payment->payment_type == 1 ? 'Sale due' : 'Return due' }}</td>
                            <td>{{ $payment->account ? $payment->account->name : 'Cash-In-Hand' }}</td>
                            <td>
                                @if ($sale->branch_id == auth()->user()->branch_id)

                                    @if ($payment->payment_type == 1)

                                        <a href="{{ route('sales.payment.edit', $payment->id) }}" id="edit_payment"
                                            class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                                    @else
                                        @if ($payment->sale_id)

                                            <a href="{{ route('sales.return.payment.edit', $payment->id) }}" id="edit_return_payment" class="btn-sm">
                                                <i class="fas fa-edit text-info"></i>
                                            </a>
                                        @endif
                                    @endif
                                @endif

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
