@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp 
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-xl">
      <div class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
              @lang('Transfer Details (Invoice ID') : <strong>{{ $transfer->invoice_id }}</strong>)
          </h5>
          <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
            class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4 text-left">
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

                <div class="col-md-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('Warehouse (From)') : </strong></li>
                        <li><strong>@lang('Name') :</strong>{{ $transfer->warehouse->warehouse_name.'/'.$transfer->warehouse->warehouse_code }}</li>
                        <li><strong>@lang('Phone') : </strong>{{ $transfer->warehouse->phone }}</li>
                        <li><strong>@lang('Address') : </strong>{{ $transfer->warehouse->address }}</li>
                    </ul>
                </div>

                <div class="col-md-4 text-left">
                    <ul class="list-unstyled float-right">
                        <li><strong>@lang('Date') : </strong> {{ $transfer->date }}</li>
                        <li><strong>@lang('Reference ID') : </strong> {{ $transfer->invoice_id }}</li>
                        <li><strong>@lang('Status') : </strong> 
                            @if ($transfer->status == 1) 
                                <span class="badge bg-danger">@lang('Pending')</span>
                            @elseif($transfer->status == 2)
                                <span class="badge bg-primary">@lang('Partial')</span>
                            @elseif($transfer->status == 3)
                               <span class="badge bg-success">@lang('Completed')</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div><br>
            <div class="row">
                <div class="table-responsive">
                    <table id="" class="table modal-table table-striped table-sm">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="text-start">SL</th>
                                <th class="text-start">@lang('Product')</th>
                                <th class="text-start">@lang('Unit Price')</th>
                                <th class="text-start">@lang('Quantity')</th>
                                <th class="text-start">@lang('Unit')</th>
                                <th class="text-start">@lang('Pending Qty')</th>
                                <th class="text-start">@lang('Received Qty')</th>
                                <th class="text-start">@lang('SubTotal')</th>
                            </tr>
                        </thead>
                        <tbody class="transfer_print_product_list">
                            @foreach ($transfer->transfer_products as $transfer_product)
                                <tr>
                                    <td class="text-start">{{ $loop->index + 1 }}</td>
                                    @php
                                        $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                                        $sku = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_code.')' : $transfer_product->product->product_code;
                                    @endphp
                                    <td class="text-start">{{ $transfer_product->product->name.$variant.' ('.$sku.')' }}</td>
                                    <td class="text-start">{{ $transfer_product->unit_price}}</td>
                                    <td class="text-start">{{ $transfer_product->quantity }}</td>
                                    <td class="text-start">{{ $transfer_product->unit }}</td>
                                    @php
                                        $panding_qty = $transfer_product->quantity - $transfer_product->received_qty;
                                    @endphp
                                    <td class="text-start text-danger"><b>{{ $panding_qty.' ('.$transfer_product->unit.')' }}</b></td>
                                    <td class="text-start">{{ $transfer_product->received_qty.' ('.$transfer_product->unit.')' }}</td>
                                    <td class="text-start">{{ $transfer_product->subtotal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-6 offset-6">
                    <div class="table-responsive">
                        <table class="table modal-table table-sm">
                            <tr>
                                <th class="text-start" colspan="6">@lang('Net Total Amount') :</th>
                                <th class="text-start" colspan="2">
                                    {{json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $transfer->net_total_amount }}
                                </th>
                            </tr>
                        
                            <tr>
                                <th class="text-start" colspan="6">@lang('Shipping Charge')</th>
                                <th class="text-start" colspan="2">
                                    {{json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $transfer->shipping_charge }}
                                </th>
                            </tr>
        
                            <tr>
                                <th class="text-start" colspan="6">@lang('Grand Total')</th>
                                @php
                                    $grandTotal = $transfer->net_total_amount  + $transfer->shipping_charge;
                                @endphp
                                <th class="text-start" colspan="2">
                                    {{json_decode($generalSettings->business, true)['currency'] }}
                                    {{ bcadd($grandTotal, 0, 2) }}
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div> <br> 
          <hr class="p-0 m-0">
          <div class="row">
            <div class="col-md-6">
                <div class="details_area">
                    <h6>@lang('Additional Note') : </h6>
                    <p>{{ $transfer->additional_note }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="details_area">
                    <h6>@lang('Receiver Note') : </h6>
                    <p>{{ $transfer->receiver_note }}</p>
                </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">@lang('Close')</button>
            <button type="submit" class="c-btn button-success print_btn float-end">@lang('Print')</button>
        </div>
      </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Print Template-->
<div class="transfer_print_template d-none">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                        <p>{{ json_decode($generalSettings->business, true)['address'] }}</p>
                        <p>@lang('Phone') : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                        <h6>@lang('Transfer Stock (To Warehouse)')</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('B.Location') (@lang('From')): </strong></li>
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
                    <ul class="list-unstyled">
                        <li><strong>@lang('Warehouse (To)') : </strong></li>
                        <li><strong>@lang('Name') :</strong> {{ $transfer->warehouse->warehouse_name.'/'.$transfer->warehouse->warehouse_code }}</li>
                        <li><strong>@lang('Phone') : </strong>{{ $transfer->warehouse->phone }}</li>
                        <li><strong>@lang('Address') : </strong> {{ $transfer->warehouse->address }}</li>
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
                        <th class="text-start" colspan="6">@lang('Net Total Amount') :</th>
                        <th class="text-start" colspan="2">
                            {{json_decode($generalSettings->business, true)['currency'] }}
                            {{ $transfer->net_total_amount }}
                        </th>
                    </tr>
                   
                    <tr>
                        <th class="text-start" colspan="6">@lang('Shipping Charge') :</th>
                        <th class="text-start" colspan="2">
                            {{json_decode($generalSettings->business, true)['currency'] }}
                            {{ $transfer->shipping_charge }}
                        </th>
                    </tr>

                    <tr>
                        <th class="text-start" colspan="6">@lang('Grand Total') :</th>
                        @php
                            $grandTotal = $transfer->net_total_amount  + $transfer->shipping_charge;
                        @endphp
                        <th class="text-start" colspan="2">
                            {{json_decode($generalSettings->business, true)['currency'] }}
                            {{ bcadd($grandTotal, 0, 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <br><br>
   
        <div class="row">
            <div class="col-md-6">
                <p><strong>@lang('Receiver')'s Signature</strong></p>
            </div>
            <div class="col-md-6 text-end">
                <p><strong>@lang('Signature Of Authority')</strong></p>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($transfer->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p class="p-0 m-0"><b>{{ $transfer->invoice_id }}</b></p>
                @if (env('PRINT_SD_OTHERS') == true)
                    <small class="d-block">@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                @endif
            </div>
        </div>
     
    </div>
</div>
<!-- Print Template End-->