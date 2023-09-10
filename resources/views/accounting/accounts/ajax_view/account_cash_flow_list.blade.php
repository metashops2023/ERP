<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">@lang('Date')</th>
            <th class="text-start">@lang('Particular')</th>
            <th class="text-start">@lang('Voucher')</th>
            <th class="text-start">@lang('Debit')</th>
            <th class="text-start">@lang('Credit')</th>
            <th class="text-start">@lang('Balance')</th>
            <th class="text-center">@lang('Action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($accountCashFlows as $cashFlow)
            <tr>
                <td class="text-start">{{ date('d/m/Y', strtotime($cashFlow->date)) }}</td> 
                <td class="text-start">
                    @if ($cashFlow->transaction_type == 4)
                        @if ($cashFlow->cash_type == 1)
                            {!! '<b>@lang('Fund Transfer')</b> (To: '. $cashFlow->receiver_account->name.')'!!}
                        @else  
                            {!! '<b>@lang('Fund Transfer')</b> (From: '. $cashFlow->sender_account->name.')'!!}  
                        @endif
                    @elseif($cashFlow->transaction_type == 5)
                        <b>@lang('Deposit')</b>   
                    @elseif($cashFlow->transaction_type == 7)   
                        <b>@lang('Opening Balance')</b>    
                    @elseif($cashFlow->transaction_type == 3)  
                        @if($cashFlow->purchase_payment->payment_on == 1)
                            @if ($cashFlow->purchase_payment->is_advanced == 1)
                                <b>@lang('PO Advance Payment')</b><br>
                            @else
                                {{ $cashFlow->purchase_payment->payment_type == 1 ? 'Purchase Payment' : 'Purchase Return' }}  <br>
                            @endif
                            <span class="mt-1">{{ 'Supplier : ' .$cashFlow->purchase_payment->purchase->supplier->name }}</span>  <br>
                            <span class="mt-1">{!! '<b>@lang('Purchase Invoice') : </b>'. '<span class="text-primary">'.$cashFlow->purchase_payment->purchase->invoice_id.'</span>' !!}</span> <br>
                            <span class="mt-1">{!! '<b>@lang('Payment Voucher') : </b>'. '<span class="text-primary">'. $cashFlow->purchase_payment->invoice_id.'</span>' !!}</span>
                        @else   
                            {{ $cashFlow->purchase_payment->payment_type == 1 ? 'Purchase Due Payment' : 'Purchase Return Due Payment' }} <br>
                            <span class="mt-1">{{ 'Supplier : ' .$cashFlow->purchase_payment->purchase->supplier->name }}</span>  <br>
                            <span class="mt-1">{!! '<b>@lang('Payment Voucher') : </b>'. $cashFlow->purchase_payment->invoice_id !!}</span>  <br>
                            <span class="mt-1">{!! '<b>@lang('Payment Voucher') : </b>'. $cashFlow->purchase_payment->invoice_id !!}</span>
                        @endif
                    @elseif($cashFlow->transaction_type == 2)  
                        @if($cashFlow->sale_payment->payment_on == 1)
                            {{ $cashFlow->sale_payment->payment_type == 1 ? 'Sale Due Payment' : 'Sale Return Due Payment' }} <br>
                            <span class="mt-1">@lang('Customer') : {{ $cashFlow->sale_payment->sale->customer ? $cashFlow->sale_payment->sale->customer->name : 'Walk-In-Customer' }}</span>  <br>
                            <span class="mt-1">{!! '<b>@lang('Sale Invoice')</b>: '. $cashFlow->sale_payment->sale->invoice_id !!}</span><br>
                            <span class="mt-1">{!! '<b>@lang('Payment Voucher') : </b>'. $cashFlow->sale_payment->invoice_id !!}</span>
                        @else   
                            {{ $cashFlow->sale_payment->payment_type == 1 ? 'Sale Due Payment' : 'Sale Return' }}  <br>
                            <span class="mt-1">@lang('Customer') : {{ $cashFlow->sale_payment->sale->customer ? $cashFlow->sale_payment->sale->customer->name : 'Walk-In-Customer' }}</span>  <br>
                            <span class="mt-1">{!! '<b>@lang('Payment Invoice') :<b>'. $cashFlow->sale_payment->invoice_id !!}</b></span> <br> 
                        @endif  
                    @elseif($cashFlow->transaction_type == 6)  
                        <b>@lang('Expense')</b> <br>
                        <span class="mt-1"><b>@lang('Expense Invoice') :</b> {!! '<span class="text-primary">'.$cashFlow->expense_payment->expense->invoice_id.'</span>'  !!}</span>  <br>
                        <span class="mt-1">{!! '<b>@lang('Payment Voucher') : </b>'.'<span class="text-primary">'. $cashFlow->expense_payment->invoice_id.'</span>' !!}</span> 
                    @elseif($cashFlow->transaction_type == 8)  
                        <b>@lang('Payroll Payment')</b><br>
                        <b>@lang('Reference No') : </b> {{ $cashFlow->payroll->reference_no }}<br>
                        <span class="mt-1"><b>@lang('Payment Voucher No') :</b> {!! '<span class="text-primary">'.$cashFlow->payroll_payment->reference_no.'</span>'  !!}</span>   
                    @elseif($cashFlow->transaction_type == 10)  
                        <b>{{ $cashFlow->loan->type == 1 ? 'Pay Loan' : 'Get Loan' }}</b><br>
                        <b>{{ $cashFlow->loan->company->name }}</b><br>
                        <b>@lang('Reference No') : </b> {{ $cashFlow->loan->reference_no }}
                    @elseif($cashFlow->transaction_type == 11)  
                        <b>{{ $cashFlow->loan_payment->payment_type == 1 ? 'Pay Loan Due Receive' : 'Get Loan Due Paid' }}</b><br/>
                        <b>@lang('B.Location') :</b> {{ $cashFlow->loan_payment->branch ? $cashFlow->loan_payment->branch->name.'/'.$cashFlow->loan_payment->branch->branch_code.'(BL)' : json_decode($generalSettings->business, true)['shop_name'] .'(HO)' }}<br/>
                        <b>@lang('Company/Person'): </b> {{ $cashFlow->loan_payment->company->name }}<br/>
                        <b>@lang('Payment Voucher No') : </b> {{ $cashFlow->loan_payment->voucher_no }}
                    @elseif($cashFlow->transaction_type == 12)  
                        <b>{{ $cashFlow->supplier_payment->type == 1 ? 'Paid To Supplier(Purchase Due)' : 'Receive From Supplier(Return Due)' }}</b><br>
                        <b>@lang('Supplier') : </b>{{ $cashFlow->supplier_payment->supplier->name }}<br>
                        <b>@lang('Payment Voucher No') : </b> {{ $cashFlow->supplier_payment->voucher_no }}
                    @elseif($cashFlow->transaction_type == 13)  
                        <b>{{ $cashFlow->customer_payment->type == 1 ? 'Receive From Customer(Sale Due)' : 'Paid To Customer(Return Due)' }}</b><br>
                        <b>@lang('Customer') :</b> {{ $cashFlow->customer_payment->customer->name }}<br>
                        <b>@lang('Payment Voucher No') : </b> {{ $cashFlow->customer_payment->voucher_no }}
                    @endif
                </td> 
                <td class="text-start">{{ $cashFlow->admin ? $cashFlow->admin->prefix.' '.$cashFlow->admin->name.' '.$cashFlow->admin->last_name : '' }}</td>
                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($cashFlow->debit) }}</td>
                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($cashFlow->credit) }}</td>
                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($cashFlow->balance) }}</td>
                <td class="text-center">
                    <div class="dropdown table-dropdown">
                        @if ($cashFlow->transaction_type == 4 || $cashFlow->transaction_type == 5)
                            <a href="{{ route('accounting.accounts.account.delete.cash.flow', $cashFlow->id) }}" class="btn btn-sm btn-danger" id="delete">@lang('Delete')</a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        aaSorting: [[0, 'desc']],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
    });
</script>

