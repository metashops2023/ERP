@if ($sale->branch->pos_sale_invoice_layout->layout_design == 1)
    <div class="sale_print_template d-none">
        <style>
            @page {size:a4;margin-top: 0.8cm; /*margin-bottom: 35px;*/ margin-left: 4%;margin-right: 4%;}
        </style>
        <div class="details_area">
            @if ($sale->branch->pos_sale_invoice_layout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <p>{{ $sale->branch->pos_sale_invoice_layout->header_text }}</p>
                                <p>{{ $sale->branch->pos_sale_invoice_layout->sub_heading_1 }}</p>
                                <p>{{ $sale->branch->pos_sale_invoice_layout->sub_heading_2 }}</p>
                                <p>{{ $sale->branch->pos_sale_invoice_layout->sub_heading_3 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($sale->branch->pos_sale_invoice_layout->show_shop_logo == 1)
                                @if ($sale->branch->logo != 'default.png')
                                    <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:17px;color:black;font-weight: 550; letter-spacing:1px;">{{ $sale->branch->name }}</span>
                                @endif
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h5>{{ $sale->branch->pos_sale_invoice_layout->invoice_heading }}</h5>
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
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-end">
                                @if ($sale->branch)
                                    <p class="company_name" style="text-transform: uppercase;">
                                       <strong>{{ $sale->branch->name }}</strong>
                                    </p>

                                    <p class="company_address">
                                        {{ $sale->branch->pos_sale_invoice_layout->branch_city == 1 ? $sale->branch->city : '' }},
                                        {{ $sale->branch->pos_sale_invoice_layout->branch_state == 1 ? $sale->branch->state : '' }},
                                        {{ $sale->branch->pos_sale_invoice_layout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                        {{ $sale->branch->pos_sale_invoice_layout->branch_country == 1 ? $sale->branch->country : '' }}.
                                    </p>

                                    @if ($sale->branch->pos_sale_invoice_layout->branch_phone)
                                        <p><b>Phone</b> : {{ $sale->branch->phone }}</p>
                                    @endif

                                    @if ($sale->branch->pos_sale_invoice_layout->branch_email && $sale->branch->email)
                                        <p><b>Email</b> : {{ $sale->branch->email }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($sale->branch->pos_sale_invoice_layout->is_header_less == 1)
                @for ($i = 0; $i < $sale->branch->pos_sale_invoice_layout->gap_from_top; $i++)
                    <br/>
                @endfor
            @endif
            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Customer : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>
                            @if ($sale->branch->pos_sale_invoice_layout->customer_address)
                                <li><strong>Address : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                                </li>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->customer_tax_no)
                                <li>
                                    <strong>Tax Number : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                </li>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->customer_phone)
                                <li><strong>Phone : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        @if ($sale->branch->pos_sale_invoice_layout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5>{{ $sale->branch->pos_sale_invoice_layout->invoice_heading }}</h5>
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
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> Invoice No :</strong> {{ $sale->invoice_id }}</li>
                            <li><strong> Date : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}
                            </li>
                            <li><strong> Entered By : </strong> {{ $sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }} </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">SL</th>
                            <th class="text-start">Descrpiton</th>
                            <th class="text-start">Sold Qty</th>
                            @if ($sale->branch->pos_sale_invoice_layout->product_w_type || $sale->branch->pos_sale_invoice_layout->product_w_duration || $sale->branch->pos_sale_invoice_layout->product_w_discription)
                                <th class="text-start">Warranty</th>
                            @endif

                            <th class="text-start">Price</th>

                            @if ($sale->branch->pos_sale_invoice_layout->product_discount)
                                <th class="text-start">Discount</th>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->product_tax)
                                <th class="text-start">Tax</th>
                            @endif

                            <th class="text-start">SubTotal</th>
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
                                    {!! $sale->branch->pos_sale_invoice_layout->product_imei == 1 ? '<br><small class="text-muted">' . ($sale_product->description == 'null' ? '' : $sale_product->description) . '</small>' : '' !!}
                                </td>
                                <td class="text-start">{{ $sale_product->quantity }}({{ $sale_product->unit }})</td>

                                @if ($sale->branch->pos_sale_invoice_layout->product_w_type || $sale->branch->pos_sale_invoice_layout->product_w_duration || $sale->branch->pos_sale_invoice_layout->product_w_discription)
                                    <td class="text-start">
                                        @if ($sale_product->product->warranty)
                                            {{ $sale_product->product->warranty->duration . ' ' . $sale_product->product->warranty->duration_type }}
                                            {{ $sale_product->product->warranty->type == 1 ? 'Warrantiy' : 'Guaranty' }}
                                            {!! $sale->branch->pos_sale_invoice_layout->product_w_discription ? '<br><small class="text-muted">' . $sale_product->product->warranty->description . '</small>' : '' !!}
                                        @else
                                            <b>No</b>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-start">
                                    {{ App\Utils\Converter::format_in_bdt($sale_product->unit_price_inc_tax) }}
                                </td>

                                @if ($sale->branch->pos_sale_invoice_layout->product_discount)
                                    <td class="text-start">
                                        {{ App\Utils\Converter::format_in_bdt($sale_product->unit_discount_amount) }}
                                    </td>
                                @endif

                                @if ($sale->branch->pos_sale_invoice_layout->product_tax)
                                    <td class="text-start">
                                        {{ $sale_product->unit_tax_percent }}
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

            @if (count($sale->sale_products) > 6)
                <br/>
                <div class="row page_break">
                    <div class="col-md-12 text-end">
                        <h6><em>Continued To this next page....</em></h6>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    @if ($sale->branch->pos_sale_invoice_layout->show_total_in_word)
                        <p style="text-transform: uppercase;"><b>In Word : </b> <span id="inword"></span> ONLY.</p>
                    @endif

                    @if (
                        $sale->branch->pos_sale_invoice_layout->account_name ||
                        $sale->branch->pos_sale_invoice_layout->account_no ||
                        $sale->branch->pos_sale_invoice_layout->bank_name ||
                        $sale->branch->pos_sale_invoice_layout->bank_branch
                    )
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px; margin-top:13px;">
                            @if ($sale->branch->pos_sale_invoice_layout->account_name)
                                <p>Account Name : {{ $sale->branch->pos_sale_invoice_layout->account_name }}</p>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->account_no)
                                <p>Account No : {{ $sale->branch->pos_sale_invoice_layout->account_no }}</p>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->bank_name)
                                <p>Bank : {{ $sale->branch->pos_sale_invoice_layout->bank_name }}</p>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->bank_branch)
                                <p>Branch : {{ $sale->branch->pos_sale_invoice_layout->bank_branch }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end"><strong>Net Total Amount : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Order Discount : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-end">
                                    @if ($sale->order_discount_type == 1)
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} (Fixed)
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} ( {{ $sale->order_discount }}%)
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Order Tax : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}
                                    ({{ $sale->order_tax_percent }} %)
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Shipment charge : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Total Payable : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Total Paid : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Total Due : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="total_paid text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br/><br/>

            <div class="row">
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>Customer's Signature</b>  </p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>Checked By</b>  </p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>Approved By</b> </p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>Signature Of Authority</b></p>
                    </div>
                </div>
            </div><br/>

            <div class="row">
                <div class="col-md-12">
                    <div class="invoice_notice">
                        <p>{!! $sale->branch->pos_sale_invoice_layout->invoice_notice ? '<strong>Attention : </strong>' . $sale->branch->pos_sale_invoice_layout->invoice_notice : '' !!}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <span>{{ $sale->branch->pos_sale_invoice_layout->footer_text }}</span>
                    </div>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-center">
                        <small>Print Date : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                    </div>

                    @if (env('PRINT_SD_SALE') == true)
                        <div class="col-4 text-center">
                            <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                            <small class="d-block">Software By <b>MetaShops Pvt. Ltd.</b></small>
                        </div>
                    @endif

                    <div class="col-4 text-center">
                        <small>Print Time : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <style>@page{margin: 8px;}</style>
    <div class="sale_print_template d-none">
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
                                    <span><b>Phone :</b>  {{ $sale->branch->phone }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span><b>Email :</b> {{ $sale->branch->email }}</span>
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
                                    <b>Date:</b> <span>{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <b>INV NO: </b> <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <b>Customer:</b> <span>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="description_area pt-2 pb-1">
                    <table class="w-100">
                        <thead class="t-head">
                            <tr>
                                <th class="text-start"> Description</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <thead class="d-body">
                            @foreach ($sale->sale_products as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->variant ? ' '.$saleProduct->variant->variant_name : '';
                                    @endphp
                                    <th class="text-start">{{ $loop->index + 1 }}. {{ Str::limit($saleProduct->product->name, 25, '').$variant }} </th>

                                    <th class="text-center">{{ (float) $saleProduct->quantity }}</th>
                                    <th class="text-center">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</th>
                                    <th class="text-end">{{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}</th>
                                </tr>
                            @endforeach
                        </thead>
                    </table>
                </div>

                <div class="amount_area">
                    <table class="w-100 float-end">
                        <thead>
                            <tr>
                                <th class="text-end">Discount : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{  App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">Order Tax : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        ({{  App\Utils\Converter::format_in_bdt($sale->order_tax_percent) }} %)
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> Total Payable : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{  App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"><strong> Total Paid : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></th>
                                <th class="text-end">
                                    <span>
                                        {{  App\Utils\Converter::format_in_bdt($sale->paid) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"><strong> Change Amount : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></th>
                                <th class="text-end">
                                    <span>
                                        {{  App\Utils\Converter::format_in_bdt($sale->change_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"><strong> Total Due : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></th>
                                <th class="text-end">
                                    <span>
                                        {{  App\Utils\Converter::format_in_bdt($sale->due) }}
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
                                    <span>{{ $sale->branch->pos_sale_invoice_layout->invoice_notice ?  $sale->branch->pos_sale_invoice_layout->invoice_notice : '' }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <br>
                                    <span>{{ $sale->branch->pos_sale_invoice_layout->footer_text ?  $sale->branch->pos_sale_invoice_layout->footer_text : '' }}</span>
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
                                        <span>Software By <b>MetaShops Pvt. Ltd.</b> </span>
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
