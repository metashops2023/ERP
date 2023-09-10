@foreach ($quotations as $quotation)
    <tr>
        <td class="text-start">{{ $loop->index + 1 }}</td>
        <td class="text-start">{{ $quotation->invoice_id }}</td>
        <td class="text-start">{{ $quotation->customer_id ? $quotation->customer->name : 'Walk-In-Customer' }}</td>
        <td class="text-start">{{ $quotation->total_payable_amount }}</td>
        <td class="text-start">
            <a id="editInvoice" href="{{ route('sales.pos.edit', $quotation->id) }}" title="Edit" class="" tabindex="-1"> <i class="far fa-edit text-info"></i></a>
            <a id="delete" href="{{ route('sales.delete', $quotation->id) }}" title="Delete" class="" tabindex="-1"> <i class="far fa-trash-alt text-danger"></i></a>
            <a href="{{ route('sales.print', $quotation->id) }}" title="Print" id="only_print" tabindex="-1"> <i class="fas fa-print text-secondary"></i></a>
        </td>
    </tr>
@endforeach
