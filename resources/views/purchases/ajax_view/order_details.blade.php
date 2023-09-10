@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">
                     @lang('PO Details (Reference ID)')' : <strong>{{ $purchase->invoice_id }}</strong>)
                 </h5>
                 <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-4">
                         <ul class="list-unstyled">
                             <li><strong>@lang('Supplier') :- </strong></li>
                             <li><strong>@lang('Name') : </strong> {{ $purchase->supplier->name }}</li>
                             <li><strong>@lang('Address') : </strong> {{ $purchase->supplier->address }}</li>
                             <li><strong>@lang('Tax Number') : </strong> {{ $purchase->supplier->tax_number }}</li>
                             <li><strong>@lang('Phone') : </strong> {{ $purchase->supplier->phone }}</li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                             <li><strong>@lang('Purchase From') : </strong></li>
                             <li><strong>@lang('Business Location') : </strong>
                                @if ($purchase->branch_id)
                                    {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}(<b>@lang('Branch/Concern')</b>)
                                @else
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head
                                    Office</b>)
                                @endif
                            </li>

                             <li><strong>@lang('Phone') : </strong>
                                @if ($purchase->branch)

                                    {{ $purchase->branch->phone }}, <br>
                                @elseif($purchase->warehouse_id)

                                    {{ $purchase->warehouse->phone }}
                                @else

                                    {{ json_decode($generalSettings->business, true)['phone'] }}
                                @endif
                             </li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                            <li><strong>PO.Invoice ID : </strong> {{ $purchase->invoice_id }}</li>
                            <li><strong>@lang('PO Date') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}</li>
                            <li><strong>@lang('Delivery') : </strong> {{$purchase->delivery_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) : '' }}</li>
                            <li><strong>@lang('Purchase Status') : </strong> <span class="badge bg-primary">@lang('Ordered')</span></li>
                            <li><strong>@lang('Receiving Status') : </strong>
                                @if ($purchase->po_receiving_status == 'Pending')

                                    <span class="badge bg-danger">@lang('Pending')</span>
                                @elseif ($purchase->po_receiving_status == 'Completed')

                                    <span class="badge bg-success">@lang('Completed')</span>
                                @else

                                    <span class="badge bg-primary">@lang('Partial')</span>
                                @endif
                            </li>
                            <li><strong>@lang('Payment Status') : </strong>
                                @php
                                    $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                                @endphp
                                @if ($purchase->due <= 0)
                                     <span class="badge bg-success">@lang('Paid')</span>
                                @elseif($purchase->due > 0 && $purchase->due < $payable)
                                    <span class="badge bg-primary text-white">@lang('Partial')</span>
                                @elseif($payable == $purchase->due)
                                    <span class="badge bg-danger text-white">@lang('Due')</span>
                                @endif
                             </li>
                             <li>
                                 <strong>@lang('Created By') : </strong>
                                {{ $purchase->admin->prefix.' '.$purchase->admin->name.' '.$purchase->admin->last_name }}
                             </li>
                         </ul>
                     </div>
                 </div>
                 <br>
                 <div class="row">
                     <div class="col-md-12">
                         <div class="table-responsive">
                             <table id="" class="table modal-table table-sm table-striped">
                                 <thead>
                                     <tr class="bg-primary">
                                         <th class="text-white text-start">@lang('Product')</th>
                                         <th class="text-white text-start">@lang('Ordered Quantity')</th>
                                         <th class="text-white text-start">@lang('Unit Cost')({{ json_decode($generalSettings->business, true)['currency']}}) (Before Discount)</th>
                                         <th class="text-white text-start">@lang('Unit Discount')({{ json_decode($generalSettings->business, true)['currency']}})</th>
                                         <th class="text-white text-start">@lang('Unit Cost')({{ json_decode($generalSettings->business, true)['currency']}}) (Before Tax)</th>
                                         <th class="text-white text-start">@lang('SubTotal')({{ json_decode($generalSettings->business, true)['currency']}}) (Before Tax)</th>
                                         <th class="text-white text-start">@lang('Tax')(%)</th>
                                         <th class="text-white text-start">@lang('Unit Cost')({{ json_decode($generalSettings->business, true)['currency']}}) (After Tax)</th>
                                         <th class="text-white text-start">@lang('SubTotal')({{ json_decode($generalSettings->business, true)['currency']}})</th>
                                         <th class="text-white text-start">@lang('Pending Qty')</th>
                                         <th class="text-white text-start">@lang('Received Qty')</th>
                                     </tr>
                                 </thead>
                                 <tbody class="purchase_product_list">
                                     @foreach ($purchase->purchase_order_products as $product)
                                        <tr>
                                            @php
                                                $variant = $product->variant ? '('.$product->variant->variant_name.')' : '';
                                            @endphp

                                            <td class="text-start">{{ $product->product->name.' '.$variant }}</td>
                                            <td class="text-start">{{ $product->order_quantity }}</td>
                                            <td class="text-start">
                                                {{ App\Utils\Converter::format_in_bdt($product->unit_cost) }}
                                            </td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->unit_discount) }} </td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->unit_cost_with_discount) }}</td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->subtotal) }}</td>
                                            <td class="text-start">{{ $product->unit_tax.'('.$product->unit_tax_percent.'%)' }}</td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->net_unit_cost) }} </td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->line_total) }}</td>
                                            <td class="text-start text-danger">{{ $product->pending_quantity }}</td>
                                            <td class="text-start text-success">{{ $product->received_quantity }}</td>
                                            @if (count($product->receives) > 0)

                                                <tr>
                                                    <td colspan="3" class="text-center"><strong>@lang('Receive Details') âž¡</strong></td>

                                                    <td colspan="8">
                                                        <table class="table modal-table table-sm table-striped">
                                                            <thead>
                                                                <tr class="bg-secondary">
                                                                    <th class="text-white">@lang('Challan No')</th>
                                                                    <th class="text-white">@lang('Lot Number')</th>
                                                                    <th class="text-white">@lang('Received Date')</th>
                                                                    <th class="text-white">@lang('Received Quantity')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($product->receives as $receive)
                                                                    <tr>
                                                                        <td>{{ $receive->purchase_challan }}</td>

                                                                        <td>{{ $receive->lot_number }}</td>

                                                                        <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($receive->received_date)) }}</td>

                                                                        <td>{{ $receive->qty_received }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>

                 <div class="row">
                     <div class="col-md-6">
                         <div class="payment_table">
                             <div class="table-responsive">
                                <table class="table modal-table table-striped table-sm">
                                    <thead>
                                        <tr class="bg-primary text-white">
                                            <th class="text-start">@lang('Date')</th>
                                            <th class="text-start">@lang('Voucher No')</th>
                                            <th class="text-start">@lang('Method')</th>
                                            <th class="text-start">@lang('Type')</th>
                                            <th class="text-start">@lang('Account')</th>
                                            <th class="text-end">
                                                Amount({{ json_decode($generalSettings->business, true)['currency'] }})
                                            </th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="p_details_payment_list">
                                       @if (count($purchase->purchase_payments) > 0)

                                           @foreach ($purchase->purchase_payments as $payment)

                                               <tr data-info="{{ $payment }}">
                                                   <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) }}</td>
                                                   <td class="text-start">{{ $payment->invoice_id }}</td>
                                                   <td class="text-start">{{ $payment->pay_mode }}</td>
                                                   <td class="text-start">
                                                        @if ($payment->is_advanced == 1)

                                                            <b>@lang('PO Advance Payment')</b>
                                                        @else

                                                            {{ $payment->payment_type == 1 ? 'Payment' : 'Received Return Amt.' }}
                                                        @endif
                                                    </td>
                                                    <td class="text-start">
                                                        {{ $payment->account ? $payment->account->name.' (A/C'.$payment->account->account_number.')' : 'N/A' }}
                                                    </td>
                                                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}</td>
                                                    <td class="text-start">
                                                       @if (auth()->user()->branch_id == $purchase->branch_id)

                                                           @if ($payment->payment_type == 1)

                                                               <a href="{{ route('purchases.payment.edit', $payment->id) }}" id="edit_payment" class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                                                           @else

                                                               <a href="{{ route('purchases.return.payment.edit', $payment->id) }}" id="edit_return_payment" class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                                                           @endif

                                                           <a href="{{ route('purchases.payment.details', $payment->id) }}" id="payment_details" class="btn-sm"><i class="fas fa-eye text-primary"></i></a>
                                                       @else
                                                           ......
                                                       @endif
                                                   </td>
                                               </tr>
                                           @endforeach
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
                                    <th class="text-end">@lang('Net Total Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Order Discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ $purchase->order_discount }} {{ $purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Order Tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ $purchase->purchase_tax_amount.' ('.$purchase->purchase_tax_percent.'%)' }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Shipment Charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end"><b></b>
                                           {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Grand Total') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
                                   </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                 </div>
                 <hr>
                 <div class="row">
                     <div class="col-md-6">
                         <div class="details_area">
                             <p><b>@lang('Shipping Details')</b> : </p>
                             <p class="shipping_details">{{ $purchase->shipment_details }}</p>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="details_area">
                             <p><b>@lang('Purchase Note')</b> : </p>
                             <p class="purchase_note">{{ $purchase->purchase_note }}</p>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('purchases.po.receive.process', $purchase->id) }}" class="c-btn btn-secondary py-1 px-1 text-white"> <i class="fas fa-check-double"></i> @lang('PO To Receive')</a>
                        <a href="{{ route('purchases.order.supplier.copy.print', $purchase->id) }}" id="print_supplier_copy" class="c-btn btn-info py-1 px-1 text-white"> <i class="fas fa-print"></i> @lang('Print Supplier Copy')</a>
                        <button type="submit" class="c-btn button-success print_btn">@lang('Print')</button>
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">@lang('Close')</button>
                    </div>
                </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Details Modal End-->
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
 <!-- Purchase Order print templete-->
    <div class="purchase_print_template d-none">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        @if ($purchase->branch)
                            @if ($purchase->branch->logo != 'default.png')

                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $purchase->branch->logo) }}">
                            @else

                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $purchase->branch->name }}</span>
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
                            <h3 class="bill_name">@lang('Purchase Order')</h3>
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
                            <li><strong>@lang('Supplier') :- </strong></li>
                            <li><strong>@lang('Name') : </strong>{{ $purchase->supplier->name }}</li>
                            <li><strong>@lang('Address') : </strong>{{ $purchase->supplier->address }}</li>
                            <li><strong>@lang('Tax Number') : </strong> {{ $purchase->supplier->tax_number }}</li>
                            <li><strong>@lang('Phone') : </strong> {{ $purchase->supplier->phone }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('Purchase From') : </strong></li>
                            <li>
                                <strong>@lang('Business Location') : </strong>
                                @if ($purchase->branch)

                                    {!! $purchase->branch->name.' '.$purchase->branch->branch_code.' <b>(BL)</b>' !!}
                                @else

                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>HO</b>)
                                @endif
                            </li>
                            <li><strong>@lang('Ordered Location') : </strong>
                                @if ($purchase->warehouse_id )

                                    {{ $purchase->warehouse->warehouse_name . '/' . $purchase->warehouse->warehouse_code }}
                                    (<b>WH</b>)
                                @elseif($purchase->branch_id)

                                    {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}
                                    (<b>B.L</b>)
                                @else

                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>@lang('Head Office')</b>)
                                @endif
                            </li>
                            <li><strong>@lang('Phone') : </strong>
                                @if ($purchase->branch)

                                    {{ $purchase->branch->phone }}
                                @elseif($purchase->warehouse_id)

                                    {{ $purchase->warehouse->phone }}.
                                @else

                                    {{ json_decode($generalSettings->business, true)['phone'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>PO.Invoice ID : </strong> {{ $purchase->invoice_id }}</li>
                            <li><strong>@lang('Purchase Date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}</li>
                            <li><strong>@lang('Delivery Date') : </strong>{{ $purchase->delivery_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->delivery_date)) : '' }}</li>

                            <li><strong>@lang('Purchase Status') : </strong>@lang('Ordered')</li>
                            <li><strong>@lang('Receiving Status') : </strong>{{ $purchase->po_receiving_status }}</li>
                            <li><strong>@lang('Payment Status') : </strong>
                               @php
                                   $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                               @endphp
                               @if ($purchase->due <= 0)

                                   Paid
                               @elseif($purchase->due > 0 && $purchase->due < $payable)

                                   Partial
                               @elseif($payable == $purchase->due)

                                   Due
                               @endif
                            </li>
                            <li><strong>@lang('Created By') : </strong>
                                {{ $purchase->admin->prefix.' '.$purchase->admin->name.' '.$purchase->admin->last_name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="purchase_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">@lang('Product')</th>
                            <th scope="col">@lang('Ordered Quantity')</th>
                            <th scope="col">@lang('Unit Cost')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                            <th scope="col">@lang('Unit Discount')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                            <th scope="col">@lang('Tax')(%)</th>
                            <th scope="col">@lang('SubTotal')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                            <th scope="col">@lang('Pending Qty')</th>
                            <th scope="col">@lang('Received Qty')</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($purchase->purchase_order_products as $product)
                            <tr>
                                @php
                                    $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : '';
                                @endphp

                                <td>
                                    {{ Str::limit($product->product->name, 25).' '.$variant }}
                                    <small>{!! $product->description ? '<br/>'.$product->description : '' !!}</small>
                                </td>

                                <td>{{ $product->order_quantity }}</td>
                                <td>
                                    {{ App\Utils\Converter::format_in_bdt($product->unit_cost) }}
                                </td>
                                <td>{{ App\Utils\Converter::format_in_bdt($product->unit_discount) }} </td>
                                <td>{{ $product->unit_tax.'('.$product->unit_tax_percent.'%)' }}</td>
                                <td>{{ App\Utils\Converter::format_in_bdt($product->line_total) }}</td>
                                <td>{{ $product->pending_quantity }}</td>
                                <td>{{ $product->received_quantity }}</td>

                                @if (count($product->receives) > 0)
                                    <tr>
                                        <td colspan="3" class="text-center"><strong>@lang('Receive Details') âž¡</strong></td>

                                        <td colspan="8">
                                            <table class="table modal-table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>@lang('Challan No')</th>
                                                        <th>@lang('Lot Number')</th>
                                                        <th>@lang('Received Date')</th>
                                                        <th>@lang('Received Quantity')</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($product->receives as $receive)
                                                        <tr class="text-end">
                                                            <td>{{ $receive->purchase_challan }}</td>

                                                            <td>{{ $receive->lot_number }}</td>

                                                            <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($receive->received_date)) }}</td>

                                                            <td>{{ $receive->qty_received }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-6">
                    <p><strong>@lang('Order Note') :</strong> </p>
                    <p>{{ $purchase->purchase_note }}</p><br>
                    <p><strong>@lang('Shipment Details') :</strong> </p>
                    <p>{{ $purchase->shipment_details }}</p>
                </div>

                <div class="col-6">
                    <table class="table modal-table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th colspan="11" class="text-end">@lang('Net Total Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                                </td>
                            </tr>
                            <tr>
                                <th colspan="11" class="text-end">Order Discount :
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                </th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }} {{$purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                                </td>
                            </tr>
                            <tr>
                                <th colspan="11" class="text-end">@lang('Order Tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->purchase_tax_amount).' ('.$purchase->purchase_tax_percent.'%)' }}
                                </td>
                            </tr>

                            <tr>
                                <th colspan="11" class="text-end">@lang('Shipment Charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <th colspan="11" class="text-end">@lang('Grand Total') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th colspan="11" class="text-end">@lang('Paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <th colspan="11" class="text-end">@lang('Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
                                </td>
                            </tr>
                        </thead>
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
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($purchase->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{$purchase->invoice_id}}</p>
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
 <!-- Purchase print templete end-->
