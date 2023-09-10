@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<style>
    .packing_slip_print_template{font-family: monospace!important;font-weight: bolder;}
</style>
<div class="">
    <div>
        <div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class=" text-center">
                        @if ($sale->branch)
                            <h3><strong>{{$sale->branch ? $sale->branch->name . '/' . $sale->branch->branch_code : ''}}</strong> </h3>
                            <p style="width: 60%; margin:0 auto;">{{ $sale->branch->city . ', ' . $sale->branch->state . ', ' . $sale->branch->zip_code . ', ' . $sale->branch->country }}</p>
                            <p><strong>@lang('Phone') :</strong> {{ $sale->branch->phone }}</p>
                        @else
                            <h3>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h3>
                            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                            <p><strong>@lang('Phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                        @endif
                        <h6 >@lang('Packing Slip')</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-3">
            <div class="row">
                <div class="col-4">
                    <ul class="list-unstyled">
                        <li>
                            <strong>@lang('Name') : </strong>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                        </li>
                        <li>
                            <strong>@lang('Address') : </strong>
                            @if ($sale->shipment_address)
                                {{  $sale->shipment_address }}
                            @else
                                {{ $sale->customer ? $sale->customer->address : '' }}
                            @endif
                        </li>
                        <li>
                            <strong>@lang('Tax Number') : </strong>{{ $sale->customer ? $sale->customer->tax_number : '' }}
                        </li>
                        <li>
                            <strong>@lang('Phone') : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
                        </li>
                    </ul>
                </div>
                <div class="col-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('Invoice ID') : </strong> {{ $sale->invoice_id }}
                        </li>
                        <li><strong>@lang('Date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}</li>
                        <li><img style="width: 100%; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}"></li>

                    </ul>
                </div>
                <div class="col-4">
                    <ul class="list-unstyled float-right">
                        <li><strong>@lang('Shipping Address') : </strong></li>
                        <li>
                            @if ($sale->shipment_address)
                                {{ $sale->shipment_address }}
                            @else
                                @if ($sale->customer)
                                    {{ $sale->customer->shipping_address }}
                                @endif
                            @endif
                        </li>
                        <li><strong>@lang('Delivered To') :</strong> {{ $sale->delivered_to }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="pt-3 pb-3">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="" class="text-start">SL</th>
                        <th class="text-start">@lang('Description')</th>
                        <th class="text-start">@lang('Unit')</th>
                        <th class="text-start">@lang('Quantity')</th>
                    </tr>
                </thead>
                <thead>
                    @foreach ($customerCopySaleProducts as $saleProduct)
                        <tr>
                            @php
                                $variant = $saleProduct->product_variant_id ? ' -' . $saleProduct->variant_name : '';
                            @endphp
                            <td class="text-start">{{ $loop->index + 1 }}</td>
                            <td class="text-start"><p><b> {{ $saleProduct->p_name }}</b> {{ $variant }}</p> </td>
                            <td class="text-start">{{ $saleProduct->unit }}
                            <td class="text-start">{{ $saleProduct->quantity }}</td>
                        </tr>
                    @endforeach
                </thead>
            </table>
        </div>
        <br><br>
        <div class="note">
            <div class="row">
                <div class="col-md-6">
                    <h6><strong>@lang('Authorized Signature')</strong></h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Packing slip print templete end-->
