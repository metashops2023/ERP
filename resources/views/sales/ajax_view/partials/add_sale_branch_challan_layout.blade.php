<div class="challan_print_template d-none">
    <style>
        @page {size:a4;margin-top: 0.8cm; /*margin-bottom: 35px;*/ margin-left: 4%;margin-right: 4%;}
    </style>
    <div class="details_area">
        @if ($sale->branch->add_sale_invoice_layout->is_header_less == 0)
            <div class="heading_area">
                <div class="row">
                    <div class="col-md-12">
                        <div class="header_text text-center">
                            <h4>{{ $sale->branch->add_sale_invoice_layout->header_text }}</h4>
                            <p>
                                {{ $sale->branch->add_sale_invoice_layout->sub_heading_1 }}
                            </p>
                            <p>
                                {{ $sale->branch->add_sale_invoice_layout->sub_heading_2 }}
                            </p>
                            <p>
                                {{ $sale->branch->add_sale_invoice_layout->sub_heading_3 }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        @if ($sale->branch->add_sale_invoice_layout->show_shop_logo == 1)
                            @if ($sale->branch->logo != 'default.png')
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                            @else 
                                <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ $sale->branch->name }}</span>
                            @endif
                        @endif
                    </div>

                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="middle_header_text text-center">
                            <h1>{{ $sale->branch->add_sale_invoice_layout->challan_heading }}</h1>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="heading text-end">
                            <h5 class="company_name">
                                {{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                            <p class="company_address">
                                {{ $sale->branch->name . '/' . $sale->branch->branch_code }},
                                {{ $sale->branch->add_sale_invoice_layout->branch_city == 1 ? $sale->branch->city : '' }},
                                {{ $sale->branch->add_sale_invoice_layout->branch_state == 1 ? $sale->branch->state : '' }},
                                {{ $sale->branch->add_sale_invoice_layout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                {{ $sale->branch->add_sale_invoice_layout->branch_country == 1 ? $sale->branch->country : '' }}.
                            </p>

                            @if ($sale->branch->add_sale_invoice_layout->branch_phone)
                                <p><b>@lang('Phone') :</b>  {{ $sale->branch->phone }}</p>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->branch_email)
                                <p><b>@lang('Eamil') :</b> : {{ $sale->branch->email }}</p>
                            @endif
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
                            <li><strong>@lang('Phone') : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="col-lg-4 text-center">
                    @if ($sale->branch->add_sale_invoice_layout->is_header_less == 1)
                        <h5>{{ $sale->branch->add_sale_invoice_layout->challan_heading }}</h5>
                    @endif

                    {{-- <img style="width: 170px; height:45px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}"> --}}
                    <img style="width: 150px; height:150px; margin-top:3px;" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($sale->invoice_id)) !!} ">
                    
                    <p>{{ $sale->invoice_id }}</p>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong> @lang('Challan No') : </strong> {{ $sale->invoice_id }}</li>
                        <li><strong> @lang('Date') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}</li>
                        <li><strong> @lang('Entered By') : </strong> {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }} </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="text-start">SL</th>
                        <th class="text-start">@lang('Product')</th>
                        <th class="text-start">@lang('Unit')</th>
                        <th class="text-start">@lang('Quantity')</th>
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
                            <td class="text-start">{{ $sale_product->unit }}</td>
                            <td class="text-start">{{ $sale_product->quantity }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div><br>

        @if (count($sale->sale_products) > 11)
            <br>
            <div class="row page_break">
                <div class="col-md-12 text-end">
                    <h6><em>@lang('Continued To this next page')....</em></h6>
                </div>
            </div>
        @endif
       
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
        </div><br>

        <div class="row">
            <div class="col-md-12">
                <div class="footer_text text-center">
                    <span>{{ $sale->branch->add_sale_invoice_layout->footer_text }}</span>
                </div>
            </div>
        </div><br>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-center">
                    <small>@lang('Print Date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                </div>
                
                <div class="col-4 text-center">
                    <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                    @if (env('PRINT_SD_SALE') == true)
                        <small class="d-block">@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                    @endif
                </div>

                <div class="col-4 text-center">
                    <small>@lang('Print Time') : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>