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
 <!-- Purchase print templete-->
    <div class="production_print_template">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-12 text-center">
                        <h6>
                            @if ($production->branch_id)
                                {{ $production->branch->name.'/'.$production->branch->branch_code }}<b>(BL)</b>
                            @else
                                {{ json_decode($generalSettings->business, true)['shop_name'] }}<b>(HO)</b>
                            @endif
                        </h6>
                        <p style="width: 60%; margin:0 auto;">
                            @if ($production->branch_id)
                                {{ $production->branch->city.', '.$production->branch->state.', '.$production->branch->zip_code.', '.$production->branch->country }}<b>(BL)</b>
                            @else
                                {{ json_decode($generalSettings->business, true)['address'] }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="heading_area">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        @if ($production->branch_id)
                            @if ($production->branch->logo != 'default.png')
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $production->branch->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $production->branch->name }}</span>
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
                            <p style="margin-top: 10px;" class="bill_name"><strong>@lang('Menufacturing Bill')</strong></p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">

                    </div>
                </div>
            </div>

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Stored Location') : </strong>
                                @if ($production->warehouse_id)
                                    {{ $production->warehouse->warehouse_name.'/'.$production->warehouse->warehouse_code }}<b>(WH)</b>
                                @else
                                    @if ($production->branch_id)
                                        {{ $production->branch->name.'/'.$production->branch->branch_code }}<b>(BL)</b>
                                    @else
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}<b>(HO)</b>
                                    @endif
                                @endif
                            </li>
                            <li><strong>@lang('Ingredients Stock Location') : </strong>
                                @if ($production->stock_warehouse_id)
                                    {{ $production->stock_warehouse->warehouse_name.'/'.$production->stock_warehouse->warehouse_code }}<b>(WH)</b>
                                @else
                                    @if ($production->stock_branch_id)
                                        {{ $production->stock_branch->name.'/'.$production->stock_branch->branch_code }}<b>(BL)</b>
                                    @else
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}<b>(HO)</b>
                                    @endif
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li>
                                <strong>@lang('Production Item') : </strong>
                                {{ $production->product->name }} {{ $production->variant_id ? $production->variant->variant_name : '' }} {{ $production->variant_id ? $production->variant->variant_code : $production->product->product_code }}
                            </li>
                            <li>
                                <strong>@lang('Production Status'): </strong>
                                @if ($production->is_final == 1)
                                    <span class="text-success">@lang('Final')</span>
                                @else
                                    <span class="text-hold">@lang('Hold')</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Voucher No') : </strong> {{ $production->reference_no }}</li>
                            <li><strong>@lang('Date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($production->date)) . ' ' . date($timeFormat, strtotime($production->time)) }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="purchase_product_table pt-3 pb-3">
                <p><strong>@lang('Ingredients List')</strong></p>
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">@lang('Ingredient Name')</th>
                            <th scope="col">@lang('Input Qty')</th>
                            <th scope="col">@lang('Unit Cost Inc').Tax({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                            <th scope="col">@lang('SubTotal')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($production->ingredients as $ingredient)
                            <tr>
                                @php
                                    $variant = $ingredient->variant_id ? ' ('.$ingredient->variant->variant_name.')' : '';
                                @endphp

                                <td>{{ Str::limit($ingredient->product->name, 40).' '.$variant }}</td>
                                <td>{{ $ingredient->input_qty }}</td>
                                <td>
                                    {{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}
                                </td>
                                <td>{{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <br>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>@lang('Production Quantity And Total Cost')</strong></p>
                    <table class="table modal-table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th class="text-end">@lang('Output Quantity') : </th>
                                <td class="text-end">
                                    {{ $production->quantity.'/'.$production->unit->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Wasted Quantity') : </th>
                                <td class="text-end">
                                    {{ $production->wasted_quantity.'/'.$production->unit->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Final Quantity') : </th>
                                <td class="text-end">
                                    {{ $production->total_final_quantity.'/'.$production->unit->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Additional Cost') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($production->production_cost) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Total Cost') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($production->total_cost) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-6 text-end">
                    <p><strong>@lang('Production Items')'s Costing And Pricing</strong></p>
                    <table class="table modal-table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th class="text-end">@lang('Tax') : </th>
                                <td class="text-end">
                                    {{ $production->tax ? $production->tax->tax_percent : 0 }}%
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Per Unit Cost Exc').Tax : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($production->unit_cost_exc_tax) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Per Unit Cost Inc').Tax : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($production->unit_cost_inc_tax) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('xMargin')(%) : </th>
                                <td class="text-end">
                                    {{ $production->x_margin }}%
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Selling Price Exc').Tax : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($production->price_exc_tax) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-md-6">
                    <h6>@lang('CHECKED BY') : </h6>
                </div>

                <div class="col-md-6 text-end">
                    <h6>@lang('APPROVED BY') : </h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($production->reference_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{$production->reference_no}}</p>
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
 <!-- production print templete end-->
