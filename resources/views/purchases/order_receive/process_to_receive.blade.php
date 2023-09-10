@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        .sale-item-sec {height: 330px!important;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="receive_form" action="{{ route('purchases.po.receive.process.store', $purchase->id) }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>@lang('Receive Purchase Order')</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Supplier') :</b><span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input readonly type="text" id="supplier_name" class="form-control" value="{{ $purchase->supplier->name.' ('.$purchase->supplier->phone.')' }}">
                                                </div>
                                            </div>

                                            @if ($purchase->warehouse_id)
                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('Warehouse') :</b><span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select class="form-control changeable add_input"
                                                            name="warehouse_id" data-name="Warehouse" id="warehouse_id">
                                                            <option value="">@lang('Select Warehouse')</option>
                                                            @foreach ($warehouses as $warehouse)
                                                                <option {{ $purchase->warehouse_id == $warehouse->id ? 'SELECTED' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name.'/'.$warehouse->warehouse_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="input-group mt-1">
                                                    <label class="col-4"><span class="text-danger">*</span> <b>@lang('B.Location') :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>PO.Invoice ID :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="@lang('Order Invoice ID')" autocomplete="off" value="{{ $purchase->invoice_id }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class=" col-2"><b>@lang('Date') :</b></label>
                                                <div class="col-8">
                                                    <input required type="text" name="date" class="form-control changeable"
                                                         id="datepicker" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-3"><b>@lang('Status') :</b></label>
                                                <div class="col-8">
                                                    <select class="form-control changeable" name="purchase_status" id="purchase_status">
                                                        <option value="3">@lang('Ordered')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>@lang('Product')</th>
                                                                    <th>@lang('Ordered Qty')</th>
                                                                    <th>@lang('Unit Cost (Inc. Tax)')</th>
                                                                    <th>@lang('Subtotal')</th>
                                                                    <th>@lang('Pending Qty')</th>
                                                                    <th>@lang('Receive Qty')</th>
                                                                    <th>@lang('Add Receive')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="order_list">
                                                                @foreach ($purchase->purchase_order_products as $row)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" name="purchase_order_product_ids[]" id="purchase_order_product_id" value="{{ $row->id }}">
                                                                            <input type="hidden" name="product_ids[]" value="{{ $row->product_id }}">
                                                                            <input type="hidden" name="variant_ids[]" value="{{ $row->product_variant_id ? $row->product_variant_id : 'noid' }}">
                                                                            {{ Str::limit($row->product->name, 25) }}
                                                                            <b>{{ $row->variant ? ' - '.$row->variant->variant_name : '' }}</b>
                                                                        </td>

                                                                        <td>
                                                                            <input type="hidden" name="ordered_quantities[]" id="ordered_quantity" class="ordered_quantity-{{ $row->id }}" value="{{ $row->order_quantity }}">
                                                                            <input type="hidden" id="unit" value="{{$row->unit}}">
                                                                            <b>{{ $row->order_quantity }} ({{$row->unit}})</b>
                                                                        </td>

                                                                        <td> <b>{{ $row->net_unit_cost }}</b></td>

                                                                        <td> <b>{{ $row->line_total }}</b></td>

                                                                        <td>
                                                                            <input readonly type="text" class="form-control text-danger bold_input_field pending_quantity-{{ $row->id }}" name="pending_quantities[]" id="pending_quantity" value="{{ $row->pending_quantity }}">
                                                                        </td>

                                                                        <td>
                                                                            <input readonly type="number" step="any" class="form-control text-success bold_input_field received_quantity-{{ $row->id }}" name="received_quantities[]" id="received_quantity" value="{{ $row->received_quantity }}">
                                                                        </td>

                                                                        <td>
                                                                            <a href="#" class="btn btn-sm btn-success" data-id="{{ $row->id }}" id="add_receive">+</a>
                                                                        </td>

                                                                        @if (count($row->receives) > 0)
                                                                            <tr>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td colspan="4">
                                                                                    <table class="display data__table">
                                                                                        <tbody id="{{ $row->id }}">
                                                                                            @foreach ($row->receives as $receive)
                                                                                                <tr class="text-end">
                                                                                                    <td>
                                                                                                        <input type="text" name="or_receive_rows[{{ $row->id }}][purchase_challan][]" value="{{ $receive->purchase_challan }}" placeholder="@lang('Challan No')">

                                                                                                        <input type="hidden" name="or_receive_rows[{{ $row->id }}][receive_id][]" value="{{ $receive->id }}">
                                                                                                    </td>

                                                                                                    <td>
                                                                                                        <input type="text" name="or_receive_rows[{{ $row->id }}][lot_number][]" value="{{ $receive->lot_number }}" placeholder="@lang('Lot Number')">
                                                                                                    </td>

                                                                                                    <td>
                                                                                                        <input required type="date" name="or_receive_rows[{{ $row->id }}][received_date][]" value="{{ $receive->received_date }}" placeholder="@lang('Received Date')">
                                                                                                    </td>

                                                                                                    <td>
                                                                                                        <input required type="number" step="any" name="or_receive_rows[{{ $row->id }}][qty_received][]" id="qty_received-{{ $row->id }}" value="{{ $receive->qty_received }}"  data-id="{{ $row->id }}" class="qty_received" placeholder="@lang('Received Quantity')">
                                                                                                    </td>

                                                                                                    <td></td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        @else
                                                                            <tr>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td colspan="4">
                                                                                    <table class="display data__table">
                                                                                        <tbody id="{{ $row->id }}">
                                                                                            <tr class="text-end">
                                                                                                <td>
                                                                                                    <input type="text" name="or_receive_rows[{{ $row->id }}][purchase_challan][]" placeholder="@lang('Challan No')">

                                                                                                    <input type="hidden" name="or_receive_rows[{{ $row->id }}][receive_id][]" value="">
                                                                                                </td>

                                                                                                <td>
                                                                                                    <input type="text" name="or_receive_rows[{{ $row->id }}][lot_number][]" placeholder="@lang('Lot Number')">
                                                                                                </td>

                                                                                                <td>
                                                                                                    <input required type="date" name="or_receive_rows[{{ $row->id }}][received_date][]" placeholder="@lang('Received Date')">
                                                                                                </td>

                                                                                                <td>
                                                                                                    <input required type="number" step="any" name="or_receive_rows[{{ $row->id }}][qty_received][]" id="qty_received-{{ $row->id }}" data-id="{{ $row->id }}" class="qty_received" placeholder="@lang('Received Quantity')">
                                                                                                </td>

                                                                                                <td>
                                                                                                    <a href="#" class="btn btn-sm btn-danger" data-id="{{ $row->id }}" id="delete_partial_receive">X</a>
                                                                                                </td>
                                                                                            </tr>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form_element">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class=" col-4"><b>@lang('Total Item') :</b> </label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="{{ $purchase->total_item }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <input type="hidden" name="total_pending" id="total_pending" value="{{ $purchase->po_pending_qty }}" tabindex="-1">
                                                    <input type="hidden" name="total_received" id="total_received" value="{{ $purchase->po_received_qty }}" tabindex="-1">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('Order Discount') :</b> {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                        <div class="col-8">
                                                            <input readonly name="order_discount_amount" type="number" step="any" class="form-control" id="order_discount_amount" value="{{ $purchase->order_discount_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Order Tax') :</b> </label>
                                                        <div class="col-8">
                                                            <input readonly name="purchase_tax_amount" type="text" class="form-control" id="purchase_tax_amount" value="{{ $purchase->purchase_tax_amount.'('.$purchase->purchase_tax_percent.'%)' }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('Shipment Cost') :</b> {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                        <div class="col-8">
                                                            <input readonly name="shipment_charge" type="number" class="form-control" id="shipment_charge" value="{{ $purchase->shipment_charge }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Total Payable') :</b>  {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                        <div class="col-8">
                                                            <input readonly name="total_purchase_amount" type="number" step="any" class="form-control" value="{{ $purchase->total_purchase_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b> @lang('Paid') :</b> {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="{{ $purchase->paid }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class=" col-4"><b>@lang('Current Order Due') :</b> {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="due" id="due" class="form-control text-danger bold_input_field" value="{{ $purchase->due }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Paying Amount') : </b> {{ json_decode($generalSettings->business, true)['currency'] }} <strong>>></strong></label>
                                                        <div class="col-8">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <input type="number" step="any" name="paying_amount" class="form-control" id="paying_amount" value="0.00" autocomplete="off">
                                                                </div>

                                                                <div class="col-md-5">
                                                                    <input type="text" step="any" name="fixed_payment_date" class="form-control" id="fixed_payment_date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" placeholder="@lang('DD-MM-YYYY')" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Pay Method') :</b> </label>
                                                        <div class="col-8">
                                                            <select name="payment_method_id" class="form-control" id="payment_method_id">
                                                                @foreach ($methods as $method)
                                                                    <option
                                                                        data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                                                        value="{{ $method->id }}">
                                                                        {{ $method->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Account') :</b> </label>
                                                        <div class="col-8">
                                                            <select name="account_id" class="form-control" id="account_id">
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{ $account->id }}">
                                                                        @php
                                                                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                                                            $balance = ' BL : '.$account->balance;
                                                                        @endphp
                                                                        {{ $account->name.$accountType.$balance}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label  class=" col-4"><b>@lang('Total Due') :</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" class="form-control text-danger bold_input_field" name="purchase_due" id="purchase_due" value="{{ $purchase->due }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Pay Note') :</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" name="payment_note" class="form-control" id="payment_note" placeholder="@lang('Payment note')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button class="btn btn-sm btn-primary submit_button float-end">@lang('Save Changes')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).on('click', '#add_receive', function(e) {
            e.preventDefault();

            var purchase_order_product_id = $(this).data('id');

            var tr = '';
            tr += '<tr class="text-end">';
            tr += '<td>';
            tr += '<input type="text" name="or_receive_rows['+purchase_order_product_id+'][purchase_challan][]" placeholder="@lang('Challan No')">';
            tr += '<input type="hidden" name="or_receive_rows['+purchase_order_product_id+'][receive_id][]">';
            tr += '</td>';
            tr += '<td><input type="text" name="or_receive_rows['+purchase_order_product_id+'][lot_number][]" placeholder="@lang('Lot Number')"></td>';
            tr += '<td><input required type="date" name="or_receive_rows['+purchase_order_product_id+'][received_date][]" placeholder="@lang('Received Date')"></td>';
            tr += '<td><input required type="number" step="any" name="or_receive_rows['+purchase_order_product_id+'][qty_received][]" id="qty_received-'+purchase_order_product_id+'" class="qty_received" data-id="'+purchase_order_product_id+'" placeholder="@lang('Received Quantity')" autofocus></td>';
            tr += '<td><a href="#" class="btn btn-sm btn-danger" data-id="'+purchase_order_product_id+'" id="delete_partial_receive">X</a></td>';
            tr += '</tr>';

            $('#'+purchase_order_product_id).append(tr);
        });

        $(document).on('click', '#delete_partial_receive', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $(this).closest('tr').remove();
            calulateOnlyReceiveQty(id);
            calulateTotalReceiveAndPendingQty();
        });

        $(document).on('input', '.qty_received', function() {

            var val = $(this).val();
            var id = $(this).data('id');

            var total_qty_received = calulateOnlyReceiveQty(id);

            if (parseFloat(total_qty_received) >= 0) {

                var ordered_quantity = $('.ordered_quantity-'+id).val();
                total_qty_received = calulateOnlyReceiveQty(id);
                calulateTotalReceiveAndPendingQty();

                if (parseFloat(total_qty_received) > parseFloat(ordered_quantity)) {

                    alert('Only - ' + ordered_quantity + ' ' + ' is available.');
                    var instantValue = parseFloat(ordered_quantity) - (parseFloat(total_qty_received) - parseFloat(val));
                    $(this).val(instantValue);
                    calulateOnlyReceiveQty(id);
                    calulateTotalReceiveAndPendingQty();
                    return;
                }
            }
        });

        function calulateOnlyReceiveQty(id) {

            var received_quantities = document.querySelectorAll('#qty_received-'+id);

            var ordered_quantity = $('.ordered_quantity-'+id).val();

            var total_received = 0;
            received_quantities.forEach(function(qty) {

                total_received += parseFloat(qty.value) ? parseFloat(qty.value) : 0;
            });

            //console.log(total_received);
            var pending_qty = parseInt(ordered_quantity) - parseFloat(total_received);

            $('.pending_quantity-'+id).val(parseInt(pending_qty).toFixed(2));

            $('.received_quantity-'+id).val(parseFloat(total_received).toFixed(2));
            return parseFloat(total_received);
        }

        function calulateTotalReceiveAndPendingQty() {

            var pending_quantities = document.querySelectorAll('#pending_quantity');
            var received_quantities = document.querySelectorAll('#received_quantity');

            var total_pending = 0;
            pending_quantities.forEach(function(qty) {

                total_pending += parseFloat(qty.value) ? parseFloat(qty.value) : 0;
            });

            var total_received = 0;
            received_quantities.forEach(function(qty) {

                total_received += parseFloat(qty.value) ? parseFloat(qty.value) : 0;
            });

            $('#total_pending').val(parseFloat(total_pending));
            $('#total_received').val(parseFloat(total_received));
        }

        $(document).on('input', '#received_quantity', function() {

            var received_qty = $(this).val() ? $(this).val() : 0;
            if (parseFloat(received_qty) >= 0) {

                var tr = $(this).closest('tr');
                var ordered_quantity = tr.find('#ordered_quantity').val();
                var unit = tr.find('#unit').val();
                var pending_qty = parseInt(ordered_quantity) - parseFloat(received_qty);
                tr.find('#pending_quantity').val(parseFloat(pending_qty).toFixed(2));
                calulateTotalReceiveAndPendingQty();

                if (parseInt(received_qty) > parseInt(ordered_quantity)) {

                    alert('Only - ' + ordered_quantity + ' ' + unit + ' is available.');
                    $(this).val(ordered_quantity);
                    tr.find('#pending_quantity').val(parseFloat(0).toFixed(2));
                    calulateTotalReceiveAndPendingQty();
                    return;
                }
            }
        });

        //Add receive request by ajax
        $('#receive_form').on('submit', function(e){
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){

                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();
                    toastr.success(data);
                    window.location = "{{ url()->previous() }}";
                }, error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {

                        toastr.error('Server error please contact to the support.');
                    }
                }
            });
        });

        // Input paying amount and clculate due amount
        $(document).on('input', '#paying_amount', function(){

            var payingAmount = $(this).val() ? $(this).val() : 0;
            var due = $('#due').val() ? $('#due').val() : 0;
            var calcDueAmount = parseFloat(due) - parseFloat(payingAmount);
            $('#purchase_due').val(parseFloat(calcDueAmount).toFixed(2));
        });

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '' ;
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: _expectedDateFormat,
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('fixed_payment_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: _expectedDateFormat,
        });

        $('#payment_method_id').on('change', function () {

            var account_id = $(this).find('option:selected').data('account_id');
            setMethodAccount(account_id);
        });

        function setMethodAccount(account_id) {

            if (account_id) {

                $('#account_id').val(account_id);
            }else if(account_id === ''){

                $('#account_id option:first-child').prop("selected", true);
            }
        }

        setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));
    </script>
@endpush
