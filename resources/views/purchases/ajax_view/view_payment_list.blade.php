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
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>@lang('Supplier') : </strong><span>{{ $purchase->supplier->name }}</span>
                    </li>
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
                    <li><strong> @lang('P.Invoice ID') : </strong>{{ $purchase->invoice_id }}
                    </li>
                    <li><strong>@lang('Purchase Form') : </strong>
                        {{ $purchase->branch ? $purchase->branch->name . '/' . $purchase->branch->branch_code : 'Head Office' }}
                    </li>
                    <li><strong>@lang('Stored Loacation') : </strong>
                        @if ($purchase->branch)
                            {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}
                            (<b>@lang('Branch/Company')</b>) ,<br>
                            {{ $purchase->branch ? $purchase->branch->city : '' }},
                            {{ $purchase->branch ? $purchase->branch->state : '' }},
                            {{ $purchase->branch ? $purchase->branch->zip_code : '' }},
                            {{ $purchase->branch ? $purchase->branch->country : '' }}.
                        @else
                            {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>HO</b>)
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li>
                        <strong>@lang('Total Due') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong>
                        {{ $purchase->due }}
                    </li>
                    <li><strong>@lang('Date') : </strong>{{date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date))  . ' ' . date($timeFormat, strtotime($purchase->time)) }} </li>
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
                            <span class="text-danger"><b>@lang('Due')</b></span>
                        @endif
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
        <table class="display modal-table table-sm table-striped">
            <thead>
                <tr class="bg-primary">
                    <th class="text-start text-white">@lang('Date')</th>
                    <th class="text-start text-white">@lang('Voucher No')</th>
                    <th class="text-start text-white">@lang('Method')</th>
                    <th class="text-start text-white">@lang('Type')</th>
                    <th class="text-start text-white">@lang('Account')</th>
                    <th class="text-end text-white">@lang('Amount')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                    <th class="text-start text-white">@lang('Action')</th>
                </tr>
            </thead>
            <tbody id="payment_list_body">
                @if (count($purchase->purchase_payments) > 0)
                    @foreach ($purchase->purchase_payments as $payment)
                        <tr data-info="{{ $payment }}">
                            <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) }}</td>

                            <td>{{ $payment->invoice_id }}</td>

                            <td>
                                @if ($payment->paymentMethod)
                                    {{ $payment->paymentMethod->name }}
                                @else
                                    {{ $payment->pay_mode }}
                                @endif
                            </td>

                            <td>
                                @if ($payment->is_advanced == 1)
                                    <b>@lang('PO Advance Payment')</b>
                                @else
                                    {{ $payment->payment_type == 1 ? 'Purchase Payment' : 'Received Return Amt.' }}
                                @endif
                            </td>

                            <td>{{ $payment->account ? $payment->account->name : '....' }}</td>

                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}
                            </td>

                            <td>
                                @if (auth()->user()->branch_id == $purchase->branch_id)

                                    @if (auth()->user()->permission->purchase['purchase_payment'] == '1')
                                        @if ($payment->payment_type == 1)
                                            <a href="{{ route('purchases.payment.edit', $payment->id) }}" id="edit_payment" class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                                        @else
                                            <a href="{{ route('purchases.return.payment.edit', $payment->id) }}" id="edit_return_payment" class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                                        @endif

                                        @if ($payment->supplier_payment_id == null)
                                            <a href="{{ route('purchases.payment.delete', $payment->id) }}" id="delete_payment"
                                            class="btn-sm"><i class="far fa-trash-alt text-danger"></i></a>
                                        @endif
                                    @endif
                                @endif

                                <a href="{{ route('purchases.payment.details', $payment->id) }}" id="payment_details" class="btn-sm"><i class="fas fa-eye text-primary"></i></a>
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
