@if (count($sales))
    @foreach ($sales as $sale)
        <tr>
            <td class="text-start">{{ $loop->index + 1 }}</td>
            <td class="text-start">{{ $sale->invoice_id }}</td>
            <td class="text-start">{{ $sale->customer_id ? $sale->customer->name : 'Walk-In-Customer' }}</td>
            <td class="text-start">{{ $sale->total_payable_amount }}</td>
            <td class="text-start">
                @if ($sale->created_by == 1)
                    <a id="editInvoice" href="{{ route('sales.edit', $sale->id) }}" title="Edit" class=""> <i class="far fa-edit text-info"></i></a>
                @else
                    <a id="editInvoice" href="{{ route('sales.pos.edit', $sale->id) }}" title="Edit" class=""> <i class="far fa-edit text-info"></i></a>
                @endif
                
                <a href="{{ route('sales.print', $sale->id) }}" id="only_print" title="Print" class=""> <i class="fas fa-print text-secondary"></i></a>
            </td>
        </tr>  
    @endforeach
@else
    <tr><td colspan="5"><b>@lang('No Data Found').</b></td></tr>
@endif
