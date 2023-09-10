@extends('layout.master')
@push('stylesheets')
    <style>
         .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 96.7%;z-index: 9999999;padding: 0;left: 3.3%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 13px;padding: 3px 3px;display: block;border: 1px solid lightgray;}
        .select_area ul li a:hover {background-color: #999396;color: #fff;}
        .selectProduct {background-color: #746e70;color: #fff !important;}
        .input-group-text-sale {font-size: 7px !important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_adjustment_form" action="{{ route('stock.adjustments.store') }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>@lang('Add Stock Adjustment') <small>(@lang('From Business Location').)</small></h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('B.Location'):</b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control"
                                                        value="{{
                                                            auth()->user()->branch ?
                                                            auth()->user()->branch->name . '/' . auth()->user()->branch->branch_code
                                                            : json_decode($generalSettings->business, true)['shop_name'].' (HO)'
                                                        }}">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('Adjust'). A<C : <span
                                                    class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <select name="adjustment_account_id" class="form-control add_input"
                                                        id="adjustment_account_id" data-name="Stock Adjustiment A/C">

                                                        @foreach ($stockAdjustmentAccounts as $stockAdjustmentAccount)

                                                            <option value="{{ $stockAdjustmentAccount->id }}">
                                                                {{ $stockAdjustmentAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_adjustiment_account_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('Voucher No') :</b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="If you keep this field empty, The Voucher No will be generated automatically." class="fas fa-info-circle tp"></i></label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id"
                                                        class="form-control" placeholder="@lang('Voucher No')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('Adjust Date') :</b> <span
                                                    class="text-danger">*</span> </label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control datepicker changeable"
                                                        value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" id="datepicker" autocomplete="off">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Type') :</b> <span
                                                    class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Normal: like Leakage, Damage etc. Abnormal: like Fire, Accident, stolen etc." class="fas fa-info-circle tp"></i></label>
                                                <div class="col-8">
                                                    <select name="type" class="form-control add_input">
                                                        <option value="">@lang('Select Type')</option>
                                                        <option value="1">@lang('Normal')</option>
                                                        <option value="2">@lang('Abnormal')</option>
                                                    </select>
                                                    <span class="error error_type"></span>
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
                                            <div class="col-md-12">
                                                <div class="searching_area" style="position: relative;">
                                                    <label for="inputEmail3" class="col-form-label">@lang('Item Search')</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode text-dark input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="search_product"
                                                            class="form-control scanable" autocomplete="off"
                                                            id="search_product"
                                                            placeholder="@lang('Search Product by product code(SKU) / Scan bar code')" autofocus>
                                                    </div>
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>@lang('Product')</th>
                                                                    <th></th>
                                                                    <th class="text-center">@lang('Quantity')</th>
                                                                    <th>@lang('Unit')</th>
                                                                    <th>@lang('Unit Cost').Inc.Tax</th>
                                                                    <th>@lang('SubTotal')</th>
                                                                    <th><i class="fas fa-trash-alt text-danger"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="product_list"></tbody>
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
                                                    <div class="input-group mt-1">
                                                        <div class="input-group">
                                                            <label for="inputEmail3" class="col-4"><b>@lang('Total Item') :</b></label>
                                                            <div class="col-8">
                                                                <input readonly type="number" step="any" name="total_item" class="form-control"
                                                                    id="total_item" value="0.00">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group">
                                                            <label for="inputEmail3" class=" col-4"><b>@lang('Net Total Amount') :</b> </label>
                                                            <div class="col-8">
                                                                <input readonly type="number" class="form-control" step="any" step="any"
                                                                    name="net_total_amount" id="net_total_amount" value="0.00">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label for="inputEmail3" class=" col-4"><b>@lang('Reason') :</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="reason" class="form-control"
                                                                autocomplete="off" placeholder="@lang('Reason')">
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
                                                        <label class="col-4"><b>@lang('Recovered Amount') : </b> <strong>>></strong></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="total_recovered_amount"
                                                                id="total_recovered_amount" class="form-control" value="0.00">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Payment Method') : <span
                                                            class="text-danger">*</span></b> </label>
                                                        <div class="col-8">
                                                            <select name="payment_method_id" class="form-control" id="payment_method_id">
                                                                @foreach ($methods as $method)
                                                                    <option value="{{ $method->id }}">
                                                                        {{ $method->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_payment_method_id"></span>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Debit A/C') : <span
                                                            class="text-danger">*</span></b> </label>
                                                        <div class="col-8">
                                                            <select name="account_id" class="form-control" id="account_id" data-name="Debit A/C">
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
                                                            <span class="error error_account_id"></span>
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
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button name="save" value="save" class="btn btn-sm btn-success submit_button float-end">@lang('Save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Add Product Modal End-->

@endsection
@push('scripts')
    <script src="/assets/plugins/custom/select_li/selectli.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
         // Calculate total amount functionalitie
         function calculateTotalAmount(){
            var quantities = document.querySelectorAll('#quantity');
            var subtotals = document.querySelectorAll('#subtotal');

            // Update Total Item
            var total_item = 0;
            quantities.forEach(function(qty){

                total_item += 1;
            });

            $('#total_item').val(parseFloat(total_item));

            // Update Net total Amount
            var netTotalAmount = 0;
            subtotals.forEach(function(subtotal){

                netTotalAmount += parseFloat(subtotal.value);
            });

             $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));
         }

        // add purchase product by searching product code
        $('#search_product').on('input', function(e){

            $('.variant_list_area').empty();
            $('.select_area').hide();
            var keyword = $(this).val();
            var __keyword = keyword.replaceAll('/', '~');

            $.ajax({
                url:"{{url('stock/adjustments/search/product')}}"+"/"+__keyword,
                dataType: 'json',
                success:function(product){

                     if(!$.isEmptyObject(product.errorMsg)){

                        toastr.error(product.errorMsg);
                        $('#search_product').val("");
                        return;
                     }

                     var qty_limit = product.qty_limit;

                     if(
                         !$.isEmptyObject(product.product) ||
                         !$.isEmptyObject(product.variant_product) ||
                         !$.isEmptyObject(product.namedProducts)
                    ){

                        $('#search_product').addClass('is-valid');

                        if(!$.isEmptyObject(product.product)){

                            var product = product.product;

                            if(product.product_variants.length == 0){

                                $('.select_area').hide();
                                $('#search_product').val('');
                                product_ids = document.querySelectorAll('#product_id');
                                var sameProduct = 0;

                                product_ids.forEach(function(input){

                                    if(input.value == product.id){

                                        sameProduct += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.'+className).closest('tr');
                                        var presentQty = closestTr.find('#quantity').val();
                                        var qty_limit = closestTr.find('#qty_limit').val();

                                        if(parseFloat(qty_limit) == parseFloat(presentQty)){

                                            toastr.error('Quantity Limit is - '+qty_limit+' '+product.unit.name);
                                            return;
                                        }

                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                        //Update Subtotal
                                        var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                                        var calcSubtotal = parseFloat(unitCostIncTax) * parseFloat(updateQty);
                                        closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                        closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                        calculateTotalAmount();
                                        return;
                                    }
                                });

                                if(sameProduct == 0){

                                    var tr = '';
                                    tr += '<tr>';
                                    tr += '<td colspan="2" class="text">';
                                    tr += '<a href="#" class="text-success" id="edit_product">';
                                    tr += '<span class="product_name">'+product.name+'</span>';
                                    tr += '<span class="product_variant"></span>';

                                    tr += '<span class="product_code">'+'('+product.product_code+')'+'</span>';
                                    tr += '</a>';
                                    tr += '<input value="'+product.id+'" type="hidden" class="productId-'+product.product_id+'" id="product_id" name="product_ids[]">';

                                    tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';

                                    tr += '<input readonly name="unit_costs_inc_tax[]" type="hidden"  id="unit_cost_inc_tax" value="'+product.product_cost_with_tax+'">';
                                    tr += '<input type="hidden" id="previous_quantity" value="0.00">';
                                    tr += '<input type="hidden" id="qty_limit" value="'+qty_limit+'">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<div class="input-group">';
                                    tr += '<div class="input-group-prepend">';
                                    tr += '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                                    tr += '</div>';
                                    tr += '<input value="1" required name="quantities[]" type="text" class="form-control text-center" id="quantity">';
                                    tr += '<div class="input-group-prepend">';
                                    tr += '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                                    tr += '</div>';
                                    tr += '</div>';
                                    tr += '</td>';
                                    tr += '<td class="text">';
                                    tr += '<span class="span_unit">'+product.unit.name+'</span>';
                                    tr += '<input  name="units[]" type="hidden" id="unit" value="'+product.unit.name+'">';
                                    tr += '</td>';

                                    tr += '<td class="text">';
                                    tr += '<strong><span class="span_unit_cost_inc_tax">'+product.product_cost_with_tax+'</span></strong>';
                                    tr += '</td>';

                                    tr += '<td class="text text-center">';
                                    tr += '<strong><span class="span_subtotal">'+product.product_cost_with_tax+'</span></strong>';
                                    tr += '<input value="'+product.product_cost_with_tax+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                                    tr += '</td>';

                                    tr += '<td class="text-start">';
                                    tr += '<a href="" id="remove_product_btn"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                    tr += '</td>';
                                    tr += '</tr>';
                                    $('#product_list').prepend(tr);
                                    calculateTotalAmount();
                                }
                            } else {

                                var li = "";
                                var imgUrl = "{{asset('uploads/product/thumbnail')}}";

                                $.each(product.product_variants, function(key, variant){

                                     li += '<li class="mt-1">';
                                     li += '<a class="select_variant_product" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-unit="'+product.unit.name+'" data-v_code="'+variant.variant_code+'" data-v_cost_inc_tax="'+variant.variant_cost_with_tax+'" data-v_name="'+variant.variant_name+'" href="#"><img style="width:25px; height:25px;"src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' ('+product.product_code+')'+' - Unit Cost: '+product.product_cost_with_tax+'</a>';
                                     li +='</li>';
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        }else if(!$.isEmptyObject(product.variant_product)){

                            $('.select_area').hide();
                            $('#search_product').val('');
                            var variant_product = product.variant_product;

                            var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.percent : 0;

                            var tax_rate = parseFloat(variant_product.product.tax != null ? variant_product.variant_price/100 * tax_percent : 0);

                            var variant_ids = document.querySelectorAll('#variant_id');
                            var sameVariant = 0;

                            variant_ids.forEach(function(input){

                                if(input.value != 'noid'){

                                    if(input.value == variant_product.id){

                                        sameVariant += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.'+className).closest('tr');
                                        var presentQty = closestTr.find('#quantity').val();
                                        var qty_limit = closestTr.find('#qty_limit').val();

                                        if(parseFloat(qty_limit) == parseFloat(presentQty)){

                                            toastr.error('Quantity Limit is - '+qty_limit+' '+variant_product.product.unit.name);
                                            return;
                                        }

                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                         //Update Subtotal
                                        var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                                        var calcSubtotal = parseFloat(unitCostIncTax) * parseFloat(updateQty);
                                        closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                        closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                        calculateTotalAmount();
                                        return;
                                    }
                                }
                             });

                            if(sameVariant == 0){

                                var tr = '';
                                tr += '<tr>';
                                tr += '<td colspan="2">';
                                tr += '<a href="#" class="text-success" id="edit_product">';
                                tr += '<span class="product_name">'+variant_product.product.name+'</span>';
                                tr += '<span class="product_variant">'+' -'+variant_product.variant_name+'- '+'</span>';
                                tr += '<span class="product_code">'+'('+variant_product.variant_code+')'+'</span>';
                                tr += '</a>';
                                tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                                tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                                tr += '<input readonly name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax" value="'+parseFloat(variant_product.variant_cost_with_tax).toFixed(2) +'">';
                                tr += '<input type="hidden" id="qty_limit" value="'+qty_limit+'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<div class="input-group">';
                                tr += '<div class="input-group-prepend">';
                                tr += '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                                tr += '</div>';
                                tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                                tr += '<div class="input-group-prepend">';
                                tr += '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                                tr += '</div>';
                                tr += '</div>';
                                tr += '</td>';
                                tr += '<td class="text text-center">';
                                tr += '<span class="span_unit">'+variant_product.product.unit.name+'</span>';
                                tr += '<input  name="units[]" type="hidden" id="unit" value="'+variant_product.product.unit.name+'">';
                                tr += '</td>';

                                tr += '<td class="text">';
                                tr += '<strong><span class="span_unit_cost_inc_tax">'+parseFloat(variant_product.variant_cost_with_tax).toFixed(2)+'</span></strong>';
                                tr += '</td>';

                                tr += '<td class="text text-center">';
                                tr += '<strong><span class="span_subtotal">'+parseFloat(variant_product.variant_cost_with_tax).toFixed(2)+'</span></strong>';
                                tr += '<input value="'+parseFloat(variant_product.variant_cost_with_tax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                                tr += '</td>';
                                tr += '<td class="text-start">';
                                tr += '<a href="" id="remove_product_btn"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                tr += '</td>';
                                tr += '</tr>';
                                $('#product_list').append(tr);
                                calculateTotalAmount();
                            }

                        }else if (!$.isEmptyObject(product.namedProducts)) {

                            if(product.namedProducts.length > 0){

                                var li = "";
                                var imgUrl = "{{asset('uploads/product/thumbnail')}}";
                                var products = product.namedProducts;

                                $.each(products, function (key, product) {

                                    if (product.is_variant == 1) {

                                        li += '<li class="mt-1">';
                                        li += '<a class="select_variant_product" data-p_id="'+product.id+'" data-v_id="'+product.variant_id+'" data-p_name="'+product.name+'" data-unit="' + product.unit_name +'" data-v_code="'+variant.variant_code+'" data-v_cost_inc_tax="'+product.variant_cost_with_tax+'" data-v_name="'+product.variant_name+'" href="#"><img style="width:25px; height:25px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+product.variant_name+' ('+product.variant_code+')'+' - Unit Cost: '+variant.variant_cost_with_tax+'</a>';
                                        li +='</li>';

                                    }else{

                                        li += '<li class="mt-1">';
                                        li += '<a class="select_single_product" data-p_id="'+product.id+'" data-p_name="'+product.name+'" data-unit="'+product.unit_name+'" data-p_code="'+product.product_code+'" data-p_cost_inc_tax="'+product.product_cost_with_tax+'" href="#"><img style="width:25px; height:25px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' ('+product.product_code+')'+' - Unit Cost: '+product.product_cost_with_tax+'</a>';
                                        li +='</li>';
                                    }
                                });

                                 $('.variant_list_area').html(li);
                                 $('.select_area').show();
                            }
                         }
                     }else{

                         $('#search_product').addClass('is-invalid');
                     }
                }
            });
        });

         // select single product and add stock adjustment table
         $(document).on('click', '.select_single_product', function(e){
             e.preventDefault();

            $('.select_area').hide();
            var product_id = $(this).data('p_id');
            var product_name = $(this).data('p_name');
            var product_code = $(this).data('p_code');
            var product_unit = $(this).data('unit');
            var product_cost_inc_tax = $(this).data('p_cost_inc_tax');
            $('#search_product').val('');

            $.ajax({
                url:"{{url('stock/adjustments/check/single/product/stock')}}"+"/"+product_id,
                 type:'get',
                 dataType: 'json',
                 success:function(singleProductQty){

                     if($.isEmptyObject(singleProductQty.errorMsg)){

                         var product_ids = document.querySelectorAll('#product_id');
                         var sameProduct = 0;
                         product_ids.forEach(function(input){

                             if(input.value == product_id){

                                sameProduct += 1;
                                var className = input.getAttribute('class');
                                // get closest table row for increasing qty and re calculate product amount
                                var closestTr = $('.'+className).closest('tr');
                                var presentQty = closestTr.find('#quantity').val();
                                var qty_limit = closestTr.find('#qty_limit').val();

                                 if(parseFloat(qty_limit)  === parseFloat(presentQty)){

                                     toastr.error('Quantity Limit is - '+qty_limit+' '+product_unit);
                                     return;
                                 }

                                 var updateQty = parseFloat(presentQty) + 1;
                                 closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                 //Update Subtotal
                                 var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                                 var calcSubtotal = parseFloat(unitCostIncTax) * parseFloat(updateQty);
                                 closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                 closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                 calculateTotalAmount();
                                 return;
                             }
                         });

                         if(sameProduct == 0){

                             var tr = '';
                             tr += '<tr>';
                             tr += '<td colspan="2">';
                             tr += '<a href="#" class="text-success" id="edit_product">';
                             tr += '<span class="product_name">'+product_name+'</span>';
                             tr += '<span class="product_variant"></span>';
                             tr += '<span class="product_code">'+' ('+product_code+')'+'</span>';
                             tr += '</a>';
                             tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                             tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                             tr += '<input readonly name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax" value="'+parseFloat(product_cost_inc_tax).toFixed(2) +'">';
                             tr += '<input type="hidden" id="qty_limit" value="'+singleProductQty+'">';
                             tr += '</td>';

                             tr += '<td>';
                             tr += '<div class="input-group">';
                             tr += '<div class="input-group-prepend">';
                             tr += '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                             tr += '</div>';
                             tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                             tr += '<div class="input-group-prepend">';
                             tr += '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                             tr += '</div>';
                             tr += '</div>';
                             tr += '</td>';
                             tr += '<td class="text text-center">';
                             tr += '<span class="span_unit">'+product_unit+'</span>';
                             tr += '<input  name="units[]" type="hidden" id="unit" value="'+product_unit+'">';
                             tr += '</td>';

                             tr += '<td class="text">';
                             tr += '<strong><span class="span_unit_cost_inc_tax">'+parseFloat(product_cost_inc_tax).toFixed(2)+'</span></strong>';
                             tr += '</td>';

                             tr += '<td class="text">';
                             tr += '<strong><span class="span_subtotal">'+parseFloat(product_cost_inc_tax).toFixed(2)+'</span></strong>';
                             tr += '<input value="'+parseFloat(product_cost_inc_tax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                             tr += '</td>';
                             tr += '<td class="text-start">';
                             tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                             tr += '</td>';
                             tr += '</tr>';
                             $('#product_list').prepend(tr);
                             calculateTotalAmount();
                         }
                     }else{

                         toastr.error(singleProductQty.errorMsg);
                     }
                 }
             });
         });

         // select variant product and add stock adjustment table
         $(document).on('click', '.select_variant_product', function(e){
             e.preventDefault();
             $('.select_area').hide();
             $('#search_product').val('');
             var product_id = $(this).data('p_id');
             var product_name = $(this).data('p_name');
             var product_unit = $(this).data('unit');
             var variant_id = $(this).data('v_id');
             var variant_name = $(this).data('v_name');
             var variant_code = $(this).data('v_code');
             var variant_cost_inc_tax = $(this).data('v_cost_inc_tax');

             $.ajax({
                url:"{{url('stock/adjustments/check/variant/product/stock/')}}"+"/"+product_id+"/"+variant_id,
                 type:'get',
                 dataType: 'json',
                 success:function(branchVariantQty){
                     if($.isEmptyObject(branchVariantQty.errorMsg)){
                         var variant_ids = document.querySelectorAll('#variant_id');
                         var sameVariant = 0;
                         variant_ids.forEach(function(input){

                             if(input.value != 'noid'){

                                 if(input.value == variant_id){

                                     sameVariant += 1;
                                     var className = input.getAttribute('class');
                                     // get closest table row for increasing qty and re calculate product amount
                                     var closestTr = $('.'+className).closest('tr');
                                     var presentQty = closestTr.find('#quantity').val();
                                     var qty_limit = closestTr.find('#qty_limit').val();

                                     if(parseFloat(qty_limit)  === parseFloat(presentQty)){
                                         toastr.error('Quantity Limit is - '+qty_limit+' '+product_unit);
                                         return;
                                     }
                                     var updateQty = parseFloat(presentQty) + 1;
                                     closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                     //Update Subtotal
                                     var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                                     var calcSubtotal = parseFloat(unitCostIncTax) * parseFloat(updateQty);
                                     closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                     closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                     calculateTotalAmount();
                                     return;
                                 }
                             }
                         });

                         if(sameVariant == 0){

                             var tr = '';
                             tr += '<tr>';
                             tr += '<td colspan="2">';
                             tr += '<a href="#" class="text-success" id="edit_product">';
                             tr += '<span class="product_name">'+product_name+'</span>';
                             tr += '<span class="product_variant">'+' -'+variant_name+'- '+'</span>';
                             tr += '<span class="product_code">'+'('+variant_code+')'+'</span>';
                             tr += '</a>';
                             tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                             tr += '<input value="'+variant_id+'" type="hidden" class="variantId-'+variant_id+'" id="variant_id" name="variant_ids[]">';
                             tr += '<input readonly name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax" value="'+parseFloat(variant_cost_inc_tax).toFixed(2) +'">';
                             tr += '<input type="hidden" id="qty_limit" value="'+branchVariantQty+'">';
                             tr += '</td>';

                             tr += '<td>';
                             tr += '<div class="input-group">';
                             tr += '<div class="input-group-prepend">';
                             tr += '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                             tr += '</div>';
                             tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                             tr += '<div class="input-group-prepend">';
                             tr += '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                             tr += '</div>';
                             tr += '</div>';
                             tr += '</td>';
                             tr += '<td class="text text-center">';
                             tr += '<span class="span_unit">'+product_unit+'</span>';
                             tr += '<input  name="units[]" type="hidden" id="unit" value="'+product_unit+'">';
                             tr += '</td>';

                             tr += '<td class="text">';
                             tr += '<strong><span class="span_unit_cost_inc_tax">'+parseFloat(variant_cost_inc_tax).toFixed(2)+'</span></strong>';
                             tr += '</td>';

                             tr += '<td class="text text-center">';
                             tr += '<strong><span class="span_subtotal">'+parseFloat(variant_cost_inc_tax).toFixed(2)+'</span></strong>';
                             tr += '<input value="'+parseFloat(variant_cost_inc_tax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                             tr += '</td>';

                             tr += '<td class="text-start">';
                             tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                             tr += '</td>';
                             tr += '</tr>';
                             $('#product_list').prepend(tr);
                             calculateTotalAmount();
                         }
                     }else{

                         toastr.error(branchVariantQty.errorMsg);
                     }
                 }
             });
         });

         // Quantity increase or dicrease and clculate row amount
         $(document).on('input', '#quantity', function(){

             var qty = $(this).val() ? $(this).val() : 0;

             if (parseFloat(qty) >= 0) {

                 var tr = $(this).closest('tr');
                 var qty_limit = tr.find('#qty_limit').val();
                 var unit = tr.find('#unit').val();

                 if(parseInt(qty) > parseInt(qty_limit)){

                     toastr.error('Quantity Limit Is - '+qty_limit+' '+unit);
                     $(this).val(qty_limit);
                     var unitCostIncTax = tr.find('#unit_cost_inc_tax').val();
                     var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                     tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                     tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                     calculateTotalAmount();
                     return;
                 }

                 var unitCostIncTax = tr.find('#unit_cost_inc_tax').val();
                 var calcSubtotal = parseFloat(unitCostIncTax) * parseFloat(qty);
                 tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                 tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                 calculateTotalAmount();
             }
         });

         $(document).on('blur', '#quantity', function(){

             var qty = $(this).val() ? $(this).val() : 0;

             if (parseFloat(qty) >= 0) {

                 var tr = $(this).closest('tr');
                 var qty_limit = tr.find('#qty_limit').val();
                 var unit = tr.find('#unit').val();

                 if(parseInt(qty) > parseInt(qty_limit)){

                     toastr.error('Quantity Limit Is - '+qty_limit+' '+unit);
                     $(this).val(qty_limit);
                     var unitCostIncTax = tr.find('#unit_cost_inc_tax').val();
                     var calcSubtotal = parseFloat(unitCostIncTax) * parseFloat(qty_limit);
                     tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                     tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                     calculateTotalAmount();
                     return;
                 }

                 var unitCostIncTax = tr.find('#unit_cost_inc_tax').val();
                 var calcSubtotal = parseFloat(unitCostIncTax) * parseFloat(qty);
                 tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                 tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                 calculateTotalAmount();
             }
         });

         // Dispose Select area
         $(document).on('click', '.remove_select_area_btn', function(e){
             e.preventDefault();

             $('.select_area').hide();
         });

         // Remove product form purchase product list (Table)
         $(document).on('click', '#remove_product_btn',function(e){
             e.preventDefault();

             $(this).closest('tr').remove();
             calculateTotalAmount();
         });

         //Add purchase request by ajax
         $('#add_adjustment_form').on('submit', function(e){
             e.preventDefault();

             var totalItem = $('#total_item').val();

             if (parseFloat(totalItem) == 0) {

                 toastr.error('Product table is empty.','Some thing went wrong.');
                 return;
             }

             $('.loading_button').show();
             var url = $(this).attr('action');
             var request = $(this).serialize();
             $('.submit_button').prop('type', 'button');

             $.ajax({
                 url:url,
                 type:'post',
                 data: request,
                 success:function(data){

                    $('.submit_button').prop('type', 'submit');

                     if (!$.isEmptyObject(data.errorMsg)) {

                         toastr.error(data.errorMsg);
                     }else{

                         toastr.success(data);
                         $('.loading_button').hide();
                         window.location = "{{ route('stock.adjustments.index') }}";
                     }
                 },error: function(err) {

                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }

                    toastr.error("@lang('Please check again all form fields.')",
                    "@lang('Something went wrong.')");

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
             });
         });

         // Decrease qty
         $(document).on('click', '.decrease_qty_btn', function (e) {
            e.preventDefault();

            var tr = $(this).closest('tr');
            var presentQty = tr.find('#quantity').val();
            var updateQty = parseFloat(presentQty) - 1;
            tr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
            tr.find('#quantity').addClass('.form-control:focus');
            tr.find('#quantity').blur();
         });

         // Iecrease qty
         $(document).on('click', '.increase_qty_btn', function (e) {
            e.preventDefault();

            var tr = $(this).closest('tr');
            var presentQty = tr.find('#quantity').val();
            var updateQty = parseFloat(presentQty) + 1;
            tr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
            tr.find('#quantity').addClass('.form-control:focus');
            tr.find('#quantity').blur();
         });

         // Automatic remove searching product is found signal
         setInterval(function(){

             $('#search_product').removeClass('is-invalid');
         }, 500);

         setInterval(function(){

             $('#search_product').removeClass('is-valid');
         }, 1000);

        $('body').keyup(function(e){

            if (e.keyCode == 13 || e.keyCode == 9){

                $(".selectProduct").click();
                $('#list').empty();
            }
        });

        $(document).keypress(".scanable",function(event){

            if (event.which == '10' || event.which == '13') {

                event.preventDefault();
            }
        });

        $(document).on('mouseenter', '#list>li>a',function () {

            $('#list>li>a').removeClass('selectProduct');
            $(this).addClass('selectProduct');
        });

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
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
     </script>
@endpush
