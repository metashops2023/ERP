@extends('layout.pos_edit_master')
@section('pos_content')
<!-- Pos Header -->
@include('sales.pos.partial.pos_edit_header')
<!-- Pos Header End-->
<div class="body-wraper">
    <div class="container-fluid">
        <div class="pos-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <!-- Select Category, Brand and Product Area -->
                        @include('sales.pos.partial.select_edit_product_section')
                        <!-- Select Category, Brand and Product Area -->
                        <div class="col-lg-7 p-1 pb-0" >
                            <div class="cart-table">
                                <div class="cart-table-inner-pos">
                                    <div class="tbl-head">
                                        <ul id="pos-shortcut-menus">

                                        </ul>
                                    </div>
                                    <!-- Sale Product Table -->
                                    @include('sales.pos.partial.sale_edit_product_table')
                                    <!-- Sale Product Table End -->

                                    <!-- Total Item & Qty section -->
                                    @include('sales.pos.partial.total_edit_item_and_qty')
                                    <!-- Total Item & Qty section End-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pos Footer -->
                    @include('sales.pos.partial.pos_edit_footer')
                    <!-- Pos Footer End -->
                </div>

                <!-- Pos Total Sum And Buttons section -->
                @include('sales.pos.partial.total_edit_sum_and_butten')
                <!-- Pos Total Sum And Buttons section End -->
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    var unique_index = 0;
    var delay = (function() {
        var timer = 0;
        return function(callback, ms) {
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('#search_product').on('input', function(e) {
        $('.select_area').hide();
        $('.variant_list_area').empty();
        var product_code = $(this).val() ? $(this).val() : 'no_key_word';
        var __product_code = product_code.replaceAll('/', '~');
        var warehouse_id = 'NULL';
        var status = 'no_status';
        var __price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';
        delay(function() { searchProduct(status, __product_code, __price_group_id, warehouse_id); }, 200)//sendAjaxical is the name of remote-command
    });

    function searchProduct(status, product_code, __price_group_id, warehouse_id) {

        var price_group_id = $('#price_group_id').val();

        $.ajax({

            url:"{{ url('sales/search/product') }}" + "/"+ status + "/" + product_code + "/" + __price_group_id + "/" + warehouse_id,
            dataType: 'json',
            success: function(product) {

                if(!$.isEmptyObject(product.errorMsg || product_code == '')){

                    toastr.error(product.errorMsg);
                    $('#search_product').val("");
                    $('.select_area').hide();
                    return;
                }

                var qty_limit = product.qty_limit;
                var discount = product.discount;

                if (
                    !$.isEmptyObject(product.product) ||
                    !$.isEmptyObject(product.variant_product) ||
                    !$.isEmptyObject(product.namedProducts)
                ) {

                    $('#search_product').addClass('is-valid');

                    if (!$.isEmptyObject(product.product)) {

                        $('#search_product').val('');
                        $('.select_area').hide();
                        var product = product.product;
                        if (product.product_variants.length == 0) {

                            if (product.is_manage_stock == 1) {

                                $('#stock_quantity').val(qty_limit);
                            }

                            $('.select_area').hide();
                            $('#search_product').val('');
                            product_ids = document.querySelectorAll('#product_id');
                            var sameProduct = 0;
                            product_ids.forEach(function(input) {

                                if (input.value == product.id) {

                                    sameProduct += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.' + className).closest('tr');
                                    var presentQty = closestTr.find('#quantity').val();
                                    var previousQty = closestTr.find('#previous_qty').val();
                                    var limit = closestTr.find('#qty_limit').val()
                                    var qty_limit = parseFloat(previousQty) + parseFloat(limit);

                                    if (parseFloat(qty_limit) == parseFloat(presentQty)) {

                                        toastr.error('Quantity Limit is - ' + qty_limit + ' ' + product.unit.name);
                                        return;
                                    }

                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                    //Update Subtotal
                                    var unitPrice = closestTr.find('#unit_price_inc_tax').val();
                                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    return;
                                }
                            });

                            if (sameProduct == 0) {

                                var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0;
                                var price = 0;

                                var __price = price_groups.filter(function (value) {

                                    return value.price_group_id == price_group_id && value.product_id == product.id;
                                });

                                if (__price.length != 0) {

                                    price = __price[0].price ? __price[0].price : product.product_price;
                                } else {

                                    price = product.product_price;
                                }

                                var discount_amount = 0;
                                if (discount.discount_type == 1) {

                                    discount_amount = discount.discount_amount
                                }else{

                                    discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                                }

                                var __price_with_discount = parseFloat(price) - parseFloat(discount_amount);

                                var tax_amount = parseFloat(__price_with_discount / 100 * tax_percent);
                                var unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);

                                if (product.tax_type == 2) {

                                    var inclusiveTax = 100 + parseFloat(tax_percent)
                                    var calcAmount = parseFloat(__price_with_discount) / parseFloat(inclusiveTax) * 100;
                                    tax_amount = parseFloat(__price_with_discount) - parseFloat(calcAmount);
                                    unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);
                                }

                                var tr = '';
                                tr += '<tr>';
                                tr += '<td class="serial">1</td>';

                                tr += '<td class="text-start">';
                                tr += '<a class="product-name text-info" id="edit_product" title="'+product.product_code+'" href="#">' +
                                    product.name + '</a><br/><input type="'+(product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control description_input scanable" placeholder="@lang('IMEI, Serial number or other info.')">';
                                tr += '<input value="' + product.id +'" type="hidden" class="productId-' + product.id +
                                    '" id = "product_id" name="product_ids[]">';
                                tr +='<input input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                                tr +='<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="' +
                                    tax_percent + '">';
                                tr +='<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="' +
                                    parseFloat(tax_amount).toFixed(2) + '">';
                                tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                                tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                                tr += '<input value="'+ discount_amount +'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                                tr += '<input value="' + product.product_cost_with_tax +'" name="unit_costs_inc_tax[]" type="hidden" id="unit_costs_inc_tax">';
                                tr += '<input type="hidden" id="previous_qty" value="0.00">';
                                tr += '<input type="hidden" id="qty_limit" value="' + qty_limit + '">';
                                tr += '<input class="index-' + unique_index +'" type="hidden" id="index">';
                                tr += '</td>';

                                tr += '<td>';
                                tr +='<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<b><span class="span_unit">' + product.unit.name +
                                    '</span></b>';
                                tr += '<input name="units[]" type="hidden" id="unit" value="' +
                                    product.unit.name +'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr +='<input name="unit_prices_exc_tax[]" type="hidden" value="'+ parseFloat(price).toFixed(2) +'" id="unit_price_exc_tax">';
                                tr +='<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="'+parseFloat(unitPriceIncTax).toFixed(2) +'">';
                                tr += '<b><span class="span_unit_price_inc_tax">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span> </b>';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="' + parseFloat(unitPriceIncTax).toFixed(
                                    2) + '" name="subtotals[]" type="hidden" id="subtotal">';
                                tr += '<b><span class="span_subtotal">' + parseFloat(
                                    unitPriceIncTax).toFixed(2) + '</span></b>';
                                tr += '</td>';

                                tr +='<td><a href="#" class="action-btn c-delete" id="remove_product_btn"><span class="fas fa-trash "></span></a></td>';
                                tr += '</tr>';

                                $('#product_list').prepend(tr);
                                calculateTotalAmount();
                                unique_index++;
                            }
                        } else {
                            var li = "";
                            var imgUrl = "{{ asset('uploads/product/thumbnail') }}";
                            var tax_percent = product.tax_id != null ? product.tax.tax_percent :
                                0.00;
                            $.each(product.product_variants, function(key, variant) {

                                var price = 0;
                                var __price = price_groups.filter(function (value) {

                                    return value.price_group_id == price_group_id && value.product_id == product.id && value.variant_id == variant.id;
                                });

                                if (__price.length != 0) {

                                    price = __price[0].price ? __price[0].price : variant.variant_price;
                                } else {

                                    price = variant.variant_price;
                                }

                                var tax_amount = parseFloat(price / 100 * tax_percent);
                                var unitPriceIncTax = (parseFloat(price) / 100 * tax_percent) + parseFloat(price);

                                if (product.tax_type == 2) {

                                    var inclusiveTax = 100 + parseFloat(tax_percent);
                                    var calcTax = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                    var __tax_amount = parseFloat(price) - parseFloat(calcTax);
                                    unitPriceIncTax = parseFloat(price) + parseFloat(__tax_amount);
                                    tax_amount = __tax_amount;
                                }

                                li += '<li class="mt-1">';
                                li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + variant.id +'" data-p_name="'+ product.name +'" data-p_tax_id="'+ product.tax_id +'" data-unit="'+ product.unit.name +'" data-tax_percent="'+ tax_percent +'" data-tax_type="'+ product.tax_type +'" data-tax_amount="' + tax_amount +'" data-v_code="'+ variant.variant_code +'" data-v_price="'+ variant.variant_price +'" data-v_name="'+ variant.variant_name +'" data-v_cost_inc_tax="'+ variant.variant_cost_with_tax +'" href="#">'+ product.name + ' - ' + variant.variant_name + ' - Price: ' +parseFloat(unitPriceIncTax).toFixed(2) + '</a>';
                                li += '</li>';
                            });
                            $('.variant_list_area').prepend(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }

                    } else if (!$.isEmptyObject(product.variant_product)) {

                        if (product.is_manage_stock == 1) {

                            $('#stock_quantity').val(qty_limit);
                        }

                        $('#search_product').val('');
                        $('.select_area').hide();
                        var variant_product = product.variant_product;
                        var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.tax_percent : 0;
                        var variant_ids = document.querySelectorAll('#variant_id');
                        var sameVariant = 0;
                        variant_ids.forEach(function(input) {

                            if (input.value != 'noid') {

                                if (input.value == variant_product.id) {

                                    sameVariant += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.' + className).closest('tr');
                                    var presentQty = closestTr.find('#quantity').val();
                                    var previousQty = closestTr.find('#previous_qty').val();
                                    var limit = closestTr.find('#qty_limit').val()
                                    var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                                    if (parseFloat(qty_limit) == parseFloat(presentQty)) {
                                        toastr.error('Quantity Limit is - ' + qty_limit + ' ' + variant_product.product.unit.name);
                                        return;
                                    }
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                    //Update Subtotal
                                    var unitPrice = closestTr.find('#unit_price_inc_tax').val();
                                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    return;
                                }
                            }
                        });

                        if (sameVariant == 0) {

                            var price = 0;
                            var __price = price_groups.filter(function (value) {

                                return value.price_group_id == price_group_id && value.product_id == variant_product.product.id && value.variant_id == variant_product.id;
                            });

                            if (__price.length != 0) {

                                price = __price[0].price ? __price[0].price : variant_product.variant_price;
                            } else {

                                price = variant_product.variant_price;
                            }

                            var discount_amount = 0;
                            if (discount.discount_type == 1) {

                                discount_amount = discount.discount_amount
                            }else{

                                discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                            }

                            var __price_with_discount = parseFloat(price) - parseFloat(discount_amount)

                            var tax_amount = parseFloat(__price_with_discount / 100 * tax_percent);
                            var unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);

                            if (variant_product.product.tax_type == 2) {

                                var inclusiveTax = 100 + parseFloat(tax_percent)
                                var calcAmount = parseFloat(__price_with_discount) / parseFloat(inclusiveTax) * 100;
                                tax_amount = parseFloat(__price_with_discount) - parseFloat(calcAmount);
                                unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);
                            }

                            var tr = '';
                            tr += '<tr>';
                            tr += '<td class="serial">1</td>';
                            tr += '<td class="text-start">';
                            tr += '<a class="product-name text-info" id="edit_product" title="'+variant_product.variant_code+'" href="#">'+ variant_product.product.name + ' - ' + variant_product.variant_name +'</a><input type="'+ (variant_product.product.is_show_emi_on_pos == 1 ? 'text' : 'hidden') +'" name="descriptions[]" class="form-control description_input scanable" placeholder="@lang('IMEI, Serial number or other info.')">';
                            tr += '<input value="'+ variant_product.product.id +'" type="hidden" class="productId-'+ variant_product.product.id +'" id = "product_id" name="product_ids[]">';
                            tr += '<input input value="' + variant_product.id +'" type="hidden" class="variantId-'+ variant_product.id +'" id="variant_id" name="variant_ids[]">';
                            tr +='<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+ tax_percent +'">';
                            tr +='<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+ parseFloat(tax_amount).toFixed(2) +'">';
                            tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                            tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                            tr += '<input value="'+ discount_amount +'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                            tr += '<input value="'+ variant_product.variant_cost_with_tax +'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                            tr += '<input type="hidden" id="previous_qty" value="0.00">';
                            tr += '<input type="hidden" id="qty_limit" value="'+ qty_limit +'">';
                            tr += '<input class="index-'+ unique_index +'" type="hidden" id="index">';
                            tr += '</td>';

                            tr += '<td>';
                            tr +='<input value="1.00" required name="quantities[]" type="text" class="form-control text-center" id="quantity">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<b><span class="span_unit">'+ variant_product.product.unit.name +'</span></b>';
                            tr += '<input name="units[]" type="hidden" id="unit" value="'+ variant_product.product.unit.name +'">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+ parseFloat(price).toFixed(2) +'" id="unit_price_exc_tax">';
                            tr +='<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="'+ parseFloat(unitPriceIncTax).toFixed(2)+'">';
                            tr += '<b><span class="span_unit_price_inc_tax">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span> </b>';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input value="'+ parseFloat(unitPriceIncTax).toFixed(2) +'" name="subtotals[]" type="hidden" id="subtotal">';
                            tr += '<b><span class="span_subtotal">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span></b>';
                            tr += '</td>';

                            tr +='<td><a href="#" class="action-btn c-delete" id="remove_product_btn"><span class="fas fa-trash "></span></a></td>';
                            tr += '</tr>';

                            $('#product_list').prepend(tr);
                            calculateTotalAmount();
                            unique_index++;
                        }
                    } else if (!$.isEmptyObject(product.namedProducts)) {

                        $('#current_stock').val('');

                        if (product.namedProducts.length > 0) {

                            var li = "";
                            var imgUrl = "{{ asset('uploads/product/thumbnail') }}";
                            var products = product.namedProducts;

                            $.each(products, function(key, product) {

                                var tax_percent = product.tax_percent != null ? product.tax_percent : 0;

                                if (product.is_variant == 1) {

                                    var price = 0;
                                    var __price = price_groups.filter(function (value) {

                                        return value.price_group_id == price_group_id && value.product_id == product.id && value.variant_id == product.variant_id;
                                    });

                                    if (__price.length != 0) {

                                        price = __price[0].price ? __price[0].price : product.variant_price;
                                    } else {

                                        price = product.variant_price;
                                    }

                                    var tax_amount = parseFloat(price / 100 * tax_percent);
                                    var unitPriceIncTax = (parseFloat(price) / 100 * tax_percent) + parseFloat(price);

                                    if (product.tax_type == 2) {

                                        var inclusiveTax = 100 + parseFloat(tax_percent);
                                        var calcTax = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                        var __tax_amount = parseFloat(price) - parseFloat(calcTax);
                                        unitPriceIncTax = parseFloat(price) + parseFloat(__tax_amount);
                                        tax_amount = __tax_amount;
                                    }

                                    li += '<li class="mt-1">';
                                    li +='<a class="product-name s" id="'+product.id+product.variant_id+'" onclick="salectVariant(this); return false;" data-p_id="'+ product.id +'" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="'+ product.variant_id +'" data-p_name="' +product.name +'" data-p_tax_id="'+ product.tax_id +'" data-unit="' +product.unit_name +'" data-tax_percent="'+ tax_percent +'" data-tax_type="'+ product.tax_type +'" data-tax_amount="'+ tax_amount +'" data-v_code="' + product.variant_code + '" data-v_price="'+ product.variant_price +'" data-v_name="'+ product.variant_name +'" data-v_cost_inc_tax="'+ product.variant_cost_with_tax+'" href="#">'+ product.name + ' - ' + product.variant_name + ' - Price: ' + parseFloat(unitPriceIncTax).toFixed(2) +'</a>';
                                    li += '</li>';

                                } else {

                                    var price = 0;
                                    var __price = price_groups.filter(function (value) {

                                        return value.price_group_id == price_group_id && value.product_id == product.id;
                                    });

                                    if (__price.length != 0) {

                                        price = __price[0].price ? __price[0].price : product.product_price;
                                    } else {

                                        price = product.product_price;
                                    }

                                    var tax_amount = parseFloat(price / 100 * tax_percent);
                                    var unitPriceIncTax = (parseFloat(price) / 100 * tax_percent) + parseFloat(price);

                                    if (product.tax_type == 2) {

                                        var inclusiveTax = 100 + parseFloat(tax_percent);
                                        var calcTax = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                        var __tax_amount = parseFloat(price) - parseFloat(calcTax);
                                        unitPriceIncTax = parseFloat(price) + parseFloat(__tax_amount);
                                        tax_amount = __tax_amount;
                                    }

                                    li += '<li class="mt-1">';
                                    li +='<a class="product-name s" id="'+product.id+'noid'+'" onclick="singleProduct(this); return false;" data-p_id="'+ product.id +'" data-is_manage_stock="' + product.is_manage_stock + '" data-p_name="'+ product.name +'" data-unit="'+ product.unit_name + '" data-p_code="'+ product.product_code +'" data-p_price_exc_tax="'+ product.product_price +'" data-p_tax_percent="' + tax_percent +'" data-tax_type="'+ product.tax_type +'" data-p_tax_amount="'+ tax_amount +'" data-p_cost_inc_tax="'+product.product_cost_with_tax+'" data-description="'+ product.is_show_emi_on_pos +'"  href="#">' + product.name + ' - Price: ' + parseFloat(unitPriceIncTax).toFixed(2) +'</a>';
                                    li += '</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    }
                } else {

                    $('#search_product').addClass('is-invalid');
                }
            }
        });
    }

    // select single product and add stock adjustment table
    var keyName = 1;
    function singleProduct(e) {

        var warehouse_id = 'NULL';
        var price_group_id = $('#price_group_id').val();
        var __price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';
        $('.select_area').hide();
        $('#search_product').val("");
        var product_id = e.getAttribute('data-p_id');
        var is_manage_stock = e.getAttribute('data-is_manage_stock');
        var product_name = e.getAttribute('data-p_name');
        var product_code = e.getAttribute('data-p_code');
        var product_unit = e.getAttribute('data-unit');
        var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
        var product_price_exc_tax = e.getAttribute('data-p_price_exc_tax');
        var p_tax_percent = e.getAttribute('data-p_tax_percent');
        var p_tax_type = e.getAttribute('data-tax_type');
        var p_tax_amount = e.getAttribute('data-p_tax_amount');
        var description = e.getAttribute('data-description');
        var status = 'no_status';
        $('#search_product').val('');

        $.ajax({
            url:"{{ url('sales/check/single/product/stock/') }}"+"/"+ status +"/"+product_id+ "/" + __price_group_id + "/" + warehouse_id,
            async: true,
            type: 'get',
            dataType: 'json',
            success: function(data) {

                if ($.isEmptyObject(data.errorMsg)) {

                    if (is_manage_stock == 1) {

                        $('#stock_quantity').val(data.stock);
                    }

                    var discount = data.discount;

                    var product_ids = document.querySelectorAll('#product_id');
                    var sameProduct = 0;
                    product_ids.forEach(function(input) {

                        if (input.value == product_id) {

                            sameProduct += 1;
                            var className = input.getAttribute('class');
                            // get closest table row for increasing qty and re calculate product amount
                            var closestTr = $('.' + className).closest('tr');
                            var presentQty = closestTr.find('#quantity').val();
                            var previousQty = closestTr.find('#previous_qty').val();
                            var limit = closestTr.find('#qty_limit').val()
                            var qty_limit = parseFloat(previousQty) + parseFloat(limit);

                            if (parseFloat(qty_limit) == parseFloat(presentQty)) {

                                toastr.error('Quantity Limit is - ' + qty_limit + ' ' + product.unit.name);
                                return;
                            }

                            var updateQty = parseFloat(presentQty) + 1;
                            closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                            //Update Subtotal
                            var unitPrice = closestTr.find('#unit_price_inc_tax').val();
                            var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                            closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                            closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                            calculateTotalAmount();

                            if (keyName == 9) {

                                closestTr.find('#quantity').focus();
                                closestTr.find('#quantity').select();
                                keyName = 1;
                            }

                            return;
                        }
                    });

                    if (sameProduct == 0) {

                        var price = 0;
                        var __price = price_groups.filter(function (value) {

                            return value.price_group_id == price_group_id && value.product_id == product_id;
                        });

                        if (__price.length != 0) {

                            price = __price[0].price ? __price[0].price : product_price_exc_tax;
                        } else {

                            price = product_price_exc_tax;
                        }

                        var discount_amount = 0;
                        if (discount.discount_type == 1) {

                            discount_amount = discount.discount_amount
                        }else{

                            discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                        }

                        var __price_with_discount = parseFloat(price) - parseFloat(discount_amount);

                        p_tax_amount = parseFloat(__price_with_discount) / 100 * parseFloat(p_tax_percent);
                        var unitPriceIncTax = parseFloat(__price_with_discount) / 100 * parseFloat(p_tax_percent) + parseFloat(__price_with_discount);

                        if (p_tax_type == 2) {

                            var inclusiveTax = 100 + parseFloat(p_tax_percent);
                            var calcTax = parseFloat(__price_with_discount) / parseFloat(inclusiveTax) * 100;
                            var __tax_amount = parseFloat(__price_with_discount) - parseFloat(calcTax);
                            unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(__tax_amount);
                            p_tax_amount = __tax_amount;
                        }

                        var tr = '';
                        tr += '<tr>';
                        tr += '<td class="serial">1</td>';
                        tr += '<td class="text-start">';
                        tr += '<a class="product-name text-info" title="'+'SKU-'+product_code+'" id="edit_product" href="#">' + product_name +'</a><br/><input type="'+(description == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control description_input scanable" placeholder="@lang('IMEI, Serial number or other info')">';
                        tr += '<input value="' + product_id + '" type="hidden" class="productId-' +
                            product_id + '" id = "product_id" name="product_ids[]" >';
                        tr +='<input input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                        tr +='<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="' +p_tax_percent + '">';
                        tr +='<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="' +parseFloat(p_tax_amount).toFixed(2) + '">';
                        tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                        tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                        tr += '<input value="'+ discount_amount +'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                        tr += '<input value="'+ product_cost_inc_tax +'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                        tr += '<input type="hidden" id="previous_qty" value="0.00">';
                        tr += '<input type="hidden" id="qty_limit" value="' + data.stock +
                            '">';
                        tr += '<input class="index-'+ unique_index  +'" type="hidden" id="index">';
                        tr += '</td>';

                        tr += '<td>';
                        tr +='<input type="number" name="quantities[]" value="1.00" class="form-control text-center" id="quantity">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<b><span class="span_unit">'+ product_unit +'</span></b>';
                        tr += '<input name="units[]" type="hidden" id="unit" value="'+ product_unit +'">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+parseFloat(price).toFixed(2)+'" id="unit_price_exc_tax">';
                        tr +='<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="'+ parseFloat(unitPriceIncTax).toFixed(2) +'">';
                        tr += '<b><span class="span_unit_price_inc_tax">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span> </b>';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="' + parseFloat(unitPriceIncTax).toFixed(2) +'" name="subtotals[]" type="hidden" id="subtotal">';
                        tr += '<b><span class="span_subtotal">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span></b>';
                        tr += '</td>';

                        tr +='<td><a href="#" class="action-btn c-delete" id="remove_product_btn"><span class="fas fa-trash "></span></a></td>';
                        tr += '</tr>';

                        $('#product_list').prepend(tr);
                        calculateTotalAmount();

                        if (keyName == 9) {

                            $("#quantity").select();
                            keyName = 1;
                        }
                        unique_index++;
                    }
                } else {

                    toastr.error(data.errorMsg);
                }
            }
        });
    }

    // select variant product and add purchase table
    function salectVariant(e){

        var warehouse_id = 'NULL';
        var price_group_id = $('#price_group_id').val();
        var __price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';
        $('.select_area').hide();
        $('#search_product').val("");
        var product_id = e.getAttribute('data-p_id');
        var is_manage_stock = e.getAttribute('data-is_manage_stock');
        var product_name = e.getAttribute('data-p_name');
        var tax_percent = e.getAttribute('data-tax_percent');
        var product_unit = e.getAttribute('data-unit');
        var tax_id = e.getAttribute('data-p_tax_id');
        var tax_type = e.getAttribute('data-tax_type');
        var tax_amount = e.getAttribute('data-tax_amount');
        var variant_id = e.getAttribute('data-v_id');
        var variant_name = e.getAttribute('data-v_name');
        var variant_code = e.getAttribute('data-v_code');
        var variant_cost_inc_tax = e.getAttribute('data-v_cost_inc_tax');
        var variant_price = e.getAttribute('data-v_price');
        var description = e.getAttribute('data-description');
        var status = 'no_status';

        $.ajax({
            url:"{{url('sales/check/branch/variant/qty/')}}"+ "/" + status + "/" + product_id + "/" + variant_id + "/" +  __price_group_id  + "/" +  warehouse_id,
            type: 'get',
            dataType: 'json',
            success: function(data) {

                if ($.isEmptyObject(data.errorMsg)) {

                    if (is_manage_stock == 1) {

                        $('#stock_quantity').val(parseFloat(data.stock));
                    }

                    var discount = data.discount;

                    var variant_ids = document.querySelectorAll('#variant_id');
                    var sameVariant = 0;
                    variant_ids.forEach(function(input) {

                        if (input.value != 'noid') {

                            if (input.value == variant_id) {

                                sameVariant += 1;
                                var className = input.getAttribute('class');
                                // get closest table row for increasing qty and re calculate product amount
                                var closestTr = $('.' + className).closest('tr');
                                var presentQty = closestTr.find('#quantity').val();
                                var previousQty = closestTr.find('#previous_qty').val();
                                var limit = closestTr.find('#qty_limit').val()
                                var qty_limit = parseFloat(previousQty) + parseFloat(limit);

                                if (parseFloat(qty_limit) === parseFloat(presentQty)) {

                                    toastr.error('Quantity Limit is - '+qty_limit+' '+product_unit);
                                    return;
                                }

                                var updateQty = parseFloat(presentQty) + 1;

                                closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                //Update Subtotal
                                var unitPrice = closestTr.find('#unit_price_inc_tax').val();

                                var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);

                                closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));

                                calculateTotalAmount();

                                if (keyName == 9) {

                                    closestTr.find('#quantity').focus();
                                    closestTr.find('#quantity').select();
                                    keyName = 1;
                                }
                                return;
                            }
                        }
                    });

                    if (sameVariant == 0) {

                        var price = 0;
                        var __price = price_groups.filter(function (value) {

                            return value.price_group_id == price_group_id && value.product_id == product_id && value.variant_id == variant_id;
                        });

                        if (__price.length != 0) {

                            price = __price[0].price ? __price[0].price : variant_price;
                        } else {

                            price = variant_price;
                        }

                        var discount_amount = 0;
                        if (discount.discount_type == 1) {

                            discount_amount = discount.discount_amount
                        }else{

                            discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                        }

                        var __price_with_discount = parseFloat(price) - parseFloat(discount_amount);

                        tax_amount = parseFloat(__price_with_discount) / 100 * parseFloat(tax_percent);

                        var unitPriceIncTax = parseFloat(__price_with_discount) / 100 * parseFloat(tax_percent) + parseFloat(__price_with_discount);

                        if (tax_type == 2) {

                            var inclusiveTax = 100 + parseFloat(tax_percent);
                            var calcTax = parseFloat(__price_with_discount) / parseFloat(inclusiveTax) * 100;
                            var __tax_amount = parseFloat(__price_with_discount) - parseFloat(calcTax);
                            unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(__tax_amount);
                            tax_amount = __tax_amount;
                        }

                        var tr = '';
                        tr += '<tr>';
                        tr += '<td class="serial">1</td>';
                        tr += '<td class="text-start">';
                        tr += '<a class="product-name text-info" title="'+'SKU-'+variant_code+'" id="edit_product" href="#">' + product_name +' - ' + variant_name + '</a><br/><input type="'+(description == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control description_input scanable" placeholder="@lang('IMEI, Serial number or other info')">';
                        tr += '<input value="' + product_id + '" type="hidden" class="productId-'+ product_id +'" id="product_id" name="product_ids[]">';
                        tr += '<input input value="'+ variant_id +'" type="hidden" class="variantId-'+ variant_id +'" id="variant_id" name="variant_ids[]">';
                        tr +='<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent +'">';
                        tr +='<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+ parseFloat(tax_amount).toFixed(2) +'">';
                        tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                        tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                        tr += '<input value="'+ discount_amount +'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                        tr += '<input value="' + variant_cost_inc_tax +'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                        tr += '<input type="hidden" id="previous_qty" value="0.00">';
                        tr += '<input type="hidden" id="qty_limit" value="'+ data.stock +'">';
                        tr += '<input class="index-'+ unique_index +'" type="hidden" id="index">';
                        tr += '</td>';

                        tr += '<td>';
                        tr +='<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<b><span class="span_unit">' +product_unit+ '</span></b>';
                        tr += '<input name="units[]" type="hidden" id="unit" value="' +product_unit+ '">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+ parseFloat(price).toFixed(2) +'" id="unit_price_exc_tax">';
                        var unitPriceIncTax = parseFloat(price) / 100 * parseFloat(tax_percent) + parseFloat(price);
                        tr +='<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="'+ parseFloat(unitPriceIncTax).toFixed(2) +'">';
                        tr += '<b><span class="span_unit_price_inc_tax">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span></b>';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="'+ parseFloat(unitPriceIncTax).toFixed(2) +'" name="subtotals[]" type="hidden" id="subtotal">';
                        tr += '<b><span class="span_subtotal">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span></b>';
                        tr += '</td>';

                        tr +='<td><a href="#" class="action-btn c-delete" id="remove_product_btn"><span class="fas fa-trash"></span></a></td>';
                        tr += '</tr>';
                        $('#product_list').prepend(tr);
                        calculateTotalAmount();

                        if (keyName == 9) {

                            $("#quantity").select();
                            keyName = 1;
                        }

                        unique_index++;
                    }
                } else {

                    toastr.error(data.errorMsg);
                }
            }
        });
    }

    $(document).on('input', '#quantity', function(){

        var qty = $(this).val() ? $(this).val() : 0;

        if (qty < 0) {

            $(this).val(0);
        }

        if (parseFloat(qty) >= 0) {

            var tr = $(this).closest('tr');
            var qty_limit = tr.find('#qty_limit').val();
            var unit = tr.find('#unit').val();

            if(parseInt(qty) > parseInt(qty_limit)){

                toastr.error('Quantity Limit Is - '+qty_limit+' '+unit);
                $(this).val(qty_limit);
                var unitPrice = tr.find('#unit_price_inc_tax').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount();
                return;
            }

            var unitPrice = tr.find('#unit_price_inc_tax').val();
            var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty);
            tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
            calculateTotalAmount();
        }
    });

    // chane purchase tax and clculate total amount
    $(document).on('change', '#order_tax', function(){
        calculateTotalAmount();
    });

    // Input paying amount and clculate due amount
    $(document).on('input', '#paying_amount', function(){
        calculateTotalAmount();
    });

    // Input order discount and clculate total amount
    $(document).on('input', '#order_discount', function(){
        calculateTotalAmount();
    });

    // Show selling product's update modal
    var tableRowIndex = 0;
    $(document).on('click', '#edit_product', function(e) {
        e.preventDefault();
        var parentTableRow = $(this).closest('tr');
        tableRowIndex = parentTableRow.index();
        var quantity = parentTableRow.find('#quantity').val();
        var product_name = parentTableRow.find('.product-name').html();
        var product_variant = parentTableRow.find('.product_variant').html();
        var product_code = parentTableRow.find('.product-name').attr('title');
        var unit_price_exc_tax = parentTableRow.find('#unit_price_exc_tax').val();
        var unit_tax_percent = parentTableRow.find('#unit_tax_percent').val();
        var unit_tax_amount = parentTableRow.find('#unit_tax_amount').val();
        var unit_discount_type = parentTableRow.find('#unit_discount_type').val();
        var unit_discount = parentTableRow.find('#unit_discount').val();
        var unit_discount_amount = parentTableRow.find('#unit_discount_amount').val();
        var product_unit = parentTableRow.find('#unit').val();
        // Set modal heading
        var heading = product_name + ' - ' + (product_variant ? product_variant : '') + ' (' + product_code +
            ')';
        $('#product_info').html(heading);

        $('#e_quantity').val(parseFloat(quantity).toFixed(2));
        $('#e_unit_price').val(parseFloat(unit_price_exc_tax).toFixed(2));
        $('#e_unit_discount_type').val(unit_discount_type);
        $('#e_unit_discount').val(unit_discount);
        $('#e_discount_amount').val(unit_discount_amount);
        $('#e_unit_tax').empty();
        $('#e_unit_tax').append('<option value="0.00">@lang('No Tax')</option>');

        taxArray.forEach(function(tax) {

            if (tax.tax_percent == unit_tax_percent) {

                $('#e_unit_tax').append('<option SELECTED value="' + tax.tax_percent + '">' + tax
                    .tax_name + '</option>');
            } else {

                $('#e_unit_tax').append('<option value="' + tax.tax_percent + '">' + tax.tax_name +
                    '</option>');
            }
        });

        $('#e_unit').empty();

        unites.forEach(function(unit) {

            if (unit == product_unit) {

                $('#e_unit').append('<option SELECTED value="' + unit + '">' + unit + '</option>');
            } else {

                $('#e_unit').append('<option value="' + unit + '">' + unit + '</option>');
            }
        });

        $('#editProductModal').modal('show');
    });

    // Calculate unit discount
    $('#e_unit_discount').on('input', function() {

        var discountValue = $(this).val() ? $(this).val() : 0.00;

        if ($('#e_unit_discount_type').val() == 1) {

            $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
        } else {

            var unit_price = $('#e_unit_price').val();
            var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
            $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
        }
    });

    // change unit discount type var productTableRow
    $('#e_unit_discount_type').on('change', function() {

        var type = $(this).val();
        var discountValue = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;

        if (type == 1) {

            $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
        } else {

            var unit_price = $('#e_unit_price').val();
            var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
            $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
        }
    });

    //Update Selling producdt
    $(document).on('submit', '#update_selling_product', function(e) {
        e.preventDefault();
        var inputs = $('.edit_input');
        $('.error').html('');
        var countErrorField = 0;
        $.each(inputs, function(key, val) {

            var inputId = $(val).attr('id');
            var idValue = $('#' + inputId).val();

            if (idValue == '') {

                countErrorField += 1;
                var fieldName = $('#' + inputId).data('name');
                $('.error_' + inputId).html(fieldName + ' is required.');
            }
        });

        if (countErrorField > 0) {

            return;
        }

        var e_quantity = $('#e_quantity').val();
        var e_unit_price = $('#e_unit_price').val();
        var e_unit_discount_type = $('#e_unit_discount_type').val();
        var e_unit_discount = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
        var e_unit_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0.00;
        var e_unit_tax_percent = $('#e_unit_tax').val() ? $('#e_unit_tax').val() : 0.00;
        var e_unit = $('#e_unit').val();

        var productTableRow = $('#product_list tr:nth-child(' + (tableRowIndex + 1) + ')');
        // calculate unit tax
        productTableRow.find('.span_unit').html(e_unit);
        productTableRow.find('#unit').val(e_unit);
        productTableRow.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
        productTableRow.find('#unit_price_exc_tax').val(parseFloat(e_unit_price).toFixed(2));
        productTableRow.find('#unit_discount_type').val(e_unit_discount_type);
        productTableRow.find('#unit_discount').val(parseFloat(e_unit_discount).toFixed(2));
        productTableRow.find('#unit_discount_amount').val(parseFloat(e_unit_discount_amount).toFixed(2));

        var calsUninTaxAmount = parseFloat(e_unit_price) / 100 * parseFloat(e_unit_tax_percent);
        productTableRow.find('#unit_tax_percent').val(parseFloat(e_unit_tax_percent).toFixed(2));
        productTableRow.find('#unit_tax_amount').val(parseFloat(calsUninTaxAmount).toFixed(2));
        var calcUnitPriceWithDiscount = parseFloat(e_unit_price) - parseFloat(e_unit_discount_amount);
        var calcUnitPriceIncTax = parseFloat(calcUnitPriceWithDiscount) / 100 * parseFloat(e_unit_tax_percent) + parseFloat(calcUnitPriceWithDiscount);

        productTableRow.find('#unit_price_inc_tax').val(parseFloat(calcUnitPriceIncTax).toFixed(2));
        productTableRow.find('.span_unit_price_inc_tax').html(parseFloat(calcUnitPriceIncTax).toFixed(2));

        var calcSubtotal = parseFloat(calcUnitPriceIncTax) * parseFloat(e_quantity);
        productTableRow.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
        productTableRow.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        calculateTotalAmount();
        $('#editProductModal').modal('hide');
    });

    // Remove product form purchase product list (Table)
    $(document).on('click', '#remove_product_btn',function(e){
        e.preventDefault();

        $(this).closest('tr').remove();
        calculateTotalAmount();
    });

    // Get all unite for form field
    var unites = [];
    function getUnites(){

        $.ajax({
            url:"{{route('purchases.get.all.unites')}}",
            success:function(units){

                $.each(units, function(key, unit){

                    unites.push(unit.name);
                });
            }
        });
    }
    getUnites();

    var taxArray;
    function getTaxes(){
        $.ajax({
            url:"{{route('purchases.get.all.taxes')}}",
            async:false,
            success:function(taxes){

                taxArray = taxes;
                $('#order_tax').append('<option value="0.00">@lang('No Tax')</option>');

                $.each(taxes, function(key, val){

                    $('#order_tax').append('<option value="'+val.tax_percent+'">'+val.tax_name+'</option>');
                });
                $('#order_tax').val("{{ $sale->order_tax_percent }}");
            }
        });
    }
    getTaxes();

    $('body').keyup(function(e){

        if (e.keyCode == 13 || e.keyCode == 9){

            $(".selectProduct").click();
            $('#list').empty();
            keyName = e.keyCode;
        }
    });

    $(document).on('mouseenter', '#list>li>a',function () {

        $('#list>li>a').removeClass('selectProduct');
        $(this).addClass('selectProduct');
    });

    // Automatic remove searching product is found signal
    setInterval(function(){

        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function(){

        $('#search_product').removeClass('is-valid');
    }, 1000);
</script>
@endpush
