@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp 
<div class="transfer_print_template">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        <h6 class="bill_name">@lang('Transfer Stock Details (Business Location To Business Location)')</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('From') : </strong></li>
                        <li><strong>@lang('B.Location') Name :</strong> {{ $transfer->sender_branch ? $transfer->sender_branch->name.'/'.$transfer->sender_branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}</li>
                        <li><strong>@lang('Phone') : </strong> {{ $transfer->sender_branch ? $transfer->sender_branch->phone : json_decode($generalSettings->business, true)['phone'] }}</li>
                        
                        <li><strong>@lang('Stock Location') : </strong> 
                            @if ($transfer->sender_warehouse)

                                {{ $transfer->sender_warehouse->warehouse_name.'/'.$transfer->sender_warehouse->warehouse_code.'(WH)' }}
                            @else     

                                {{ $transfer->sender_branch ? $transfer->sender_branch->name.'/'.$transfer->sender_branch->branch_code.'(B.L)' : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>To : </strong></li>
                        <li><strong>@lang('B.Location') Name :</strong> {{ $transfer->receiver_branch ? $transfer->receiver_branch->name.'/'.$transfer->receiver_branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}</li>
                        <li><strong>@lang('Phone') : </strong> {{ $transfer->receiver_branch ? $transfer->receiver_branch->phone : json_decode($generalSettings->business, true)['phone'] }}</li>
                        
                        @if ($transfer->receiver_branch)
                            <li><strong>@lang('Address') : </strong> 
                                {{ $transfer->receiver_branch->city }},
                                {{ $transfer->receiver_branch->state }},
                                {{ $transfer->receiver_branch->zip_code }},
                                {{ $transfer->receiver_branch->country }}.
                            </li>
                        @else 
                            <li><strong>@lang('Address') : </strong> 
                                {{ json_decode($generalSettings->business, true)['address'] }}
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled float-end">
                        <li><strong>@lang('Date') : </strong> {{ $transfer->date }}</li>
                        <li><strong>@lang('Reference ID') : </strong> {{ $transfer->ref_id }}</li>
                        <li><strong>@lang('Status') : </strong> 
                            Pending
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <th class="text-start">SL</th>
                            <th class="text-start">@lang('Product')</th>
                            <th class="text-start">@lang('Unit Cost Inc').Tax</th>
                            <th class="text-start">@lang('Quantity')</th>
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
                            <td class="text-start">{{ $transfer_product->unit_cost_inc_tax }}</td>
                            <td class="text-start">{{ $transfer_product->send_qty.'/'.$transfer_product->product->unit->name }}</td>
                            <td class="text-start">{{ $transfer_product->received_qty.'/'.$transfer_product->product->unit->name }}</td>
                            <td class="text-start">{{ $transfer_product->subtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td class="text-end" colspan="5"><strong>@lang('Total Stock Value') :</strong></td>
                        <td class="text-start">{{ $transfer->total_stock_value }}</td>
                    </tr>
                   
                    <tr>
                        <th class="text-end" colspan="5">@lang('Transfer Cost')</th>
                        <td class="text-start">{{ $transfer->transfer_cost }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br>

        <div class="note">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>@lang('CHECKED BY')</strong></p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>@lang('APPROVED BY')</strong></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($transfer->ref_id, $generator::TYPE_CODE_128)) }}">

                <p class="p-0 m-0">{{ $transfer->ref_id }}</b></small>

                @if (env('PRINT_SD_OTHERS') == true)
                    <small class="d-block">@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                @endif
            </div>
        </div>
    </div>
</div>