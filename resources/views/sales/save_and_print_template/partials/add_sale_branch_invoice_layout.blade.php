@if ($sale->branch->add_sale_invoice_layout->layout_design == 1)
    <div class="sale_print_template">
        <style>
            @page {size:a4;margin-top: 0.8cm;/* margin-bottom: 35px;  */margin-left: 4%;margin-right: 4%;}
            div#footer {position:fixed;bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
        </style>
        <div class="details_area">
            @if ($sale->branch->add_sale_invoice_layout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <p>{{ $sale->branch->add_sale_invoice_layout->header_text }}</p>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_1 }}<p>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_2 }}<p>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_3 }}<p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            @if ($sale->branch->add_sale_invoice_layout->show_shop_logo == 1)
                                @if ($sale->branch->logo != 'default.png')
                                    <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ $sale->branch->name }}</span>
                                @endif
                            @endif
                        </div>

                        <div class="col-8">
                            <div class="heading text-end">
                                <p class="company_name" style="text-transform: uppercase;"><strong>{{ $sale->branch->name }}</strong></p>

                                <p class="company_address">
                                    {{ $sale->branch->add_sale_invoice_layout->branch_city == 1 ? $sale->branch->city : '' }},
                                    {{ $sale->branch->add_sale_invoice_layout->branch_state == 1 ? $sale->branch->state : '' }},
                                    {{ $sale->branch->add_sale_invoice_layout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                    {{ $sale->branch->add_sale_invoice_layout->branch_country == 1 ? $sale->branch->country : '' }}.
                                </p>

                                @if ($sale->branch->add_sale_invoice_layout->branch_phone)
                                    <p><strong>@lang('Phone') :</strong> {{ $sale->branch->phone }}</p>
                                @endif

                                @if ($sale->branch->add_sale_invoice_layout->branch_email)
                                    <p><strong>@lang('Email') :</strong> {{ $sale->branch->email }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="middle_header_text text-center">
                                <h5 style="text-transform: uppercase;">
                                    {{ $sale->status == 1 ? $sale->branch->add_sale_invoice_layout->invoice_heading : 'SALE ORDER' }}
                                </h5>

                                <h6>
                                    @php
                                        $payable = $sale->total_payable_amount - $sale->sale_return_amount;
                                    @endphp

                                    @if ($sale->due <= 0)
                                        Paid
                                    @elseif ($sale->due > 0 && $sale->due < $payable)
                                        Partial
                                    @elseif($payable==$sale->due)
                                        Due
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($sale->branch->add_sale_invoice_layout->is_header_less == 1)
                @for ($i = 0; $i < $sale->branch->add_sale_invoice_layout->gap_from_top; $i++)
                    <br/>
                @endfor
            @endif

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Customer') : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>
                            @if ($sale->branch->add_sale_invoice_layout->customer_address)
                                <li><strong>@lang('Address') : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                                </li>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->customer_tax_no)
                                <li><strong>@lang('Tax Number') : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                </li>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->customer_phone)
                                <li><strong>@lang('Phone') : </strong> >{{ $sale->customer ? $sale->customer->phone : '' }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-lg-4 text-center">
                        @if ($sale->branch->add_sale_invoice_layout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5 style="text-transform: uppercase;">{{ $sale->status == 1 ? $sale->branch->add_sale_invoice_layout->invoice_heading : 'SALE ORDER' }}</h5>
                                <h6>
                                    @php
                                        $payable = $sale->total_payable_amount - $sale->sale_return_amount;
                                    @endphp

                                    @if ($sale->due <= 0)
                                        PAID
                                    @elseif ($sale->due > 0 && $sale->due < $payable)
                                        PARTIAL
                                    @elseif($payable==$sale->due)
                                        DUE
                                    @endif
                                </h6>
                            </div>
                        @endif
                        <img style="width: 170px; height:40px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                    </div>

                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> {{ $sale->status == 1 ? 'Invoice' : 'Order' }} No : </strong> {{ $sale->invoice_id }}</li>
                            <li><strong> @lang('Date') : {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' .date($timeFormat ,strtotime($sale->time)) }}</strong></li>
                            <li><strong> @lang('Entered By') : </strong> {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }} </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table  modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">SL</th>
                            <th class="text-start">@lang('Description')</th>
                            <th class="text-start">@lang('Sold Qty')</th>
                            @if ($sale->branch->add_sale_invoice_layout->product_w_type || $sale->branch->add_sale_invoice_layout->product_w_duration || $sale->branch->add_sale_invoice_layout->product_w_discription)
                                <th class="text-start">@lang('Warranty')</th>
                            @endif
                            <th class="text-end">@lang('Price')</th>
                            @if ($sale->branch->add_sale_invoice_layout->product_discount)
                                <th class="text-end">@lang('Discount')</th>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->product_tax)
                                <th class="text-end">@lang('Tax')</th>
                            @endif

                            <th class="text-end">@lang('SubTotal')</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($customerCopySaleProducts as $sale_product)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>

                                <td class="text-start">
                                    {{ $sale_product->p_name }}

                                    @if ($sale_product->product_variant_id)
                                        -{{ $sale_product->variant_name }}
                                    @endif

                                    {!! $sale->branch->add_sale_invoice_layout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>

                                <td class="text-start">{{ $sale_product->quantity }} ({{ $sale_product->unit }}) </td>

                                @if (
                                    $sale->branch->add_sale_invoice_layout->product_w_type ||
                                    $sale->branch->add_sale_invoice_layout->product_w_duration ||
                                    $sale->branch->add_sale_invoice_layout->product_w_discription
                                )
                                    <td class="text-start">
                                        @if ($sale_product->warranty_id)

                                            {{ $sale_product->w_duration . ' ' . $sale_product->w_duration_type }}
                                            {{ $sale_product->w_type == 1 ? 'Warranty' : 'Guaranty' }}
                                            {!! $sale->branch->add_sale_invoice_layout->product_w_discription ? '<br><small class="text-muted">' . $sale_product->w_description . '</small>' : '' !!}
                                        @else

                                            <strong>No</strong>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($sale_product->unit_price_inc_tax) }}</td>

                                @if ($sale->branch->add_sale_invoice_layout->product_discount)

                                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($sale_product->unit_discount_amount) }}</td>
                                @endif

                                @if ($sale->branch->add_sale_invoice_layout->product_tax)

                                    <td class="text-end">{{ $sale_product->unit_tax_percent }}</td>
                                @endif

                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($sale_product->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (count($sale->sale_products) > 15)
                <br>
                <div class="row page_break">
                    <div class="col-md-12 text-end">
                        <h6><em>@lang('Continued To this next page')....</em></h6>
                    </div>
                </div>
                @if ($sale->branch->add_sale_invoice_layout->is_header_less == 1)
                    @for ($i = 0; $i < $sale->branch->add_sale_invoice_layout->gap_from_top; $i++)
                        <br/>
                    @endfor
                @endif
            @endif

            <div class="row">
                <div class="col-md-6">
                    @if ($sale->branch->add_sale_invoice_layout->show_total_in_word == 1)
                        <p style="text-transform: uppercase;"><strong>@lang('In Word') : </strong> <span id="inword"></span> @lang('ONLY').</p>
                    @endif

                    @if (
                        $sale->branch->add_sale_invoice_layout->account_name ||
                        $sale->branch->add_sale_invoice_layout->account_no ||
                        $sale->branch->add_sale_invoice_layout->bank_name ||
                        $sale->branch->add_sale_invoice_layout->bank_branch
                    )
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                            @if ($sale->branch->add_sale_invoice_layout->account_name)
                                <p>@lang('Account Name') : {{ $sale->branch->add_sale_invoice_layout->account_name }}</p>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->account_no)
                                <p>@lang('Account No') : {{ $sale->branch->add_sale_invoice_layout->account_no }}</p>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->bank_name)
                                <p>@lang('Bank') : {{ $sale->branch->add_sale_invoice_layout->bank_name }}</p>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->bank_branch)
                                <p>@lang('Branch') : {{ $sale->branch->add_sale_invoice_layout->bank_branch }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end">
                                    <strong>@lang('Net Total Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong>
                                </td>
                                <td class="net_total text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('Order Discount') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="order_discount text-end">
                                    <b>
                                        @if ($sale->order_discount_type == 1)
                                            {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} (Fixed)
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} ( {{ $sale->order_discount }}%)
                                        @endif
                                    </b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('Order Tax') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="order_tax text-end">
                                    {{ $sale->order_tax_amount }}({{ $sale->order_tax_percent }} %)
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('Shipment charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="shipment_charge text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                </td>
                            </tr>

                            @if ($previous_due != 0)
                                <tr>
                                    <td class="text-end"><strong> @lang('Previous Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                    <td class="total_payable text-end">
                                        {{ App\Utils\Converter::format_in_bdt($previous_due) }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-end"><strong> @lang('Total Payable') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="total_payable text-end">
                                    {{ App\Utils\Converter::format_in_bdt($total_payable_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('Total Paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="total_paid text-end">
                                    {{ App\Utils\Converter::format_in_bdt($paying_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('Change Amount') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="total_paid text-end">
                                    {{ App\Utils\Converter::format_in_bdt($change_amount > 0 ? $change_amount : 0) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('Total Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="total_due text-end">
                                    {{ App\Utils\Converter::format_in_bdt($total_due > 0 ? $total_due : 0) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>@lang('Customer')'s signature</strong>  </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>@lang('Checked By')</strong>  </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>@lang('Approved By')</strong> </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>@lang('Signature Of Authority')</strong></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="invoice_notice">
                        <p>{!! $sale->branch->add_sale_invoice_layout->invoice_notice ? "<strong>@lang('Attention') : </strong>" . $sale->branch->add_sale_invoice_layout->invoice_notice : '' !!}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <p>{{ $sale->branch->add_sale_invoice_layout->footer_text }}</p>
                    </div>
                </div>
            </div><br>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-center">
                        <small>@lang('Print Date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (env('PRINT_SD_SALE') == true)
                            <small class="d-block">@lang('Software By') <strong>@lang('MetaShops Pvt'). Ltd.</strong></small>
                        @endif
                    </div>

                    <div class="col-4 text-center">
                        <small>@lang('Print Time') : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else

    <div class="sale_print_template">
        <style>@page{margin: 8px;}</style>
        <div class="pos_print_template">
            <div class="row">
                <div class="company_info">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    @if ($sale->branch->logo != 'default.png')
                                        <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:black;font-weight: 600;">{{ $sale->branch->name }}</span>
                                    @endif
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span>{{$sale->branch->name . '/' . $sale->branch->branch_code }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span>{{ $sale->branch->city . ', ' . $sale->branch->state . ', ' . $sale->branch->zip_code . ', ' . $sale->branch->country }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span><b>@lang('Phone') :</b>  {{ $sale->branch->phone }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span><b>@lang('Email') :</b> {{ $sale->branch->email }}</span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="customer_info mt-2">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <strong>@lang('Date'):</strong> <span>{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . $sale->time }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <strong>@lang('INV NO'): </strong> <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <strong>@lang('Customer'):</strong> <span>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="description_area pt-2 pb-1">
                    <table class="w-100">
                        <thead class="t-head">
                            <tr>
                                <th class="text-start"> @lang('Description')</th>
                                <th class="text-center">@lang('Qty')</th>
                                <th class="text-center">@lang('Price')</th>
                                <th class="text-end">@lang('Total')</th>
                            </tr>
                        </thead>
                        <thead class="d-body">
                            @foreach ($customerCopySaleProducts as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->product_variant_id ? ' '.$saleProduct->variant_name : '';
                                    @endphp
                                    <th class="text-start">
                                        {{ $loop->index + 1 }}. {{ Str::limit($saleProduct->p_name, 25, '').$variant }}
                                    </th>

                                    <th class="text-center">{{ (float)$saleProduct->quantity }}</th>
                                    <th class="text-center">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</th>
                                    <th class="text-end">{{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}</th>
                                </tr>
                            @endforeach
                        </thead>
                    </table>
                </div>

                <div class="amount_area">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-end">@lang('Net Total') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">Discount :
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Order Tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        ({{ $sale->order_tax_percent }} %)
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Previous Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($previous_due) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> @lang('Payable') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($total_payable_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> @lang('Paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($paying_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-start"><strong> @lang('Change Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></th>
                                <th class="total_paid text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($change_amount > 0 ? $change_amount : 0) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> @lang('Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($total_due > 0 ? $total_due : 0) }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="footer_text_area mt-2">
                    <table class="w-100 ">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <span>{{ $sale->branch->add_sale_invoice_layout->invoice_notice ?  $sale->branch->add_sale_invoice_layout->invoice_notice : '' }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <br>
                                    <span>{{ $sale->branch->add_sale_invoice_layout->footer_text ?  $sale->branch->add_sale_invoice_layout->footer_text : '' }}</span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="footer_area mt-1">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            @if (env('PRINT_SD_SALE') == true)
                                <tr>
                                    <th class="text-center">
                                        <span>@lang('Software By') <strong>@lang('MetaShops Pvt'). Ltd.</strong> </span>
                                    </th>
                                </tr>
                            @endif
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
