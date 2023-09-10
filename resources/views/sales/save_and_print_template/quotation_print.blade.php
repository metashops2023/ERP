@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG();@endphp
<!-- Quotation print templete-->
@if ($sale->branch && $sale->branch->add_sale_invoice_layout)
    <div class="sale_print_template">
        <div class="details_area">
            @if ($sale->branch->add_sale_invoice_layout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <h4>{{ $sale->branch->add_sale_invoice_layout->header_text }}</h4>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_1 }}</p>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_2 }}</p>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_3 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($sale->branch->add_sale_invoice_layout->show_shop_logo == 1)
                                @if ($sale->branch->logo != 'default.png')
                                    <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;font-weight: 600;">{{ $sale->branch->name }}</span>
                                @endif
                            @endif
                        </div>

                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h1>{{ $sale->branch->add_sale_invoice_layout->quotation_heading }}</h1>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-end">
                                <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                                <h6 class="company_address">
                                    {{ $sale->branch->name . '/' . $sale->branch->branch_code }},
                                    {{ $sale->branch->add_sale_invoice_layout->branch_city == 1 ? $sale->branch->city : '' }},
                                    {{ $sale->branch->add_sale_invoice_layout->branch_state == 1 ? $sale->branch->state : '' }},
                                    {{ $sale->branch->add_sale_invoice_layout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                    {{ $sale->branch->add_sale_invoice_layout->branch_country == 1 ? $sale->branch->country : '' }}.
                                </h6>

                                @if ($sale->branch->add_sale_invoice_layout->branch_phone)
                                    <h6>@lang('Phone') : {{ $sale->branch->phone }}</h6>
                                @endif

                                @if ($sale->branch->add_sale_invoice_layout->branch_email)
                                    <h6>@lang('Phone') : {{ $sale->branch->email }}</h6>
                                @endif

                                <h6 class="bill_name">Entered By :
                                    {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

             @if ($sale->branch->add_sale_invoice_layout->is_header_less == 1)
                @for ($i = 0; $i < $sale->branch->add_sale_invoice_layout->gap_from_top; $i++)
                    </br>
                @endfor
            @endif

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li>
                                <strong>@lang('Customer') : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>
                            @if ($sale->branch->add_sale_invoice_layout->customer_address)
                                <li>
                                    <strong>@lang('Address') : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                                </li>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->customer_tax_no)
                                <li>
                                    <strong>@lang('Tax Number') : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                </li>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->customer_phone)
                                <li>
                                    <strong>@lang('Phone') : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        @if ($sale->branch->add_sale_invoice_layout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5>{{ $sale->branch->add_sale_invoice_layout->quotation_heading }}</h5>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> @lang('Quotation No') :</strong> {{ $sale->invoice_id }}</li>
                            <li><strong> @lang('Date') :</strong> {{ $sale->date }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" class="text-start">SL</th>
                            <th scope="col" class="text-start">@lang('Descrpiton')</th>
                            <th scope="col" class="text-start">@lang('Quantity')</th>
                            <th scope="col" class="text-start">@lang('Unit Price')</th>
                            @if ($sale->branch->add_sale_invoice_layout->product_discount)
                                <th scope="col" class="text-start">@lang('Discount')</th>
                            @endif
                            <th scope="col" class="text-start">@lang('SubTotal')</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($sale->sale_products as $sale_product)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                <td class="text-start">
                                    {{ $sale_product->product->name }}
                                    @if ($sale_product->variant)
                                        -{{ $sale_product->variant->variant_name }}
                                    @endif
                                    @if ($sale_product->variant)
                                        ({{ $sale_product->variant->variant_code }})
                                    @else
                                        ({{ $sale_product->product->product_code }})
                                    @endif
                                    {!! $sale->branch->add_sale_invoice_layout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>
                                <td class="text-start">{{ $sale_product->quantity }} ({{ $sale_product->unit }}) </td>

                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale_product->unit_price_inc_tax }} </td>

                                @if ($sale->branch->add_sale_invoice_layout->product_discount)
                                    <td class="text-start">
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ $sale_product->unit_discount_amount }}
                                    </td>
                                @endif

                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale_product->subtotal }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><strong>@lang('Net Total Amount') :</strong></td>
                                <td class="net_total text-start">
                                    <b></b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale->net_total_amount }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td><strong> @lang('Order Discount') : </strong></td>
                                <td class="order_discount text-start">
                                <b> @if ($sale->order_discount_type == 1)
                                        {{ $sale->order_discount_amount }} (Fixed)
                                    @else
                                        {{ $sale->order_discount_amount }} ( {{ $sale->order_discount }}%)
                                    @endif</b>
                                </td>
                            </tr>

                            <tr>
                                <td><strong> @lang('Order Tax') : </strong></td>
                                <td class="order_tax text-start">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale->order_tax_amount }}
                                    ({{ $sale->order_tax_percent }} %)</b>
                                </td>
                            </tr>

                            <tr>
                                <td><strong> @lang('Shipment charge') : </strong></td>
                                <td class="shipment_charge text-start">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ number_format($sale->shipment_charge, 2) }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td><strong> @lang('Total Payable') : </strong></td>
                                <td class="total_payable text-start">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ number_format($sale->total_payable_amount, 2) }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>@lang('Recevier')'s signature </h6>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="details_area text-right">
                        <h6> @lang('Signature Of Authority') </h6>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <span>{{ $sale->branch->add_sale_invoice_layout->footer_text }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{$sale->invoice_id}}</p>
                </div>
            </div>

            @if (env('PRINT_SD_SALE') == true)
                <div class="row">
                    <div class="col-md-12 text-center">
                        <small>@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                    </div>
                </div>
            @endif
        </div>
    </div>
@else
    @php
        $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
    @endphp
    <div class="sale_print_template">
        <div class="details_area">
            @if ($defaultLayout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <h4>{{ $defaultLayout->header_text }}</h4>
                                <p>{{ $defaultLayout->sub_heading_1 }}</p>
                                <p>{{ $defaultLayout->sub_heading_2 }}</p>
                                <p>{{ $defaultLayout->sub_heading_3 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($defaultLayout->show_shop_logo == 1)
                                @if ($sale->branch)
                                    @if ($sale->branch->logo != 'default.png')
                                        <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;font-weight: 600;">{{ $sale->branch->name }}</span>
                                    @endif
                                @else
                                    @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                        <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;font-weight: 600;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                                    @endif
                                @endif
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h1>{{ $defaultLayout->quotation_heading }}</h1>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-end">
                                @if ($sale->branch)
                                    <h5 class="company_name">
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                                    <h6 class="company_address">
                                        {{ $sale->branch->name . '/' . $sale->branch->branch_code }},
                                        {{ $defaultLayout->branch_city == 1 ? $sale->branch->city : '' }},
                                        {{ $defaultLayout->branch_state == 1 ? $sale->branch->state : '' }},
                                        {{ $defaultLayout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                        {{ $defaultLayout->branch_country == 1 ? $sale->branch->country : '' }}.
                                    </h6>

                                    @if ($defaultLayout->branch_phone)
                                        <h6>@lang('Phone') : {{ $sale->branch->phone }}</h6>
                                    @endif

                                    @if ($defaultLayout->branch_email)
                                        <h6>@lang('Email') : {{ $sale->branch->email }}</h6>
                                    @endif
                                @else
                                    <h4 class="company_name">
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                    </h4>

                                    <h6 class="company_address">
                                        {{ json_decode($generalSettings->business, true)['address'] }}
                                    </h6>

                                    @if ($defaultLayout->branch_phone)
                                        <h6>@lang('Phone') : {{ json_decode($generalSettings->business, true)['phone'] }}</h6>
                                    @endif

                                    @if ($defaultLayout->branch_email)
                                        <h6>@lang('Email') : {{ json_decode($generalSettings->business, true)['email'] }}</h6>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($defaultLayout->is_header_less == 1)
                @for ($i = 0; $i < $defaultLayout->gap_from_top; $i++)
                    </br>
                @endfor
            @endif

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li>
                                <strong>@lang('Customer') : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>
                            @if ($defaultLayout->customer_address)
                                <li>
                                    <strong>@lang('Address') : </strong>{{ $sale->customer ? $sale->customer->address : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_tax_no)
                                <li>
                                    <strong>@lang('Tax Number') : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_phone)
                                <li>
                                    <strong>@lang('Phone') : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        @if ($defaultLayout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5>{{ $defaultLayout->quotation_heading }}</h5>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> @lang('Quotation No') :</strong> {{ $sale->invoice_id }}</li>
                            <li><strong> @lang('Date') : </strong> {{ $sale->date }}</li>
                            <li><strong> @lang('Entered By') : </strong> {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }}</li>
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
                            <th class="text-start">@lang('Descrpiton')</th>
                            <th class="text-start">@lang('Quantity')</th>
                            <th class="text-start">@lang('Unit Price')</th>
                            <th class="text-start">@lang('Discount')</th>
                            <th class="text-start">@lang('SubTotal')</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($sale->sale_products as $sale_product)
                            <tr>
                                <td class="text-start"> {{ $loop->index + 1 }} </td>
                                <td class="text-start">
                                    {{ $sale_product->product->name }}
                                    @if ($sale_product->variant)
                                        -{{ $sale_product->variant->variant_name }}
                                    @endif
                                    @if ($sale_product->variant)
                                        ({{ $sale_product->variant->variant_code }})
                                    @else
                                        ({{ $sale_product->product->product_code }})
                                    @endif
                                    {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>
                                <td class="text-start">{{ $sale_product->quantity }} ({{ $sale_product->unit }}) </td>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale_product->unit_price_inc_tax }} </td>
                                @if ($defaultLayout->product_discount)
                                    <td class="text-start">
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ $sale_product->unit_discount_amount }}
                                    </td>
                                @endif

                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale_product->subtotal }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    <table class="table modal-table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <td class="text-start"><strong>@lang('Net Total Amount') :</strong></td>
                                <td class="text-end">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }} {{ $sale->net_total_amount }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Order Discount') : </strong></td>
                                <td class="order_discount text-end">
                                <b>@if ($sale->order_discount_type == 1)
                                        {{ $sale->order_discount_amount }} (Fixed)
                                    @else
                                        {{ $sale->order_discount_amount }} ( {{ $sale->order_discount }}%)
                                    @endif</b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Order Tax') : </strong></td>
                                <td class="order_tax text-end">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale->order_tax_amount }}
                                    ({{ $sale->order_tax_percent }} %)</b></td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Shipment charge') : </strong></td>
                                <td class="shipment_charge text-end">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ number_format($sale->shipment_charge, 2) }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Total Payable') : </strong></td>
                                <td class="total_payable text-end">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ number_format($sale->total_payable_amount, 2) }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>@lang('Recevier')'s signature </h6>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="details_area text-end">
                        <h6> @lang('Signature Of Authority') </h6>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <span>{{ $defaultLayout->footer_text }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{$sale->invoice_id}}</p>
                </div>
            </div>

            @if (env('PRINT_SD_SALE') == true)
                <div class="row">
                    <div class="col-md-12 text-center">
                        <small>@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
<!-- Sale print templete end-->
