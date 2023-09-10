@if (count($drafts) > 0)
    @foreach ($drafts as $draft)
        <tr class="text-center">
            <td class="text-start">{{ $loop->index + 1 }}</td>
            <td class="text-start">{{ $draft->invoice_id }}</td>
            <td class="text-start">{{ $draft->customer_id ? $draft->customer->name : 'Walk-In-Customer' }}</td>
            <td class="text-start">{{ $draft->total_payable_amount }}</td>

            <td class="text-start">
                @if ($draft->created_by == 1)
                    <a id="editInvoice" href="{{ route('sales.edit', $draft->id) }}" title="Edit" class=""> <i class="far fa-edit text-info"></i></a>
                @else
                    <a id="editInvoice" href="{{ route('sales.pos.edit', $draft->id) }}" title="Edit" class=""> <i class="far fa-edit text-info"></i></a>
                @endif

                <a href="{{ route('sales.print', $draft->id) }}" id="only_print" title="Print"> <i class="fas fa-print text-secondary"></i></a>
            </td>
        </tr>
    @endforeach
@else
    <tr><td colspan="5"><b>@lang('No Data Found').</b></td></tr>
@endif

