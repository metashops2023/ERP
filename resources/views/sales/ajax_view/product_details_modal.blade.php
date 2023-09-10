@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('Sale Details (Invoice ID)') : <strong>
                        <span class="head_invoice_id">{{ $sale->invoice_id }}</span>
                    </strong>
                </h5>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times"></span>
                </a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li>
                                <strong>@lang('Customer') :- </strong>
                            </li>

                            <li>
                                <strong>@lang('Name') : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>

                            <li>
                                <strong>@lang('Address') : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                            </li>

                            <li>
                                <strong>@lang('Tax Number') : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                            </li>

                            <li>
                                <strong>@lang('Phone') : </strong>{{ $sale->customer ? $sale->customer->phone : '' }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Sale From') : </strong></li>
                            @if ($sale->branch)
                                <li>
                                    <strong>@lang('Stock Location') : </strong>
                                    {{ $sale->branch->name }}/{{ $sale->branch->branch_code }}
                                </li>
                                <li>
                                    <strong>@lang('Address') : </strong>
                                    {{ $sale->branch->city }}, {{ $sale->branch->state }},
                                        {{ $sale->branch->zip_code }}, {{ $sale->branch->country }}
                                </li>
                                <li><strong>@lang('Phone') : </strong> {{ $sale->branch->phone }}</li>
                            @else
                                <li><strong>@lang('Stock Location') : </strong>
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b>
                                </li>
                                <li><strong>@lang('Address') : </strong>{{ json_decode($generalSettings->business, true)['address'] }}</li>
                                <li><strong>@lang('Phone') : </strong>{{ json_decode($generalSettings->business, true)['phone'] }}</li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . $sale->time }}</li>
                            <li><strong> {{ $sale->status == 1 ? 'Invoice ID' : 'Order No' }}  : </strong> {{ $sale->invoice_id }}</li>
                            <li><strong>@lang('Sale Status') : </strong>
                                @if ($sale->status == 1)
                                    <span class="badge bg-success">@lang('Final')</span>
                                @elseif($sale->status == 2)
                                    <span class="badge bg-primary">@lang('Draft')</span>
                                @elseif($sale->status == 3)
                                    <span class="badge bg-dark">@lang('Ordered')</span>
                                @endif
                            </li>
                            <li><strong>@lang('Payment Status') : </strong>
                                @php
                                    $payable = $sale->total_payable_amount - $sale->sale_return_amount;
                                @endphp
                                @if ($sale->due <= 0)
                                    <span class="badge bg-success"> @lang('Paid') </span>
                                @elseif ($sale->due > 0 && $sale->due < $payable)
                                    <span class="badge bg-primary text-white">@lang('Partial')</span>
                                @elseif ($payable == $sale->due)
                                    <span class="badge bg-danger text-white">@lang('Due')</span>
                                @endif
                            </li>
                            <li><strong>@lang('Shipment Status') : </strong>
                                @if ($sale->shipment_status == null)
                                    <span class="badge bg-danger">@lang('Not-Available')</span>
                                @elseif($sale->shipment_status == 1)
                                    <span class="badge bg-warning">@lang('Ordered')</span>
                                @elseif($sale->shipment_status == 2)
                                    <span class="badge bg-secondary">@lang('Packed')</span>
                                @elseif($sale->shipment_status == 3)
                                    <span class="badge bg-primary">@lang('Shipped')</span>
                                @elseif($sale->shipment_status == 4)
                                    <span class="badge bg-success">@lang('Delivered')</span>
                                @elseif($sale->shipment_status == 5)
                                    <span class="badge bg-info">@lang('Cancelled')</span>
                                @endif
                            </li>
                            <li><strong>@lang('Created By') : </strong>
                                @php
                                    $admin_role = '';
                                    $prefix = '';
                                    $name = $lastName = '';
                                    if ($sale->admin) {
                                        if ($sale->admin->role_type == 1) {

                                            $admin_role = ' (Super-Admin)';
                                        } elseif ($sale->admin->role_type == 2) {

                                            $admin_role = ' (Admin)';
                                        } elseif ($sale->admin->role_type == 3) {

                                            $admin_role = '(' . $sale->admin->role->name . ')';
                                        }

                                        $prefix = $sale->admin ? $sale->admin->prefix : '';
                                        $name = $sale->admin ? $sale->admin->name : '';
                                        $lastName = $sale->admin ? $sale->admin->last_name : '';
                                    }
                                @endphp
                                {{ $admin_role ? $prefix . ' ' . $name . ' ' . $lastName . $admin_role : 'N/A' }}
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-start">@lang('S/L')</th>
                                    <th class="text-start">@lang('Product')</th>
                                    <th class="text-start">@lang('Stock Location')</th>
                                    <th class="text-start">@lang('Warranty')</th>
                                    <th class="text-end">@lang('Quantity')</th>
                                    <th class="text-end">@lang('Unit Price Exc Tax') ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                    <th class="text-end">@lang('Unit Discount') ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                    <th class="text-end">@lang('Unit Tax') ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                    <th class="text-end">@lang('Unit Price Inc Tax') ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                    <th class="text-end">@lang('SubTotal') ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                </tr>
                            </thead>
                            <tbody class="sale_product_list">
                                @foreach ($sale->sale_products as $saleProduct)
                                    <tr>
                                        <td class="text-start">{{ $loop->index + 1 }}</td>
                                        @php
                                            $variant = $saleProduct->variant ? ' -' . $saleProduct->variant->variant_name : '';
                                        @endphp
                                        <td class="text-start">
                                            {{ $saleProduct->product->name . $variant }}<br/>
                                            <small>{{ $saleProduct->description }}</small>
                                        </td>
                                        <td class="text-start">
                                            @if ($saleProduct->stock_warehouse_id)
                                                {{ $saleProduct->warehouse->warehouse_name.'/'.$saleProduct->warehouse->warehouse_code }}
                                            @else
                                                @if ($saleProduct->stock_branch_id)

                                                    {{ $saleProduct->branch->name.'/'.$saleProduct->branch->branch_code }}
                                                @else

                                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}<b>(HO)</b>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-start">
                                            @if ($saleProduct->product->warranty_id)
                                                {{ $saleProduct->product->warranty->duration . ' ' . $saleProduct->product->warranty->duration_type }}
                                                {{ $saleProduct->product->warranty->type == 1 ? 'Warranty' : 'Guaranty' }}
                                                {!! $saleProduct->product->warranty->discription ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                            @else
                                                NO
                                            @endif
                                        </td>
                                        <td class="text-end">{{ $saleProduct->quantity }}</td>
                                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_exc_tax) }}
                                        </td>
                                        @php
                                            $DiscountType = $saleProduct->unit_discount_type == 1 ? ' (Fixed)' : ' (' . $saleProduct->unit_discount . '%)';
                                        @endphp
                                        <td class="text-end">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount) . $DiscountType }}
                                        </td>
                                        <td class="text-end">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_tax_amount) . ' (' . $saleProduct->unit_tax_percent . '%)' }}
                                        </td>
                                        <td class="text-end">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}
                                        </td>
                                        <td class="text-end">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        @if (auth()->user()->permission->sale['sale_payment'] == '1')
                            @include('sales.ajax_view.partials.add_sale_details_payment_list')
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table modal-table table-sm">
                                <tr>
                                    <th class="text-end">@lang('Net Total Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        <span class="net_total">
                                            {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                        </span>
                                    </td>
                                </tr>

                                {{-- <tr>
                                    <th class="text-start">@lang('Order Discount') :</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        <span class="order_discount">
                                            @php
                                                $discount_type = $sale->order_discount_type == 1 ? ' (Fixed)' : '%';
                                            @endphp
                                            {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) . $discount_type }}
                                        </span>
                                    </td>
                                </tr> --}}

                                <tr>
                                    <th class="text-end"> @lang('Order Discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        @if ($sale->order_discount_type == 1)
                                            {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} (Fixed)
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} ({{ $sale->order_discount }}%)
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Vat/Tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) . ' (' . $sale->order_tax_percent . '%)' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Shipment Charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Grand Total') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Sale Return') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->sale_return_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Total Paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Total Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="details_area">
                            <p><b>@lang('Shipping Details')</b> : </p>
                            <p class="shipping_details">
                                {{ $sale->shipment_details ? $sale->shipment_details : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="details_area">
                            <p><b>@lang('Sale Note')</b> : </p>
                            <p class="sale_note">{{ $sale->sale_note ? $sale->sale_note : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                @if ($sale->created_by == 1)
                    @if (auth()->user()->permission->sale['edit_add_sale'] == '1')
                        <a class="btn btn-sm btn-secondary" href="{{ route('sales.edit', $sale->id) }}"> @lang('Edit')</a>
                    @endif
                @else
                    @if (auth()->user()->permission->sale['pos_edit'] == '1')
                        <a class="footer_btn btn btn-sm btn-secondary" class="btn btn-sm btn-secondary" href="{{ route('sales.pos.edit', $sale->id) }}"> @lang('Edit')</a>
                    @endif
                @endif

                @if (auth()->user()->permission->sale['shipment_access'] == '1')
                    <button type="button" id="print_packing_slip" href="{{ route('sales.packing.slip', $sale->id) }}"
                    class="footer_btn btn btn-sm btn-success action_hideable">@lang('Print Packing Slip')</button>
                @endif

                {{-- <button type="button" class="footer_btn btn btn-sm btn-info print_challan_btn text-white action_hideable">@lang('Print Challan')</button> --}}
                <button type="button" class="footer_btn btn btn-sm btn-primary print_btn text-nowrap">@lang('Print') {{ $sale->status == 1 ? __('Invoice') : __('Order') }} </button>
                <button type="button" class="footer_btn btn btn-sm btn-primary print_b2c_btn text-nowrap">@lang('Print') {{ $sale->status == 1 ? __('Invoice') : __('Order') }} B2C</button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Sale print templete-->
@if ($sale->branch && $sale->branch->add_sale_invoice_layout)
    @include('sales.ajax_view.partials.add_sale_branch_invoice_layout')
    @include('sales.ajax_view.partials.add_sale_branch_invoice_b2c_layout')
    @include('sales.ajax_view.partials.add_sale_branch_challan_layout')
@else
    @include('sales.ajax_view.partials.add_sale_default_lnvoice_layout')
    @include('sales.ajax_view.partials.add_sale_default_lnvoice_b2c_layout')
    @include('sales.ajax_view.partials.add_sale_default_challan_layout')
@endif
<!-- Sale print templete end-->

<script>
  var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
    var b= ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

    function inWords (num) {
        if ((num = num.toString()).length > 9) return 'overflow';
        n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return; var str = '';
        str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
        str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
        str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
        str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
        str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + ' ' : '';
        return str;
    }
    document.getElementById('inword').innerHTML = inWords(parseInt("{{ $sale->total_payable_amount }}"));
</script>
