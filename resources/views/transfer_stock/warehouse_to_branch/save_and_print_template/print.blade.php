@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<div class="transfer_print_template">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                        <h6 class="bill_name">@lang('Transfer Stock Details (To Branch)')</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('Warehouse (From)') : </strong></li>
                        <li><strong>@lang('Name') :</strong> {{ $transfer->warehouse->warehouse_name.'/'.$transfer->warehouse->warehouse_code }}</li>
                        <li><strong>@lang('Phone') : </strong>{{ $transfer->warehouse->phone }}</li>
                        <li><strong>@lang('Address') : </strong> {{ $transfer->warehouse->address }}</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('B.Location') (To) : </strong></li>
                        <li><strong>@lang('Name') :</strong> {{ $transfer->branch ? $transfer->branch->name.'/'.$transfer->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}</li>
                        <li><strong>@lang('Phone') : </strong> {{ $transfer->branch ? $transfer->branch->phone : json_decode($generalSettings->business, true)['phone'] }}</li>
                        @if ($transfer->branch)
                            <li><strong>@lang('Address') : </strong>
                                {{ $transfer->branch->city }},
                                {{ $transfer->branch->state }},
                                {{ $transfer->branch->zip_code }},
                                {{ $transfer->branch->country }}.
                            </li>
                        @else
                            {{ json_decode($generalSettings->business, true)['address'] }}
                        @endif

                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled float-end">
                        <li><strong>@lang('Date') : </strong> {{ $transfer->date }}</li>
                        <li><strong>@lang('Reference ID') : </strong> {{ $transfer->invoice_id }}</li>
                        <li><strong>@lang('Status') : </strong>
                            @if ($transfer->status == 1)
                                Pending
                            @elseif($transfer->status == 2)
                                Partial
                            @elseif($transfer->status == 3)
                               Completed
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <th class="text-start">SL</th>
                            <th class="text-start">@lang('Product')</th>
                            <th class="text-start">@lang('Unit Price')</th>
                            <th class="text-start">@lang('Quantity')</th>
                            <th class="text-start">@lang('Unit')</th>
                            <th class="text-start">@lang('Receive Qty')</th>
                            <th class="text-start">@lang('SubTotal')</th>
                        </tr>
                    </tr>
                </thead>
                <tbody class="transfer_print_product_list">
                    @foreach ($transfer->transfer_products as $transfer_product)
                        <tr>
                            <td class="text-start">{{ $loop->index + 1 }}</td>
                            @php
                                $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                            @endphp
                            <td class="text-start">{{ $transfer_product->product->name.$variant }}</td>
                            <td class="text-start">{{ $transfer_product->unit_price}}</td>
                            <td class="text-start">{{ $transfer_product->quantity }}</td>
                            <td class="text-start">{{ $transfer_product->unit }}</td>
                            <td class="text-start">{{ $transfer_product->received_qty.' ('.$transfer_product->unit.')' }}</td>
                            <td class="text-start">{{ $transfer_product->subtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-start" colspan="6"><strong>@lang('Net Total Amount') :</strong></td>
                        <td class="text-start" colspan="2">{{ $transfer->net_total_amount }}</td>
                    </tr>

                    <tr>
                        <th class="text-start" colspan="6">@lang('Shipping Charge')</th>
                        <td class="text-start" colspan="2">{{ $transfer->shipping_charge }}</td>
                    </tr>

                    <tr>
                        <th class="text-start" colspan="6">@lang('Grand Total')</th>
                        @php
                            $grandTotal = $transfer->net_total_amount  + $transfer->shipping_charge;
                        @endphp
                        <td class="text-start" colspan="2">{{ bcadd($grandTotal, 0, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br>

        <div class="note">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>@lang('Receiver')'s Signature</strong></p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>@lang('Signature Of Authority')</strong></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4 text-center">
                <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($transfer->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p class="p-0 m-0">{{ $transfer->invoice_id }}</b></small>
                @if (env('PRINT_SD_OTHERS') == true)
                    <small class="d-block">@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                @endif
            </div>
        </div>
    </div>
</div>
