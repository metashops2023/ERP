@extends('layout.master')
@push('stylesheets')
    <style>.input-group-text {font-size: 12px !important;}</style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_purchase_return_form" action="{{ route('purchases.returns.store', $purchaseId) }}" method="POST">
                @csrf
                <input type="hidden" name="action" id="action" value="">
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>@lang('Purchase Return')</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="m-0"><strong>@lang('Invoice ID'): </strong> {{ $purchase->invoice_id }} </p>
                                            <p class="m-0"><strong>@lang('Date'): </strong> {{ $purchase->date }}</p>
                                         </div>
                                         <div class="col-md-6">
                                            <p class="m-0 "><strong> @lang('Supplier') : </strong> {{ $purchase->supplier->name }}</p>
                                            <p class="m-0 branch"><strong>@lang('Business Location') : </strong>
                                                @if($purchase->branch)
                                                    {{ $purchase->branch->name.'/'.$purchase->branch->branch_code }}<b>(B.L.)</b>
                                                @else
                                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(HO)</b>
                                                @endif
                                            </p>
                                             <p class="m-0 warehouse"><strong>@lang('Purchase Stored Location') : </strong>
                                                @if ($purchase->warehouse)
                                                    {{ $purchase->warehouse->warehouse_name.'/'.$purchase->warehouse->warehouse_code }}<b>(WH)</b>
                                                @elseif($purchase->branch)
                                                    {{ $purchase->branch->name.'/'.$purchase->branch->branch_code }} <b>(B.L.)</b>
                                                @else
                                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}<b>(HO)</b>
                                                @endif
                                            </p>
                                         </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>PR.Invoice ID : </b><span
                                                        class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" class="form-control" id="invoice_id" placeholder="@lang('Purchase Return Invoice ID')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-2"><b>@lang('Date') :</b> <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="text" name="date" id="date" class="form-control" autocomplete="off" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-5"><b>@lang('Purchase Return A/C') : <span
                                                    class="text-danger">*</span></b></label>
                                                <div class="col-7">
                                                    <select name="purchase_return_account_id" class="form-control add_input"
                                                        id="purchase_return_account_id" data-name="Purchase Return A/C">
                                                        @foreach ($purchaseReturnAccounts as $purchaseReturnAccount)
                                                            <option value="{{ $purchaseReturnAccount->id }}">
                                                                {{ $purchaseReturnAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_purchase_return_account_id"></span>
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
                                                                    <th>@lang('Product Name')</th>
                                                                    <th></th>
                                                                    <th>@lang('Unit Cost')</th>
                                                                    <th>@lang('Lot Number')</th>
                                                                    <th>@lang('Purchase Quantity')</th>
                                                                    <th>@lang('Return Quantity')</th>
                                                                    <th>@lang('Return Subtotal')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="purchase_return_list"></tbody>
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
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-2"><b>@lang('Total Return Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</b>  </label>
                                                <div class="col-8">
                                                    <input readonly name="total_return_amount" type="number" step="any" id="total_return_amount" class="form-control" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area pt-1">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" data-action="save" class="btn btn-sm btn-primary submit_button float-end">@lang('Save')</button>
                            <button type="submit" data-action="save_and_print" class="btn btn-sm btn-primary submit_button float-end me-1">@lang('Save & Print')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script src="/assets/plugins/custom/print_this/printThis.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function getPurchaseReturn() {
        $.ajax({
            url:"{{route('purchases.returns.get.purchase', $purchaseId)}}",
            async:true,
            type:'get',
            dataType: 'json',
            success:function(purchase){
                if (purchase.purchase_return != null) {
                    $('#invoice_id').val(purchase.purchase_return ? purchase.purchase_return.invoice_id : '');
                    $('#date').val(purchase.purchase_return ? purchase.purchase_return.date : '');

                    if (purchase.purchase_return) {
                        $('#purchase_return_account_id').val(purchase.purchase_return.purchase_return_account_id);
                    }

                    $.each(purchase.purchase_return.purchase_return_products, function (key, return_product) {
                        var tr = "";
                        tr += '<tr >';
                        tr += '<td colspan="2" class="text text-dark">';
                        tr += '<span class="product_name">'+return_product.purchase_product.product.name+'</span>';
                        var variant = return_product.purchase_product.variant ? ' ('+return_product.purchase_product.variant.variant_name+')' : '';

                        tr += '<span class="product_variant"><small><b>'+variant+'</b></small></span>';

                        var code = return_product.purchase_product.variant ? return_product.purchase_product.variant.variant_code : return_product.purchase_product.product.product_code;
                        tr += ' <span class="product_code"><small>('+code+')</small></span>';
                        tr += '<input value="'+return_product.purchase_product_id+'" type="hidden" id="purchase_product_id" name="purchase_product_ids[]">';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<span class="span_unit_cost">'+return_product.purchase_product.net_unit_cost+'</span>';
                        tr += '<input value="'+return_product.purchase_product.net_unit_cost+'" type="hidden" name="unit_costs[]" id="unit_cost">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<span >'+(return_product.purchase_product.lot_no ? return_product.purchase_product.lot_no : '')+'</span>';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<input value="'+return_product.purchase_product.unit+'" type="hidden" name="units[]" id="unit">';
                        tr += '<span class="span_purchase_product_qty">'+return_product.purchase_product.quantity+' ('+return_product.purchase_product.unit+')'+'</span>';
                        tr += '<input value="'+return_product.purchase_product.quantity+'" type="hidden" name="purchase_qtys[]" id="purchase_qty">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="'+return_product.return_qty+'" type="hidden" name="previous_return_quantitiess[]" id="previous_return_quantity">';
                        tr += '<input value="'+return_product.purchase_product.unit+'" type="hidden" id="unit">';
                        tr += '<input value="'+return_product.return_qty+'" required name="return_quantities[]" type="text" class="form-control form-control-sm" id="return_quantity">';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<span class="span_return_subtotal">'+return_product.return_subtotal+'</span>';
                        tr += '<input value="'+return_product.return_subtotal+'"  name="return_subtotals[]" type="hidden" class="form-control form-control-sm" id="return_subtotal">';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#purchase_return_list').append(tr);
                    });
                    calculateTotalAmount();
                } else {
                    $.each(purchase.purchase_products, function (key, purchase_product) {
                        var tr = "";
                        tr += '<tr>';
                        tr += '<td colspan="2" class="text text-dark">';
                        tr += '<span class="product_name">'+purchase_product.product.name+'</span>';
                        var variant = purchase_product.variant ? ' ('+purchase_product.variant.variant_name+')' : '';
                        tr += '<span class="product_variant"><small><b>'+variant+'</b></small></span>';
                        var code = purchase_product.variant ? purchase_product.variant.variant_code : purchase_product.product.product_code;
                        tr += ' <span class="product_code"><small>('+code+')</small></span>';
                        tr += '<input value="'+purchase_product.id+'" type="hidden" id="purchase_product_id" name="purchase_product_ids[]">';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<span class="span_unit_cost">'+purchase_product.net_unit_cost+'</span>';
                        tr += '<input value="'+purchase_product.net_unit_cost+'" type="hidden" name="unit_costs[]" id="unit_cost">';
                        tr += '</td>';


                        tr += '<td class="text">';
                        tr += '<span class="">'+(purchase_product.lot_no ? purchase_product.lot_no : '') +'</span>';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<input value="'+purchase_product.unit+'" type="hidden" name="units[]" id="unit">';
                        tr += '<span class="span_purchase_product_quantity">'+purchase_product.quantity+' ('+purchase_product.unit+')'+'</span>';
                        tr += '<input value="'+purchase_product.quantity+'" type="hidden" name="purchase_quantities[]" id="purchase_quantity">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="0" type="hidden" name="previous_return_quantities[]" id="previous_return_quantity">';
                        tr += '<input value="'+purchase_product.unit+'" type="hidden" id="unit">';
                        tr += '<input value="0.00" required name="return_quantities[]" type="text" class="form-control form-control-sm" id="return_quantity">';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<span class="span_return_subtotal">0.00</span>';
                        tr += '<input value="0.00" name="return_subtotals[]" type="hidden" class="form-control form-control-sm" id="return_subtotal">';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#purchase_return_list').append(tr);
                    });
                    calculateTotalAmount();
                }
            }
        });
    }
    getPurchaseReturn();

    // Calculate total amount functionalitie
    function calculateTotalAmount(){
        var quantities = document.querySelectorAll('#return_quantity');
        var subtotals = document.querySelectorAll('#return_subtotal');

        // Update Net total Amount
        var netTotalAmount = 0;
        subtotals.forEach(function(subtotal){
            netTotalAmount += parseFloat(subtotal.value);
        });

        $('#total_return_amount').val(parseFloat(netTotalAmount).toFixed(2));
    }


    // Return Quantity increase or dicrease and clculate row amount
    $(document).on('input', '#return_quantity', function(){
        var return_quantity = $(this).val() ? $(this).val() : 0;
        if (parseFloat(return_quantity) >= 0) {
            var tr = $(this).closest('tr');
            var previousReturnQty = tr.find('#previous_return_quantity').val();
            var unit = tr.find('#unit').val();
            var limit = tr.find('#purchase_quantity').val();
            var qty_limit = parseFloat(previousReturnQty) + parseFloat(limit);
            if(parseInt(return_quantity) > parseInt(qty_limit)){
                alert('Only '+limit+' '+unit+' is available.');
                $(this).val(parseFloat(limit).toFixed(2));
                var unitPrice = tr.find('#unit_cost').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                tr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount();
            }else{
                var unitPrice = tr.find('#unit_cost').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(return_quantity);
                tr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount();
            }
        }
    });

    //Add purchase request by ajax
    $('#add_purchase_return_form').on('submit', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var request = $(this).serialize();
        var url = $(this).attr('action');
        $('.submit_button').prop('type', 'button');
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                $('.submit_button').prop('type', 'sumbit');
                $('.error').html('');
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'ERROR');
                    $('.loading_button').hide();
                }else if(!$.isEmptyObject(data.seccessMsg)) {
                    toastr.success(data.seccessMsg);
                }else {
                    $('.loading_button').hide();
                    toastr.success('Successfully purchase return is added.');
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                }
            },error: function(err) {
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'sumbit');
                $('.error').html('');

                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('click', '.submit_button', function () {
        var action = $(this).data('action');
        $('#action').val(action);
    });

    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('date'),
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
</script>
@endpush
