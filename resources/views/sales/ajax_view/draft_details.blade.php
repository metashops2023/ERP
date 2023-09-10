  <!-- Details Modal -->
  <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-xl">
      <div class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
                @lang('Draft Details (Draft ID') : <strong>{{ $draft->invoice_id }}</strong>
          </h5>
          <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
            class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('Customer') :- </strong></li>
                        <li>
                            <strong>@lang('Namne') : </strong>{{ $draft->customer ? $draft->customer->name : 'Walk-In-Customer' }}
                        </li>
                        <li>
                            <strong>@lang('Address') : </strong>{{ $draft->customer ? $draft->customer->address : '' }}
                        </li>
                        <li>
                            <strong>@lang('Tax Number') : </strong> {{ $draft->customer ? $draft->customer->tax_number : '' }}
                        </li>
                        <li>
                            <strong>@lang('Phone') : </strong> {{ $draft->customer ? $draft->customer->phone : '' }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-4 text-left">
                    <ul class="list-unstyled">
                        <li><strong>@lang('Entered From') : </strong></li>
                        @if ($draft->branch)
                            <li><strong>@lang('Business Name') : </strong> <span>{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                            </li>
                            <li><strong>@lang('Address') : </strong> <span>{{ $draft->branch->name }}/{{ $draft->branch->branch_code }},
                                    {{ $draft->branch->city }}, {{ $draft->branch->state }},
                                    {{ $draft->branch->zip_code }}, {{ $draft->branch->country }}</span></li>
                            <li><strong>@lang('Phone') : </strong> <span>{{ $draft->branch->phone }}</span></li>
                        @else
                            <li><strong>@lang('Business Name') : </strong> <span>{{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b></span>
                            </li>
                            <li><strong>@lang('Address') : </strong> <span>{{ json_decode($generalSettings->business, true)['address'] }}</span></li>
                            <li><strong>@lang('Phone') : </strong> <span>{{ json_decode($generalSettings->business, true)['phone'] }}</span></li>
                            <li><strong>@lang('Stock Location') : </strong>
                                <span>
                                    {{ $draft->warehouse->warehouse_name.'/'.$draft->warehouse->warehouse_code }},
                                    {{ $draft->warehouse->address }}
                                </span>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="col-md-4 text-left">
                    <ul class="list-unstyled">
                        <li>
                            <strong>@lang('Date') : </strong> {{ $draft->date . ' ' . $draft->time }}
                        </li>
                        <li>
                            <strong>@lang('draft ID') : </strong> {{ $draft->invoice_id }}
                        </li>
                    <li><strong>@lang('Status') : </strong>
                        <span class="sale_status">
                            <span class="badge bg-info">@lang('draft')</span>
                        </span>
                    </li>
                    <li><strong>@lang('Shipment Status') : </strong>
                        <span class="shipment_status">
                            @if ($draft->shipment_status == null)
                                <span class="badge bg-danger">@lang('Not-Available')</span>
                            @elseif($draft->shipment_status == 1)
                                <span class="badge bg-warning">@lang('Ordered')</span>
                            @elseif($draft->shipment_status == 2)
                                <span class="badge bg-secondary">@lang('Packed')</span>
                            @elseif($draft->shipment_status == 3)
                                <span class="badge bg-primary">@lang('Shipped')</span>
                            @elseif($draft->shipment_status == 4)
                                <span class="badge bg-success">@lang('Delivered')</span>
                            @elseif($draft->shipment_status == 5)
                                <span class="badge bg-info">@lang('Cancelled')</span>
                            @endif
                        </span>
                    </li>
                    <li>
                        <strong>@lang('Created By') : </strong>
                        @php
                            $admin_role = '';
                            $prefix = '';
                            $name = $lastName = '';
                            if ($draft->admin) {
                                if ($draft->admin->role_type == 1) {
                                    $admin_role = ' (Super-Admin)';
                                } elseif ($draft->admin->role_type == 2) {
                                    $admin_role = ' (Admin)';
                                } elseif ($draft->admin->role_type == 3) {
                                    $admin_role = '(' . $draft->admin->role->name . ')';
                                }

                                $prefix = $draft->admin ? $draft->admin->prefix : '';
                                $name = $draft->admin ? $draft->admin->name : '';
                                $lastName = $draft->admin ? $draft->admin->last_name : '';
                            }
                        @endphp
                        {{ $admin_role ? $prefix . ' ' . $name . ' ' . $lastName . $admin_role : 'N/A' }}
                    </li>
                    </ul>
                </div>

            </div><br><br>
          <div class="row">
                <div class="table-responsive">
                    <table id="" class="table modal-table table-sm">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="text-start">@lang('Product')</th>
                                <th class="text-start">@lang('Quantity')</th>
                                <th class="text-start">@lang('Unit Price Exc').Tax</th>
                                <th class="text-start">@lang('Unit Discount')</th>
                                <th class="text-start">@lang('Unit Tax')</th>
                                <th class="text-start">@lang('Unit Price Inc').Tax</th>
                                <th class="text-start">@lang('SubTotal')</th>
                            </tr>
                        </thead>
                        <tbody class="draft_product_list">
                            @foreach ($draft->sale_products as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->variant ? ' -' . $saleProduct->variant->variant_name : '';
                                    @endphp
                                    <td class="text-start">{{ $saleProduct->product->name . $variant }}</td>
                                    <td class="text-start">{{ $saleProduct->quantity }}</td>
                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] . $saleProduct->unit_price_exc_tax }}
                                    </td>
                                    @php
                                        $DiscountType = $saleProduct->unit_discount_type == 1 ? ' (Fixed)' : ' (' . $saleProduct->unit_discount . '%)';
                                    @endphp
                                    <td class="text-start">
                                        {{ json_decode($generalSettings->business, true)['currency'] . $saleProduct->unit_discount_amount . $DiscountType }}
                                    </td>
                                    <td class="text-start">
                                        {{ json_decode($generalSettings->business, true)['currency'] . $saleProduct->unit_tax_amount . ' (' . $saleProduct->unit_tax_percent . '%)' }}
                                    </td>
                                    <td class="text-start">
                                        {{ json_decode($generalSettings->business, true)['currency'] . $saleProduct->unit_price_inc_tax }}
                                    </td>
                                    <td class="text-start">
                                        {{ json_decode($generalSettings->business, true)['currency'] . $saleProduct->subtotal }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
          </div>

          <div class="row">
              <div class="col-md-6 offset-md-6">
                  <div class="table-responsive">
                    <table class="table modal-table table-sm">
                        <tr>
                            <th class="text-start">@lang('Net Total Amount')</th>
                            <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                    {{ $draft->net_total_amount }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Order Discount')</th>
                            <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                @php
                                    $discount_type = $draft->order_discount_type == 1 ? ' (Fixed)' : '%';
                                @endphp
                                {{ $draft->order_discount_amount . $discount_type }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Order Tax')</th>
                            <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                    {{ $draft->order_tax_amount . ' (' . $draft->order_tax_percent . '%)' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Shipment Charge')</th>
                            <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                              {{ $draft->shipment_charge }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Grand Total')</th>
                            <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                <span class="total_payable_amount">
                                    {{ $draft->total_payable_amount }}
                                </span>
                            </td>
                        </tr>
                      </table>
                  </div>
              </div>
          </div>
          <hr class="p-0 m-0">
          <div class="row">
            <div class="col-md-6">
                <div class="details_area">
                    <h6>@lang('Shipping Details') : </h6>
                    <p class="shipping_details">
                        {{ $draft->shipment_details ? $draft->shipment_details : 'N/A' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="details_area">
                    <h6>@lang('Note') : </h6>
                    <p class="sale_note">{{ $draft->sale_note ? $draft->sale_note : 'N/A' }}</p>
                </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">@lang('Close')</button>
          <button type="submit" id="print_payment" class="c-btn button-success print_btn">@lang('Print')</button>
        </div>
      </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- draft print templete-->
@if ($draft->branch && $draft->branch->add_sale_invoice_layout)
    <div class="draft_print_template d-none">
        <div class="details_area">
            @if ($draft->branch->add_sale_invoice_layout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <h4>{{ $draft->branch->add_sale_invoice_layout->header_text }}</h4>
                                <p>{{ $draft->branch->add_sale_invoice_layout->sub_heading_1 }}</p>
                                <p>{{ $draft->branch->add_sale_invoice_layout->sub_heading_2 }}</p>
                                <p>{{ $draft->branch->add_sale_invoice_layout->sub_heading_3 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($draft->branch->add_sale_invoice_layout->show_shop_logo == 1)
                                @if ($draft->branch)
                                    <img style="height: 75px; width:200px;" src="{{ asset('uploads/branch_logo/' . $draft->branch->logo) }}">
                                @else
                                    <img style="height: 75px; width:200px;" src="{{asset('uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
                                @endif
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h1>{{ $draft->branch->add_sale_invoice_layout->draft_heading }}</h1>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-right">
                                @if ($draft->branch)
                                    <h5 class="company_name">
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                                    <h6 class="company_address">
                                        {{ $draft->branch->name . '/' . $draft->branch->branch_code }} <br>
                                        {{ $draft->branch->add_sale_invoice_layout->branch_city == 1 ? $draft->branch->city : '' }},
                                        {{ $draft->branch->add_sale_invoice_layout->branch_state == 1 ? $draft->branch->state : '' }},
                                        {{ $draft->branch->add_sale_invoice_layout->branch_zipcode == 1 ? $draft->branch->zip_code : '' }},
                                        {{ $draft->branch->add_sale_invoice_layout->branch_country == 1 ? $draft->branch->country : '' }}.
                                    </h6>

                                    @if ($draft->branch->add_sale_invoice_layout->branch_phone)
                                        <h6><b>@lang('Phone')</b> : {{ $draft->branch->phone }}</h6>
                                    @endif

                                    @if ($draft->branch->add_sale_invoice_layout->branch_email)
                                        <h6><b>@lang('Email')</b> : {{ $draft->branch->email }}</h6>
                                    @endif
                                @else
                                    <h5 class="company_name">
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                                    <h6 class="company_address">
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }},<br>
                                    </h6>

                                    @if ($draft->branch->add_sale_invoice_layout->branch_phone)
                                        <h6>@lang('Phone') : {{ json_decode($generalSettings->business, true)['phone'] }}</h6>
                                    @endif

                                    @if ($draft->branch->add_sale_invoice_layout->branch_email)
                                        <h6>@lang('Email') : {{ json_decode($generalSettings->business, true)['email'] }}</h6>
                                    @endif
                                @endif
                                <h6 class="bill_name">Entered By :
                                    {{ $draft->admin ? $draft->admin->prefix . ' ' . $draft->admin->name . ' ' . $draft->admin->last_name : 'N/A' }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($draft->branch->add_sale_invoice_layout->is_header_less == 1)
                @for ($i = 0; $i < $draft->branch->add_sale_invoice_layout->gap_from_top; $i++)
                    </br>
                @endfor
            @endif

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Customer') : </strong> {{ $draft->customer ? $draft->customer->name : 'Walk-In-Customer' }}</li>
                            @if ($draft->branch->add_sale_invoice_layout->customer_address)
                                <li><strong>@lang('Address') : </strong> {{ $draft->customer ? $draft->customer->address : '' }}</li>
                            @endif

                            @if ($draft->branch->add_sale_invoice_layout->customer_tax_no)
                                <li><strong>@lang('Tax Number') : </strong> {{ $draft->customer ? $draft->customer->tax_number : '' }}</li>
                            @endif

                            @if ($draft->branch->add_sale_invoice_layout->customer_phone)
                                <li><strong>@lang('Phone') : </strong> {{ $draft->customer ? $draft->customer->phone : '' }}</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">

                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> @lang('Invoice No') : </strong> {{ $draft->invoice_id }}</li>
                            <li><strong> @lang('Date') : </strong> <{{ $draft->date . ' ' . $draft->time }}</li>
                            <li><strong> @lang('Entered By') : </strong> {{ $draft->admin ? $draft->admin->prefix . ' ' . $draft->admin->name . ' ' . $draft->admin->last_name : 'N/A' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">@lang('Descrpiton')</th>
                            <th class="text-start">@lang('Quantity')</th>

                            <th class="text-start">@lang('Unit Price')</th>

                            @if ($draft->branch->add_sale_invoice_layout->product_discount)
                                <th class="text-start">@lang('Discount')</th>
                            @endif

                            @if ($draft->branch->add_sale_invoice_layout->product_tax)
                                <th class="text-start">@lang('Tax')</th>
                            @endif

                            <th class="text-start">@lang('SubTotal')</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($draft->sale_products as $sale_product)
                            <tr>
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
                                </td>
                                <td class="text-start">{{ $sale_product->quantity }} ({{ $sale_product->unit }}) </td>

                                <td class="text-start">
                                    {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                    {{ $sale_product->unit_price_inc_tax }}
                                </td>

                                @if ($draft->branch->add_sale_invoice_layout->product_discount)
                                    <td class="text-start">
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ $sale_product->unit_discount_amount }}
                                    </td>
                                @endif

                                @if ($draft->branch->add_sale_invoice_layout->product_tax)
                                    <td class="text-start">
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ $sale_product->unit_tax_percent }}
                                    </td>
                                @endif

                                <td class="text-start">
                                    {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                    {{ $sale_product->subtotal }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (count($draft->sale_products) > 9)
                <br>
                <div class="row page_break">
                    <div class="col-md-12 text-right">
                        <h6><em>@lang('Continued To this next page')....</em></h6>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    @if ($draft->branch->add_sale_invoice_layout->show_total_in_word)
                        <p><b>@lang('In Word') : <span id="inword"></span></b></p>
                    @endif
                    <br>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-start"><strong>@lang('Net Total Amount') :</strong></td>
                                <td class="text-end">
                                    <b>
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ $draft->net_total_amount }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Order Discount') : </strong></td>
                                <td class="text-end">
                                    <b>
                                        @if ($draft->order_discount_type == 1)
                                            {{ $draft->order_discount_amount }} (Fixed)
                                        @else
                                            {{ $draft->order_discount_amount }} ( {{ $draft->order_discount }}%)
                                        @endif
                                    <b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Order Tax') : </strong></td>
                                <td class="text-end">
                                    <b>
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ $draft->order_tax_amount }}
                                        ({{ $draft->order_tax_percent }} %)
                                    </b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Shipment charge') : </strong></td>
                                <td class="text-end">
                                    <b>
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ number_format($draft->shipment_charge, 2) }}
                                    </b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Total Payable') : </strong></td>
                                <td class="text-end">
                                    <b>
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ number_format($draft->total_payable_amount, 2) }}
                                    </b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br><br>

            <div class="row">
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('Customer')'s signature</b>  </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('Checked By')</b>  </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('Approved By')</b> </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('Signature Of Authority')</b></p>
                    </div>
                </div>
            </div><br/>

            {{-- <div class="row">
                <div class="barcode text-center">
                    <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($sale->invoice_id, $generatorPNG::TYPE_CODE_128)) }}">
                </div>
            </div><br>--}}

            <div class="row">
                <div class="col-md-12">
                    <div class="invoice_notice">
                        <p>{!! $draft->branch->add_sale_invoice_layout->invoice_notice ? '<b>Attention : <b>' . $draft->branch->add_sale_invoice_layout->invoice_notice : '' !!}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <p>{{ $draft->branch->add_sale_invoice_layout->footer_text }}</p>
                    </div>
                </div>
            </div><br>

            <div id="footer">

                <div class="row">
                    <div class="col-md-12">
                        <div class="heading text-center">
                            <h4><b>@lang('Sister Concern')</b></h4><br>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:50px;" src="{{ asset('uploads/layout_concern_logo/Nomhost logo.png') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:50px;" src="{{ asset('uploads/layout_concern_logo/Creative Studio.png') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:50px;" src="{{ asset('uploads/layout_concern_logo/Speeddigitposprologo.png') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:50px;" src="{{ asset('uploads/layout_concern_logo/UltimateERPLogo.png') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 text-center">
                        <small>@lang('Print Date') : {{ date('d<m/Y') }}</h6>
                    </div>
                    <div class="col-md-6 text-center">
                        <small>@lang('Print Time') : {{ date('h:i:s') }}</h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <small>@lang('Powered By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    @php
        $defaultLayout = DB::table('invoice_layouts')
            ->where('is_default', 1)
            ->first();
    @endphp
    <div class="draft_print_template d-none">
        <div class="details_area">
            @if ($defaultLayout->is_header_less == 0)
                <div id="header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <p>{{ $defaultLayout->header_text }}</p>
                                <p>{{ $defaultLayout->sub_heading_1 }}<p />
                                <p>{{ $defaultLayout->sub_heading_2 }}<p />
                                <p>{{ $defaultLayout->sub_heading_3 }}<p />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($defaultLayout->show_shop_logo == 1)
                                @if ($draft->branch)
                                    <img style="height: 75px; width:200px;" src="{{ asset('uploads/branch_logo/' . $draft->branch->logo) }}">
                                @else
                                    <img style="height: 75px; width:200px;" src="{{ asset('uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
                                @endif
                            @endif
                        </div>

                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h1>{{ $defaultLayout->draft_heading }}</h1>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-end">
                                @if ($draft->branch)
                                    <h3 class="company_name">
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}</h3>
                                    <h6 class="company_address">
                                        {{ $draft->branch->name . '/' . $draft->branch->branch_code }},
                                        {{ $defaultLayout->branch_city == 1 ? $draft->branch->city : '' }},
                                        {{ $defaultLayout->branch_state == 1 ? $draft->branch->state : '' }},
                                        {{ $defaultLayout->branch_zipcode == 1 ? $draft->branch->zip_code : '' }},
                                        {{ $defaultLayout->branch_country == 1 ? $draft->branch->country : '' }}.
                                    </h6>

                                    @if ($defaultLayout->branch_phone)
                                        <h6><b>@lang('Phone')</b> : {{ $draft->branch->phone }}</h6>
                                    @endif

                                    @if ($defaultLayout->branch_email)
                                        <h6><b>@lang('Email')</b> : {{ $draft->branch->email }}</h6>
                                    @endif
                                @else
                                    <h3 class="company_name">
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}</h3>
                                    <h6 class="company_address">
                                        {{ json_decode($generalSettings->business, true)['address'] }}
                                    </h6>

                                    @if ($defaultLayout->branch_phone)
                                        <h6><b>@lang('Phone')</b> : {{ json_decode($generalSettings->business, true)['phone'] }}</h6>
                                    @endif

                                    @if ($defaultLayout->branch_email)
                                        <h6><b>@lang('Email')</b> : {{ json_decode($generalSettings->business, true)['email'] }}</h6>
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
                                <strong>@lang('Customer') : </strong> {{ $draft->customer ? $draft->customer->name : 'Walk-In-Customer' }}
                            </li>
                            @if ($defaultLayout->customer_address)
                                <li>
                                    <strong>@lang('Address') : </strong>{{ $draft->customer ? $draft->customer->address : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_tax_no)
                                <li>
                                    <strong>@lang('Tax Number') : </strong> {{ $draft->customer ? $draft->customer->tax_number : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_phone)
                                <li>
                                    <strong>@lang('Phone') : </strong>{{ $draft->customer ? $draft->customer->phone : '' }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-lg-4">

                    </div>

                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> @lang('draft ID') : </strong> {{ $draft->invoice_id }}</li>
                            <li><strong> @lang('Date') : </strong> {{ $draft->date . ' ' . $draft->time }}</li>
                            <li><strong> @lang('Entered By') : </strong> {{$draft->admin ? $draft->admin->prefix . ' ' . $draft->admin->name . ' ' . $draft->admin->last_name : 'N/A' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">@lang('Descrpiton')</th>
                            <th class="text-start">@lang('Sold Qty')</th>
                            <th class="text-start">@lang('Unit Price')</th>

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
                        @foreach ($draft->sale_products as $sale_product)
                            <tr>
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
                                </td>
                                <td class="text-start">{{ $sale_product->quantity }} ({{ $sale_product->unit }}) </td>

                                <td class="text-start">
                                    {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                    {{ $sale_product->unit_price_inc_tax }} </td>

                                @if ($defaultLayout->product_discount)
                                    <td class="text-start">
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ $sale_product->unit_discount_amount }}
                                    </td>
                                @endif

                                @if ($defaultLayout->product_tax)
                                    <td class="text-start">
                                        {{ $sale_product->unit_tax_percent }}%
                                    </td>
                                @endif

                                <td class="text-start">
                                    {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                    {{ $sale_product->subtotal }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (count($draft->sale_products) > 9)
                <br>
                <div class="row page_break">
                    <div class="col-md-12 text-right">
                        <h6><em>@lang('Continued To this next page')....</em></h6>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    @if ($defaultLayout->show_total_in_word)
                        <p><b>@lang('In Word') : <span id="inword"></span></b></p>
                    @endif
                </div>
                <div class="col-md-6">
                    <table class="table modal-table table-sm table-sm">
                        <tbody>
                            <tr>
                                <td class="text-start"><strong>@lang('Net Total Amount') :</strong></td>
                                <td class="net_total text-end">
                                    <b>
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ $draft->net_total_amount }}
                                    </b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Order Discount') : </strong></td>
                                <td class="text-end">
                                    <b>
                                        @if ($draft->order_discount_type == 1)
                                            {{ $draft->order_discount_amount }} (Fixed)
                                        @else
                                            {{ $draft->order_discount_amount }} ( {{ $draft->order_discount }}%)
                                        @endif
                                    </b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Order Tax') : </strong></td>
                                <td class="order_tax text-end">
                                    <b>
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ $draft->order_tax_amount }}
                                        ({{ $draft->order_tax_percent }} %)
                                    </b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Shipment charge') : </strong></td>
                                <td class="text-end">
                                    <b>
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ number_format($draft->shipment_charge, 2) }}
                                    </b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('Total Payable') : </strong></td>
                                <td class="text-end">
                                    <b>
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ number_format($draft->total_payable_amount, 2) }}
                                    </b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br><br>

            <div class="row">
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('Customer')'s signature</b>  </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('Checked By')</b>  </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('Approved By')</b> </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('Signature Of Authority')</b></p>
                    </div>
                </div>
            </div><br/>

            {{-- <div class="row">
                <div class="barcode text-center">
                    <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($sale->invoice_id, $generatorPNG::TYPE_CODE_128)) }}">
                </div>
            </div><br>--}}

            <div class="row">
                <div class="col-md-12">
                    <div class="invoice_notice">
                        <p>{!! $defaultLayout->invoice_notice ? '<b>Attention : <b>' . $defaultLayout->invoice_notice : '' !!}</p>
                    </div>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <p>{{ $defaultLayout->footer_text }}</p>
                    </div>
                </div>
            </div>

            <div id="footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="heading text-center">
                            <h4><b>@lang('Sister Concern')</b></h4><br>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:50px;" src="{{ asset('uploads/layout_concern_logo/Nomhost logo.png') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:50px;" src="{{ asset('uploads/layout_concern_logo/Creative Studio.png') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:50px;" src="{{ asset('uploads/layout_concern_logo/Speeddigitposprologo.png') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:50px;" src="{{ asset('uploads/layout_concern_logo/UltimateERPLogo.png') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 text-center">
                        <small>@lang('Print Date') : {{ date('d<m/Y') }}</small>
                    </div>
                    <div class="col-md-6 text-center">
                        <small>@lang('Print Time') : {{ date('h:i:s') }}</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <small>@lang('Powered By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<!-- draft print templete end-->


<script>
    // actual  conversion code starts here
    var ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    var tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    var teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen',
        'nineteen'
    ];

    function convert_millions(num) {
        if (num >= 100000) {
            return convert_millions(Math.floor(num / 100000)) + " Lack " + convert_thousands(num % 1000000);
        } else {
            return convert_thousands(num);
        }
    }

    function convert_thousands(num) {
        if (num >= 1000) {
            return convert_hundreds(Math.floor(num / 1000)) + " thousand " + convert_hundreds(num % 1000);
        } else {
            return convert_hundreds(num);
        }
    }

    function convert_hundreds(num) {
        if (num > 99) {
            return ones[Math.floor(num / 100)] + " hundred " + convert_tens(num % 100);
        } else {
            return convert_tens(num);
        }
    }

    function convert_tens(num) {
        if (num < 10) return ones[num];
        else if (num >= 10 && num < 20) return teens[num - 10];
        else {
            return tens[Math.floor(num / 10)] + " " + ones[num % 10];
        }
    }

    function convert(num) {
        if (num == 0) return "zero";
        else return convert_millions(num);
    }

    document.getElementById('inword').innerHTML = convert(parseInt("{{ $draft->total_payable_amount }}")).replace(
        'undefined', '(some Penny)').toUpperCase() + ' ONLY.';
</script>
