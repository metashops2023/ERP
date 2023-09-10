@foreach ($invoices as $invoice)
    <li>
        <a href="#" id="selected_invoice" class="name" data-sale_id="{{ $invoice->id }}" data-customer_id="{{ $invoice->customer_id }}" data-invoice_due="{{ $invoice->due }}">
            {{ $invoice->invoice_id }}
        </a>
    </li>
@endforeach
