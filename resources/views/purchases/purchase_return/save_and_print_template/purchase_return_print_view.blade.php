@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG();@endphp
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
</style>
<!-- purchase print templete-->
<div class="purchase_return_print_template">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-4">
                    @if ($return->branch)
                        @if ($return->branch->logo != 'default.png')
                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $return->branch->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $return->branch->name }}</span>
                        @endif
                    @else
                        @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                            <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-4">
                    <div class="heading text-center">
                        <h5 class="bill_name">@lang('Purchase Return Bill')</h5>
                    </div>
                </div>

                <div class="col-4">

                </div>
            </div>
        </div>

        <div class="purchase_return_and_deal_info pt-3">
            <div class="row">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li><strong>@lang('Return Details') : </strong> </li>

                        <li><strong>PR.Invoice ID : </strong>
                            <span class="return_invoice_id">{{ $return->invoice_id }}</span>
                        </li>

                        <li><strong>@lang('Return Date') : </strong>
                            <span class="return_date">{{ $return->date }}</span>
                        </li>

                        <li><strong>@lang('Supplier Name') : </strong>
                            {{ $return->supplier ? $return->supplier->name : $return->purchase->supplier->name }}
                        </li>

                        <li><strong>@lang('Return Stock Loction') : </strong>
                            @if ($return->warehouse)

                                {{ $return->warehouse->warehouse_name.'/'.$return->warehouse->warehouse_code }}<b>(WH)</b>
                            @elseif($return->branch)

                                {{ $return->branch->name.'/'.$return->branch->branch_code }} <b>(B.L)</b>
                            @else

                                {{ json_decode($generalSettings->business, true)['shop_name'] }}<b>(Head Office)</b>
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled float-right">
                        <li><strong>@lang('Purchase Details') : </strong> </li>
                        <li><strong>@lang('Invoice No') : </strong> {{ $return->purchase ? $return->purchase->invoice_id : 'N/A' }}</li>
                        <li><strong>@lang('Date') : </strong>{{ $return->purchase ? $return->purchase->date : 'N/A' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="purchase_product_table pt-3 pb-3">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <th class="text-start">SL</th>
                            <th class="text-start">@lang('Product')</th>
                            <th class="text-end">@lang('Unit Cost')</th>
                            <th class="text-end">@lang('Return Quantity')</th>
                            <th class="text-end">@lang('SubTotal')</th>
                        </tr>
                    </tr>
                </thead>
                <tbody class="purchase_return_print_product_list">
                    @foreach ($return->purchase_return_products as $purchase_return_product)
                        @if ($purchase_return_product->return_qty > 0)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>

                                <td class="text-start">
                                    {{ $purchase_return_product->product->name }}

                                    @if ($purchase_return_product->variant)

                                        -{{ $purchase_return_product->variant->variant_name }}
                                    @endif

                                    @if ($purchase_return_product->variant)

                                        ({{ $purchase_return_product->variant->variant_code }})
                                    @else

                                        ({{ $purchase_return_product->product->product_code }})
                                    @endif
                                </td>

                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase_return_product->unit_cost) }}
                                </td>

                                <td class="text-end">
                                    {{ $purchase_return_product->return_qty }} ({{ $purchase_return_product->unit }})
                                </td>

                                <td class="text-end">
                                    {{ $purchase_return_product->return_subtotal }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">@lang('Total Return Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                        <td colspan="2" class="text-end">{{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}</td>
                    </tr>

                    <tr>
                        <th colspan="4" class="text-end">@lang('Total Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>

                        <td colspan="2" class="text-end">

                            @if ($return->purchase_id)

                                {{ App\Utils\Converter::format_in_bdt($return->total_return_due) }}
                            @else
                                CHECK SUPPLIER DUE
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br><br>

        <div class="note">
            <div class="row">
                <div class="col-md-6">
                    <h6><strong>@lang('CHECKED BY')</strong></h6>
                </div>
                <div class="col-md-6 text-end">
                    <h6><strong>@lang('APPROVED BY')</strong></h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($return->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p>{{$return->invoice_id}}</p>
            </div>
        </div>

        @if (env('PRINT_SD_PURCHASE') == true)
            <div class="row">
                <div class="col-md-12 text-center">
                    <small>@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                </div>
            </div>
        @endif
    </div>
</div>
