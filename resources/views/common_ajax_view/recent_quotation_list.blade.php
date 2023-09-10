
@if (count($quotations) > 0)
    @foreach ($quotations as $quotation)
        <tr>
            <td class="text-start">{{ $loop->index + 1 }}</td>
            <td class="text-start">{{ $quotation->invoice_id }}</td>
            <td class="text-start">{{ $quotation->customer_id ? $quotation->customer->name : 'Walk-In-Customer' }}</td>
            <td class="text-start">{{ $quotation->total_payable_amount }}</td>
            <td class="text-start">

                @if ($quotation->created_by == 1)
                    <a id="editInvoice" href="{{ route('sales.edit', $quotation->id) }}" title="Edit" class=""> <i class="far fa-edit text-info"></i></a>
                @else
                    <a id="editInvoice" href="{{ route('sales.pos.edit', $quotation->id) }}" title="Edit" class=""> <i class="far fa-edit text-info"></i></a>
                @endif

                <a href="{{ route('sales.print', $quotation->id) }}" title="Print" id="only_print"> <i class="fas fa-print text-secondary"></i></a>
            </td>
        </tr>
    @endforeach
@else
    <tr><td colspan="5"><b>@lang('No Data Found').</b></td></tr>
@endif
