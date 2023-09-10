@foreach ($sales as $sale)
    <tr>
        <td class="text-start">{{ $loop->index + 1 }}</td>
        <td class="text-start">{{ $sale->invoice_id }}</td>
        <td class="text-start">{{ $sale->customer_id ? $sale->customer->name : 'Walk-In-Customer' }}</td>
        <td class="text-start">{{ $sale->total_payable_amount }}</td>

        <td class="text-start">
            <a id="editInvoice" href="{{ route('sales.pos.edit', $sale->id) }}" title="Edit" class="" tabindex="-1"> <i class="far fa-edit text-info"></i></a>
            <a id="delete" href="{{ route('sales.delete', $sale->id) }}" title="Delete" class="" tabindex="-1"> <i class="far fa-trash-alt text-danger"></i></a>
            <a href="{{ route('sales.print', $sale->id) }}" id="only_print" title="Print" class="" tabindex="-1"> <i class="fas fa-print text-secondary"></i></a>
        </td>
    </tr>
@endforeach
