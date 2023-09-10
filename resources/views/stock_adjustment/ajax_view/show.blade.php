@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp 
 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog col-80-modal">
      <div class="modal-content" >
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">@lang('Stock Adjustment Details (Reference No') : <strong>{{ $adjustment->invoice_id }}</strong></h5>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 text-left">
                    <ul class="list-unstyled">
                        <li>
                            <strong>@lang('Business Location') : </strong> 
                            {{ 
                                $adjustment->branch ? $adjustment->branch->name.'/'.$adjustment->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' 
                            }}
                        </li>

                        @if ($adjustment->warehouse_id)
                            <li>
                                <strong>@lang('Adjustment Location') : </strong> 
                                {{ $adjustment->warehouse->warehouse_name.'/'.$adjustment->warehouse->warehouse_code }} <b>(WAREHOUSE)</b> 
                            </li>
                            <li><strong>@lang('Phone') : </strong> {{ $adjustment->warehouse->phone}}</li>
                            <li><strong>@lang('Address') : </strong> {{ $adjustment->warehouse->address}}</li>
                        @elseif($adjustment->branch_id)
                            <li>
                                <strong>@lang('Adjustment Location') : </strong> 
                                {{ $adjustment->branch->name.'/'.$adjustment->branch->branch_code }} <b>(BRANCH)</b>
                            </li>
                            <li><strong>@lang('Phone') : </strong> {{ $adjustment->branch->phone}}</li>
                            <li><strong>@lang('Address') : </strong> 
                                {{ $adjustment->branch->city}}, {{ $adjustment->branch->state}}, {{ $adjustment->branch->zip_code}}, {{ $adjustment->branch->country}}
                            </li>
                        @else
                            <li>
                                <strong>@lang('Adjustment Location') : </strong> 
                                {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b>
                            </li>
                            <li><strong>@lang('Phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</li>
                            <li><strong>@lang('Address') : </strong> 
                                {{ json_decode($generalSettings->business, true)['address'] }}
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-md-6 text-left">
                    <ul class="list-unstyled">
                        <li><strong>@lang('Date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($adjustment->date)) . ' ' . $adjustment->time }}</li>
                        <li><strong>@lang('Reference No') : </strong> {{ $adjustment->invoice_id }}</li>
                        <li><strong>@lang('Type') : </strong>
                            {!! $adjustment->type == 1 ? "<span class='badge bg-primary'>@lang('Normal')</span>" : "<span class='badge bg-danger'>@lang('Abnormal')</span>" !!}
                        </li>
                        <li><strong>@lang('Created By') : </strong>
                            {{ $adjustment->admin ? $adjustment->admin->prefix.' '.$adjustment->admin->name.' '.$adjustment->admin->last_name : 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div><br>

            <div class="row">
                <div class="table-responsive">
                    <table id="" class="table modal-table table-sm">
                        <thead>
                            <tr class="bg-primary text-white text-start">
                                <th class="text-start">SL</th>
                                <th class="text-start">@lang('Product')</th>
                                <th class="text-start">@lang('Quantity')</th>
                                <th class="text-start">@lang('Unit Cost Inc').Tax</th>
                                <th class="text-start">@lang('SubTotal')</th>
                            </tr>
                        </thead>
                        <tbody class="adjustment_product_list">
                            @foreach ($adjustment->adjustment_products as $product)
                                <tr>
                                    <td class="text-start">{{ $loop->index + 1 }}</td>
                                    @php
                                        $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : ''; 
                                    @endphp
                                    <td class="text-start">{{ $product->product->name.$variant }}</td>
                                    <td class="text-start">{{ $product->quantity.' ('.$product->unit.')' }}</td>
                                    <td class="text-start">
                                        {{ json_decode($generalSettings->business, true)['currency'].' '.$product->unit_cost_inc_tax }}
                                    </td>
                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.$product->subtotal }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="payment_table">
                        <div class="table-responsive">
                           <table class="table modal-table table-striped table-sm">
                               <thead>
                                   <tr class="bg-primary text-white">
                                       <th>@lang('Date')</th>
                                       <th>@lang('Voucher No')</th>
                                       <th>@lang('Method')</th>
                                       <th>@lang('Account')</th>
                                       <th>
                                           Recovered Amount({{ json_decode($generalSettings->business, true)['currency'] }})
                                       </th>
                                   </tr>
                               </thead>
                               <tbody id="p_details_payment_list">
                                  @if ($adjustment->recover)
                                    <tr>
                                        <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($adjustment->recover->report_date)) }}</td>
                                        <td>{{ $adjustment->recover->voucher_no }}</td>
                                        <td>{{ $adjustment->recover->paymentMethod ? $adjustment->recover->paymentMethod->name : '' }}</td>
                                        <td>
                                            {{ $adjustment->recover->account ? $adjustment->recover->account->name : 'N/A' }}
                                        </td>
                                        <td>{{ App\Utils\Converter::format_in_bdt($adjustment->recover->recovered_amount) }}</td>
                                    </tr>
                                  @else   
                                      <tr>
                                          <td colspan="7" class="text-center">@lang('No Data Found')</td>
                                      </tr>  
                                  @endif
                               </tbody>
                           </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table modal-table table-sm">
                            <tr>
                                <th class="text-start">@lang('Net Total Amount')</th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'].' '.$adjustment->net_total_amount}}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-start">@lang('Recovered Amount') </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'].' '.$adjustment->recovered_amount }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div><br>

            <hr class="p-0 m-0">
            <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>@lang('Reason') : </h6>
                        <p class="reason">{{ $adjustment->reason }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">@lang('Close')</button>
            <button type="submit" class="c-btn button-success print_btn">@lang('Print')</button>
        </div>
      </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Adjustment print templete-->
<div class="adjustment_print_template d-none">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        @if ($adjustment->branch)
                            <h5 class="branch_name">{{ $adjustment->branch->name.'/'.$adjustment->branch->branch_code }}</h5>
                            <p class="address">{{ $adjustment->branch->city }}, {{ $adjustment->branch->state }},
                                {{ $adjustment->branch->zip_code }}, {{ $adjustment->branch->country }}</p>
                            <p class="branch_phone"><b>@lang('Phone')</b> : {{ $adjustment->branch->phone }}</p>
                            <p class="branch_email">{{ $adjustment->branch->email }}</p>
                        @else
                            <h5 class="business_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                            <p class="address">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                            <p class="branch_phone"><b>@lang('Phone')</b> : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                            <p class="branch_email"><b>@lang('Email')</b> : {{ json_decode($generalSettings->business, true)['email'] }}</p>
                        @endif
                        <h6 class="bill_name">@lang('Stock Adjustment Details')</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-8">
                    <ul class="list-unstyled">
                        <li><strong>@lang('Date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($adjustment->date)) . ' ' . $adjustment->time }}</li>
                        <li><strong>@lang('Reference No') : </strong>{{ $adjustment->invoice_id }}</li>
                          @if ($adjustment->warehouse_id)
                            <li>
                                <strong>@lang('Adjustment Location') : </strong> 
                                {{ $adjustment->warehouse->warehouse_name.'/'.$adjustment->warehouse->warehouse_code }} <b>(WAREHOUSE)</b>
                            </li>
                            <li><strong>@lang('Phone') : </strong> {{ $adjustment->warehouse->phone }}</li>
                            <li><strong>@lang('Address') : </strong> {{ $adjustment->warehouse->address }}</li>
                        @elseif($adjustment->branch_id)
                            <li>
                                <strong>@lang('Adjustment Location') : </strong> 
                                {{ $adjustment->branch->name.'/'.$adjustment->branch->branch_code }} <b>(BRANCH)</b>
                            </li>
                            <li><strong>@lang('Phone') : </strong> {{ $adjustment->branch->phone}}</li>
                            <li><strong>@lang('Address') : </strong> 
                                {{ $adjustment->branch->city}}, {{ $adjustment->branch->state}}, {{ $adjustment->branch->zip_code}}, {{ $adjustment->branch->country}}
                            </li>
                        @else
                            <li>
                                <strong>@lang('Adjustment Location') : </strong> 
                                {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b>
                            </li>
                            <li><strong>@lang('Phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</li>
                            <li><strong>@lang('Address') : </strong> 
                                {{ json_decode($generalSettings->business, true)['address'] }}
                            </li>
                        @endif
                    </ul>
                </div>
        
                <div class="col-4">
                    <ul class="list-unstyled float-right">
                        <li>
                            <strong>@lang('Type') : </strong>
                            {{ $adjustment->type == 1 ? 'Normal' : 'Abnormal' }}
                        </li>
                        <li>
                            <strong>@lang('Created By') : </strong>
                            {{ $adjustment->admin ? $adjustment->admin->prefix.' '.$adjustment->admin->name.' '.$adjustment->admin->last_name : 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <th scope="col" class="text-start">SL</th>
                            <th scope="col" class="text-start">@lang('Product')</th>
                            <th scope="col" class="text-start">@lang('Quantity')</th>
                            <th scope="col" class="text-start">@lang('Unit Cost Inc').Tax</th>
                            <th scope="col" class="text-start">@lang('SubTotal')</th>
                        </tr>
                    </tr>
                </thead>
                <tbody class="adjustment_print_product_list">
                    @foreach ($adjustment->adjustment_products as $product)
                        <tr>
                            <td class="text-start">{{ $loop->index + 1 }}</td>
                            @php
                                $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : ''; 
                            @endphp
                            <td class="text-start">{{ $product->product->name.$variant }}</td>
                            <td class="text-start">{{ $product->quantity.' ('.$product->unit.')' }}</td>
                            <td class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'].' '.$product->unit_cost_inc_tax }}
                            </td>
                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.$product->subtotal }} </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">@lang('Net Total Amount') :</th>
                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['currency'].' '.$adjustment->net_total_amount}}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">@lang('Recovered Amount') :</th>
                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['currency'].' '.$adjustment->recovered_amount }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br><br>

        <div class="note">
            <div class="row">
                <div class="col-md-6">
                    <h6><strong>@lang('CHECKED BY') :</strong></h6>
                </div>

                <div class="col-md-6 text-end">
                    <h6><strong>@lang('APPROVED BY') :</strong></h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($adjustment->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p>{{$adjustment->invoice_id}}</p>
            </div>
        </div>

        @if (env('PRINT_SD_OTHERS') == true)
            <div class="print_footer">
                <div class="text-center">
                    <small>@lang('Software by') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Adjustment print templete end-->