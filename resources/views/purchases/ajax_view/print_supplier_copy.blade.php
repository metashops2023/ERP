@php 
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp 

 <style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 20px;margin-right: 20px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed;top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
    <!-- Purchase Order print templete-->
    <div class="purchase_print_template">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        @if ($purchase->branch)
                            @if ($purchase->branch->logo != 'default.png')
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $purchase->branch->logo) }}">
                            @else 
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $purchase->branch->name }}</span>
                            @endif
                        @else 
                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                            @else 
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="heading text-center">
                            <h4 class="bill_name">@lang('Purchase Order Bill')</h4>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        
                    </div>
                </div>
            </div>

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Supplier') :- </strong></li>
                            <li><strong>@lang('Name') : </strong>{{ $purchase->supplier->name }}</li>
                            <li><strong>@lang('Address') : </strong>{{ $purchase->supplier->address }}</li>
                            <li><strong>@lang('Tax Number') : </strong> {{ $purchase->supplier->tax_number }}</li>
                            <li><strong>@lang('Phone') : </strong> {{ $purchase->supplier->phone }}</li>
                        </ul>
                    </div>
                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Ordered From') : </strong></li>
                            <li>
                                <strong>@lang('Business Location') : </strong> 
                                @if ($purchase->branch)
                                    {!! $purchase->branch->name.' '.$purchase->branch->branch_code.' <b>(BL)</b>' !!}
                                @else
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>@lang('Head Offiec')</b>)
                                @endif
                            </li>
                            <li><strong>@lang('Ordered Location') : </strong>
                                @if($purchase->branch_id)
                                    {{ $purchase->branch->city }}, {{ $purchase->branch->state }},
                                    {{ $purchase->branch->zip_code }}, {{ $purchase->branch->country }}
                                @else
                                    {{ json_decode($generalSettings->business, true)['address'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li><strong>PO.Invoice ID : </strong> {{ $purchase->invoice_id }}</li>
                            <li><strong>@lang('Purchase Date') : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}
                            </li>

                            <li><strong>@lang('Delivery Date') : </strong>
                                {{ $purchase->delivery_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->delivery_date)) : '' }}
                            </li>
                            
                            <li><strong>@lang('Purchase Status') : </strong>@lang('Ordered')</li>
                            <li><strong>@lang('Created By') : </strong>
                                {{ $purchase->admin->prefix.' '.$purchase->admin->name.' '.$purchase->admin->last_name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="purchase_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">SL</th>
                            <th class="text-start">@lang('Description')</th>
                            <th scope="col">@lang('Ordered Quantity')</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @php $index = 0; @endphp
                        @foreach ($purchase->purchase_order_products as $product)
                            <tr>
                                @php
                                    $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : ''; 
                                @endphp
                                <td class="text-start">{{ $index + 1 }}</td>
                                <td class="text-start">
                                    {{ Str::limit($product->product->name, 25).' '.$variant }} 
                                    <small>{!! $product->description ? '<br/>'.$product->description : '' !!}</small>
                                </td>
                                <td>{{ $product->order_quantity }}</td>
                            </tr>
                            @php $index++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

            <br>
            <div class="row">
                <div class="col-md-6">
                    <h6>@lang('RECEIVED BY') : </h6>
                </div>

                <div class="col-md-6 text-end">
                    <h6>@lang('AUTHORIZED BY') : </h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($purchase->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{$purchase->invoice_id}}</p>
                </div>
            </div>

            @if (env('PRINT_SD_PURCHASE') == true)
                <div class="row">
                    <div class="col-md-12 text-center">
                        <small>@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                    </div>
                </div>
            @endif

            <div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer">
                <small style="font-size: 5px; float: right;" class="text-end">
                    Print Date: {{ date('d-m-Y , h:iA') }}
                </small>
            </div>
        </div>
    </div>
 <!-- Purchase print templete end-->
