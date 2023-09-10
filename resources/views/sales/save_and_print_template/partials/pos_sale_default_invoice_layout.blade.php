@php
    $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
@endphp
@if ($defaultLayout->layout_design == 1)
    <div class="sale_print_template">
        <style>
            @page {size:a4;margin-top: 0.8cm;/* margin-bottom: 35px;  */margin-left: 4%;margin-right: 4%;}
            div#footer {position:fixed;bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
        </style>
        <div class="details_area">
            @if ($defaultLayout->is_header_less == 0)
                <div id="header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <p>{{ $defaultLayout->header_text }}</p>
                                <p>{{ $defaultLayout->sub_heading_1 }}</p>
                                <p>{{ $defaultLayout->sub_heading_2 }}</p>
                                <p>{{ $defaultLayout->sub_heading_3 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            @if ($defaultLayout->show_shop_logo == 1)
                                @if ($sale->branch)
                                    @if ($sale->branch->logo != 'default.png')
                                        <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ $sale->branch->name }}</span>
                                    @endif
                                @else
                                    @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                        <img style="height: 40px; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                                    @endif
                                @endif
                            @endif
                        </div>

                        <div class="col-8">
                            <div class="heading text-end">
                                @if ($sale->branch)
                                    <p class="company_name" style="text-transform: uppercase;">
                                        <strong>{{ $sale->branch->name }}</strong>
                                    </p>

                                    <p class="company_address">
                                        {{ $defaultLayout->branch_city == 1 ? $sale->branch->city : '' }},
                                        {{ $defaultLayout->branch_state == 1 ? $sale->branch->state : '' }},
                                        {{ $defaultLayout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                        {{ $defaultLayout->branch_country == 1 ? $sale->branch->country : '' }}.
                                    </p>

                                    @if ($defaultLayout->branch_phone)
                                        <p><b>@lang('Phone') :</b>  {{ $sale->branch->phone }}</p>
                                    @endif

                                    @if ($defaultLayout->branch_email)
                                        <p><b>@lang('Email') :</b> {{ $sale->branch->email }}</p>
                                    @endif
                                @else
                                    <p class="company_name" style="text-transform: uppercase;">
                                        <strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong>
                                    </p>

                                    <p class="company_address">
                                        {{ json_decode($generalSettings->business, true)['address'] }}
                                    </p>

                                    @if ($defaultLayout->branch_phone)
                                        <p><strong>@lang('Phone') :</strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                                    @endif

                                    @if ($defaultLayout->branch_email && json_decode($generalSettings->business, true)['email'])
                                        <p><strong>@lang('Email') :</strong> {{ json_decode($generalSettings->business, true)['email'] }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="middle_header_text text-center">
                                <h5>{{ $defaultLayout->invoice_heading }}</h5>
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

            @if ($defaultLayout->is_header_less == 1)
                @for ($i = 0; $i < $defaultLayout->gap_from_top; $i++)
                    <br/>
                @endfor
            @endif

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Customer') : </strong>
                                {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>
                            @if ($defaultLayout->customer_address)
                                <li><strong>@lang('Address') : </strong>
                                    {{ $sale->customer ? $sale->customer->address : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_tax_no)
                                <li><strong>@lang('Tax Number') : </strong>
                                    {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_phone)
                                <li><strong>@lang('Phone') : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}</li>
                            @endif

                            @if (json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == 1)
                                <li><strong>{{ json_decode($generalSettings->reward_poing_settings, true)['point_display_name'] }} : </strong>
                                    {{ $sale->customer ? $sale->customer->point : 0 }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-lg-4 text-center">
                        @if ($defaultLayout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5>{{ $defaultLayout->invoice_heading }}</h5>
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
                        @endif
                        <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> @lang('Invoice No') : </strong> {{ $sale->invoice_id }}</li>
                            <li><strong> @lang('Date') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}</li>
                            <li><strong> @lang('Entered By') : </strong> {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">SL</th>
                            <th class="text-start">@lang('Descrpiton')</th>
                            <th class="text-start">@lang('Sold Qty')</th>
                            @if ($defaultLayout->product_w_type || $defaultLayout->product_w_duration || $defaultLayout->product_w_discription)
                                <th class="text-start">@lang('Warranty')</th>
                            @endif
                            <th class="text-start">@lang('Price')</th>
                            @if ($defaultLayout->product_discount)
                                <th class="text-start">@lang('Discount')</th>
                            @endif

                            @if ($defaultLayout->product_tax)
                                <th class="text-start">@lang('Tax')</th>
                            @endif
                            <th class="text-start">@lang('SubTotal')</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($sale->sale_products as $sale_product)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                <td class="text-start">
                                    {{ $sale_product->product->name }}
                                    @if ($sale_product->variant)
                                        -{{ $sale_product->variant->variant_name }}{!! $sale_product->ex_quantity != 0 ? '(<b>EX:</b>'.$sale_product->ex_quantity.')' : '' !!}
                                    @endif
                                    {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>
                                <td class="text-start">{{ $sale_product->quantity }}({{ $sale_product->unit }}) </td>

                                @if ($defaultLayout->product_w_type || $defaultLayout->product_w_duration || $defaultLayout->product_w_discription)
                                    <td class="text-start">
                                        @if ($sale_product->product->warranty)
                                            {{ $sale_product->product->warranty->duration . ' ' . $sale_product->product->warranty->duration_type }}
                                            {{ $sale_product->product->warranty->type == 1 ? 'Warranty' : 'Guaranty' }}
                                            {!! $defaultLayout->product_w_discription ? '<br><small class="text-muted">' . $sale_product->product->warranty->description . '</small>' : '' !!}
                                        @else
                                            <b>No</b>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($sale_product->unit_price_inc_tax) }} </td>

                                @if ($defaultLayout->product_discount)
                                    <td class="text-start">
                                        {{ App\Utils\Converter::format_in_bdt($sale_product->unit_discount_amount) }}
                                    </td>
                                @endif

                                @if ($defaultLayout->product_tax)
                                    <td class="text-start">
                                        {{ $sale_product->unit_tax_percent }}%
                                    </td>
                                @endif

                                <td class="text-start">
                                    {{ App\Utils\Converter::format_in_bdt($sale_product->subtotal) }}
                                </td>
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
                @if ($defaultLayout->is_header_less == 1)
                    @for ($i = 0; $i < $defaultLayout->gap_from_top; $i++)
                        <br/>
                    @endfor
                @endif
            @endif

            <div class="row">
                <div class="col-md-6">
                    @if ($defaultLayout->show_total_in_word == 1)
                        <p style="text-transform: uppercase;"><b>@lang('In Word') : </b> <span id="inword"></span> @lang('ONLY').</p>
                    @endif

                    @if (
                        $defaultLayout->account_name ||
                        $defaultLayout->account_no ||
                        $defaultLayout->bank_name ||
                        $defaultLayout->bank_branch
                      )
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                            @if ($defaultLayout->account_name)
                                <p>@lang('Account Name') : {{ $defaultLayout->account_name }}</p>
                            @endif

                            @if ($defaultLayout->account_no)
                                <p>@lang('Account No') : {{ $defaultLayout->account_no }}</p>
                            @endif

                            @if ($defaultLayout->bank_name)
                                <p>@lang('Bank') : {{ $defaultLayout->bank_name }}</p>
                            @endif

                            @if ($defaultLayout->bank_branch)
                                <p>@lang('Branch') : {{ $defaultLayout->bank_branch }}</p>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end"><strong>@lang('Net Total Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="net_total text-end">{{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end"><strong> @lang('Order Discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="order_discount text-end">
                                    @if ($sale->order_discount_type == 1)
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} (Fixed)
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} ( {{ $sale->order_discount }}%)
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('Order Tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="order_tax text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}({{ $sale->order_tax_percent }} %)
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('Shipment charge') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
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

                            @if ($change_amount > 0)
                                <tr>
                                    <td class="text-end"><strong> @lang('Change Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                    <td class="total_paid text-end">
                                        {{ App\Utils\Converter::format_in_bdt($change_amount > 0 ? $change_amount : 0) }}
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="text-end"><strong> @lang('Total Due') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
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
                        <p class="borderTop"><strong>@lang('Customer')'s signature</strong></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>@lang('Checked By')</strong></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>@lang('Approved By')</strong></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>@lang('Signature Of Authority')</strong></p>
                    </div>
                </div>
            </div><br>

            <div class="row">
                <div class="col-md-12">
                    <div class="invoice_notice">
                        <p>{!! $defaultLayout->invoice_notice ? '<strong>@lang('Attention') : </strong>' . $defaultLayout->invoice_notice : '' !!}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <p>{{ $defaultLayout->footer_text }}</p>
                    </div>
                </div>
            </div><br>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small>@lang('Print Date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (env('PRINT_SD_SALE') == true)
                            <small class="d-block">@lang('Software By') <strong>@lang('MetaShops Pvt'). Ltd.</strong></small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small>@lang('Print Time') : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Packing slip print templete-->
    <div class="sale_print_template">
        <style>@page{margin: 8px;}</style>
        <div class="pos_print_template">
            <div class="row">
                <div class="company_info">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    @if ($defaultLayout->show_shop_logo == 1)
                                        @if ($sale->branch)
                                            @if ($sale->branch->logo != 'default.png')
                                                <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                            @else
                                                <span style="font-family: 'Anton', sans-serif; font-size:15px;color:black; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">{{ $sale->branch->name }}</span>
                                            @endif
                                        @else
                                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                                <img style="height: 40px; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                                            @else
                                                <span style="font-family: 'Anton', sans-serif; font-size:15px;color:black; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                                            @endif
                                        @endif
                                    @endif
                                </th>
                            </tr>

                            @if ($sale->branch)
                                <tr>
                                    <th class="text-center">
                                        <h6>{{$sale->branch->name . '/' . $sale->branch->branch_code }}</h6>
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
                            @else
                                <tr>
                                    <th class="text-center">
                                        <span>{{ json_decode($generalSettings->business, true)['address'] }} </span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>@lang('Phone') :</b> {{ json_decode($generalSettings->business, true)['phone'] }} </span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>@lang('Email') :</b> {{ json_decode($generalSettings->business, true)['email'] }} </span>
                                    </th>
                                </tr>
                            @endif
                        </thead>
                    </table>
                </div>

                <div class="customer_info mt-2">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <b>@lang('Date'):</b> <span> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <b>@lang('INV NO'): </b> <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <b>@lang('Customer'):</b> <span>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</span>
                                </th>
                            </tr>

                            @if (json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == 1)
                                <tr>
                                    <th class="text-center">
                                        <b>{{ json_decode($generalSettings->reward_poing_settings, true)['point_display_name'] }} : </b>
                                        <span>{{ $sale->customer ? $sale->customer->point : 0 }}</span>
                                    </th>
                                </tr>
                            @endif
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
                            @foreach ($sale->sale_products as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->variant ? ' '.$saleProduct->variant->variant_name : '';
                                    @endphp
                                    <th class="text-start">
                                        {{ $loop->index + 1 }}. {{ Str::limit($saleProduct->product->name, 25, '').$variant }}{!! $saleProduct->ex_quantity != 0 ? '(<strong>EX:</strong>'.$saleProduct->ex_quantity.')' : '' !!}
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
                                <th class="text-end">@lang('Discount') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Order Tax') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        ({{ $sale->order_tax_percent }} %)
                                    </span>
                                </th>
                            </tr>

                            @if ($previous_due > 0)
                                <tr>
                                    <th class="text-end">@lang('Previous Due') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                    <th class="text-end">
                                        <span>
                                            {{ App\Utils\Converter::format_in_bdt($previous_due) }}
                                        </span>
                                    </th>
                                </tr>
                            @endif

                            <tr>
                                <th class="text-end">@lang('Total Payable') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
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

                            @if ($sale->ex_status == 0)
                                @if ($change_amount > 0)
                                    <tr>
                                        <th class="text-end"><strong> @lang('Change Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></th>
                                        <th class="total_paid text-end">
                                            <span>
                                                {{ App\Utils\Converter::format_in_bdt($change_amount) }}
                                            </span>
                                        </th>
                                    </tr>
                                @endif
                            @endif

                            <tr>
                                <th class="text-end"> @lang('Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($total_due) }}
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
                                    <span>{{ $defaultLayout->invoice_notice ?  $defaultLayout->invoice_notice : '' }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <br>
                                    <span>{{ $defaultLayout->footer_text ?  $defaultLayout->footer_text : '' }}</span>
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
                                        <span>@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b> </span>
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
