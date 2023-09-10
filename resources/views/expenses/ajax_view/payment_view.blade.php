<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
</style>
<!--begin::Form-->
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong> @lang('Reference ID') : </strong>{{ $expense->invoice_id }} </li>
                    <li><strong>@lang('Business Location') : </strong>
                        {{ $expense->branch ? $expense->branch->name.''.$expense->branch->branch_code : 'Head Office' }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>@lang('Total Due') : </strong>{{ $expense->due }}</li>
                    <li><strong>@lang('Date') : </strong>{{ $expense->date }}</li>
                    <li><strong>@lang('Payment Status') : </strong>
                        @php
                           $payable = $expense->net_total_amount;
                        @endphp

                        @if ($expense->due <= 0)
                            <span class="badge bg-success">@lang('Paid')</span>
                        @elseif ($expense->due > 0 && $expense->due < $payable)
                            <span class="badge bg-primary text-white">@lang('Partial')</span>
                        @elseif ($payable == $expense->due)
                            <span class="badge bg-danger text-white">@lang('Due')</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="payment_list_table">
    <div class="data_preloader modal_preloader"> <h6><i class="fas fa-spinner"></i> @lang('Processing')...</h6></div>
    <div class="table-responsive">
        <table class="display modal-table table-sm table-striped">
            <thead>
                <tr>
                    <th class="text-start">@lang('Date')</th>
                    <th class="text-start">@lang('Voucher No')</th>
                    <th class="text-start">@lang('Note')</th>
                    <th class="text-start">@lang('Amount')</th>
                    <th class="text-start">@lang('Method')</th>
                    <th class="text-start">@lang('Type')</th>
                    <th class="text-start">@lang('Account')</th>
                    <th class="text-start">@lang('Action')</th>
                </tr>
            </thead>
            <tbody id="payment_list_body">
                @if (count($expense->expense_payments) > 0)
                    @foreach ($expense->expense_payments as $payment)
                        <tr data-info="{{ $payment }}">
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) }}</td>
                            <td class="text-start">{{ $payment->invoice_id }}</td>
                            <td class="text-start">{{ $payment->note }}</td>
                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.$payment->paid_amount }}</td>
                            <td class="text-start">
                                @if ($payment->payment_method)
                                      {{ $payment->payment_method->name }}
                                @else
                                    {{ $payment->pay_mode }}
                                @endif
                            </td>
                            <td class="text-start">{{ 'Expense due'  }}</td>
                            <td class="text-start">{{ $payment->account ? $payment->account->name : 'N/A' }}</td>
                            <td class="text-start">
                                <a href="{{ route('expenses.payment.edit', $payment) }}" id="edit_payment" class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                                <a href="{{ route('expenses.payment.details', $payment) }}" id="payment_details" class="btn-sm"><i class="fas fa-eye text-primary"></i></a>
                                <a href="{{ route('expenses.payment.delete', $payment->id) }}" id="delete_payment" class="btn-sm"><i class="far fa-trash-alt text-danger"></i></a>
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
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
