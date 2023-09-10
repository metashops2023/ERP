<div class="payment_table">
    <div class="table-responsive">
        <table class="table modal-table table-sm table-striped custom-table">
            <thead>
                <tr class="bg-primary text-white">
                    <th class="text-start">Date</th>
                    <th class="text-start">Invoice ID</th>
                    <th class="text-start">Amount</th>
                    <th class="text-start">Account</th>
                    <th class="text-start">Method</th>
                    <th class="text-start">Type</th>
                    <th class="text-start">Action</th>
                </tr>
            </thead>
            <tbody id="p_details_payment_list">
                @if (count($sale->sale_payments) > 0)
                    @foreach ($sale->sale_payments as $payment)
                        <tr data-info="{{ $payment }}">
                            <td class="text-start">{{ date('d/m/Y', strtotime($payment->date)) }}</td>
                            <td class="text-start">{{ $payment->invoice_id }}</td>
                            
                            <td class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] . ' ' . $payment->paid_amount }}
                            </td>

                            <td class="text-start">
                                {{ $payment->account ? $payment->account->name : '----' }}
                            </td>

                            <td class="text-start">
                                {{ $payment->paymentMethod ? $payment->paymentMethod->name : $payment->pay_mode }}
                            </td>

                            <td class="text-start">
                                {{ $payment->payment_type == 1 ? 'Sale due' : 'Return due' }}
                            </td>
                            
                            <td class="text-start">
                                @if (auth()->user()->branch_id == $sale->branch_id)
                                    @if ($payment->payment_type == 1)
                                        <a href="{{ route('sales.payment.edit', $payment->id) }}"
                                            id="edit_payment" class="btn-sm"><i
                                                class="fas fa-edit text-info"></i></a>
                                    @else
                                        <a href="{{ route('sales.return.payment.edit', $payment->id) }}"
                                            id="edit_return_payment" class="btn-sm"><i
                                                class="fas fa-edit text-info"></i></a>
                                    @endif

                                    <a href="{{ route('sales.payment.details', $payment->id) }}"
                                        id="payment_details" class="btn-sm">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>

                                    <a href="{{ route('sales.payment.delete', $payment->id) }}"
                                        id="delete_payment" class="btn-sm">
                                        <i class="far fa-trash-alt text-danger"></i>
                                    </a>
                                @else
                                    ............
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">No Data Found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>