    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-xl">
          <div class="modal-content" >
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">@lang('Send Stock Details') </h5>
              <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>@lang('B.Location') (To) : </strong></li>
                            <li><strong>@lang('Name') :</strong> {{ $sendStock->branch ? $sendStock->branch->name.'/'.$sendStock->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}</li>
                            <li><strong>@lang('Phone') : </strong> {{ $sendStock->branch ? $sendStock->branch->phone : json_decode($generalSettings->business, true)['phone'] }}</li>
                            @if ($sendStock->branch)
                                <li><strong>@lang('Address') : </strong> 
                                    {{ $sendStock->branch->city }},
                                    {{ $sendStock->branch->state }},
                                    {{ $sendStock->branch->zip_code }},
                                    {{ $sendStock->branch->country }}.
                                </li>
                            @else 
                                {{ json_decode($generalSettings->business, true)['address'] }}
                            @endif
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Warehouse (To)') : </strong></li>
                            <li><strong>@lang('Name') :</strong> {{ $sendStock->warehouse->warehouse_name.'/'.$sendStock->warehouse->warehouse_code }}</li>
                            <li><strong>@lang('Phone') : </strong> {{ $sendStock->warehouse->phone }}</li>
                            <li><strong>@lang('Address') : </strong> {{ $sendStock->warehouse->address }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled float-right">
                            <li><strong>@lang('Date') : </strong> {{ $sendStock->date }}</li>
                            <li><strong>@lang('Reference ID') : </strong>{{ $sendStock->invoice_id }}</li>
                            <li><strong>@lang('Status') : </strong> 
                                @if ($sendStock->status == 1) 
                                <span class="badge bg-danger">@lang('Pending')</span>
                                @elseif($sendStock->status == 2)
                                    <span class="badge bg-primary">@lang('Partial')</span>
                                @elseif($sendStock->status == 3)
                                <span class="badge bg-success">@lang('Completed')</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="" class="table table-sm modal-table">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-start">SL</th>
                                    <th class="text-start">@lang('Product')</th>
                                    <th class="text-start">@lang('Unit Price')</th>
                                    <th class="text-start">@lang('Quantity')</th>
                                    <th class="text-start">@lang('Unit')</th>
                                    <th class="text-start">@lang('Pending Qty')</th>
                                    <th class="text-start">@lang('Received Qty')</th>
                                    <th class="text-start">@lang('SubTotal')</th>
                                </tr>
                            </thead>
                            <tbody class="transfer_print_product_list">
                                @foreach ($sendStock->transfer_products as $transfer_product)
                                    <tr>
                                        <td class="text-start">{{ $loop->index + 1 }}</td>
                                        @php
                                            $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                                        @endphp
                                        <td class="text-start">{{ $transfer_product->product->name.$variant }}</td>
                                        <td class="text-start">{{ $transfer_product->unit_price}}</td>
                                        <td class="text-start">{{ $transfer_product->quantity }}</td>
                                        <td class="text-start">{{ $transfer_product->unit }}</td>
                                        @php
                                            $panding_qty = $transfer_product->quantity - $transfer_product->received_qty;
                                        @endphp
                                        <td class="text-start"><b>{{ $panding_qty.' ('.$transfer_product->unit.')' }}</b></td>
                                        <td class="text-start">{{ $transfer_product->received_qty.' ('.$transfer_product->unit.')' }}</td>
                                        <td class="text-start">{{ $transfer_product->subtotal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
    
              <hr class="p-0 m-0">
              <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>@lang('Receiver Note') : </h6>
                        <p class="receiver_note">{{ $sendStock->receiver_note }}</p>
                    </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">@lang('Close')</button>
                <button type="submit" class="c-btn button-success print_btn float-end">@lang('Print')</button>
            </div>
          </div>
        </div>
    </div>
    <!-- Details Modal End-->

    <!-- Transfer print templete-->
    <div class="transfer_print_template d-none">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="heading text-center">
                            <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                            <p class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                            <p class="company_address">@lang('Phone') : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                            <h6 class="bill_name">@lang('Send Stock Details')</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sale_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('B.Location') (@lang('From')): </strong></li>
                            <li><strong>@lang('Name') :</strong> {{ $sendStock->branch ? $sendStock->branch->name.'/'.$sendStock->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}</li>
                            <li><strong>@lang('Phone') : </strong> 
                                {{ $sendStock->branch ? $sendStock->branch->phone : json_decode($generalSettings->business, true)['phone'] }}
                            </li>
                            @if ($sendStock->branch)
                                <li><strong>@lang('Address') : </strong> 
                                    {{ $sendStock->branch->city }},
                                    {{ $sendStock->branch->state }},
                                    {{ $sendStock->branch->zip_code }},
                                    {{ $sendStock->branch->country }}.
                                </li>
                            @else 
                                <li><strong>@lang('Address') : </strong> 
                                    {{ json_decode($generalSettings->business, true)['address'] }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Warehouse (To)') : </strong></li>
                            <li><strong>@lang('Name') :</strong> {{ $sendStock->warehouse->warehouse_name.'/'.$sendStock->warehouse->warehouse_code }}</li>
                            <li><strong>@lang('Phone') : </strong> {{ $sendStock->warehouse->phone }}</li>
                            <li><strong>@lang('Address') : </strong> {{ $sendStock->warehouse->address }}</li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-4">
                        <ul class="list-unstyled float-right">
                            <li><strong>@lang('Date') : </strong> {{ $sendStock->date }}</li>
                            <li><strong>@lang('Reference ID') : </strong>{{ $sendStock->invoice_id }}</li>
                            <li><strong>@lang('Status') : </strong> 
                                @if ($sendStock->status == 1) 
                                    Pending
                                @elseif($sendStock->status == 2)
                                    Partial
                                @elseif($sendStock->status == 3)
                                    Completed
                                @endif
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
                                <th scope="col">SL</th>
                                <th scope="col">@lang('Product')</th>
                                <th scope="col">@lang('Unit Price')</th>
                                <th scope="col">@lang('Quantity')</th>
                                <th scope="col">@lang('Unit')</th>
                                <th scope="col">@lang('Pending Qty')</th>
                                <th scope="col">@lang('Received Qty')</th>
                                <th scope="col">@lang('SubTotal')</th>
                            </tr>
                        </tr>
                    </thead>
                    <tbody class="transfer_print_product_list">
                        @foreach ($sendStock->transfer_products as $transfer_product)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                @php
                                    $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                                @endphp
                                <td class="text-start">{{ $transfer_product->product->name.$variant }}</td>
                                <td class="text-start">{{ $transfer_product->unit_price}}</td>
                                <td class="text-start">{{ $transfer_product->quantity }}</td>
                                <td class="text-start">{{ $transfer_product->unit }}</td>
                                @php
                                    $panding_qty = $transfer_product->quantity - $transfer_product->received_qty;
                                @endphp
                                <td class="text-start"><b>{{ $panding_qty.' ('.$transfer_product->unit.')' }}</b></td>
                                <td class="text-start">{{ $transfer_product->received_qty.' ('.$transfer_product->unit.')' }}</td>
                                <td class="text-start">{{ $transfer_product->subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br><br> 
            <div class="note">
                <div class="row">
                    <div class="col-md-6">
                        <h6><strong>@lang('Receiver')'s Signature</strong></h6>
                    </div>
                    <div class="col-md-6 text-end">
                        <h6><strong>@lang('Signature Of Authority')</strong></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Transfer print templete end-->