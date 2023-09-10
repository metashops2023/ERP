@extends('layout.pos_master')
@section('pos_content')
<!-- Pos Header -->
@include('sales.pos.partial.pos_header')
<!-- Pos Header End-->
<div class="body-wraper">
    <div class="container-fluid h-100">
        <div class="pos-content">
            <div class="row h-100">
                <div class="col-lg-9">
                    <div class="row">
                        <!-- Select Category, Brand and Product Area -->
                        @include('sales.pos.partial.select_product_section')
                        <!-- Select Category, Brand and Product Area -->
                        <div class="col-lg-7 p-1 pb-0">
                            <div class="cart-table">
                                <div class="cart-table-inner-pos">
                                    <!-- <div class="tbl-head d-flex justify-content-center">
                                        <ul class="tbl-head-shortcut-menus" id="pos-shortcut-menus" style="background-color:#f38943;border-radius:6px;">
                                            <li>
                                                <a href="{{ route('pos.short.menus.modal.form') }}"
                                                    id="addPosShortcutBtn" class="head-tbl-icon border-none" tabindex="-1">
                                                    <span class="fas fa-plus" title="Add Pos Shortcut"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div> -->

                                    <!-- Sale Product Table -->
                                    @include('sales.pos.partial.sale_product_table')
                                    <!-- Sale Product Table End -->

                                    <!-- Total Item & Qty section -->
                                    @include('sales.pos.partial.total_item_and_qty')
                                    <!-- Total Item & Qty section End-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pos Footer -->
                    @include('sales.pos.partial.pos_footer')
                    <!-- Pos Footer End -->
                </div>

                <!-- Pos Total Sum And Buttons section -->
                @include('sales.pos.partial.total_sum_and_butten')
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

        $('#search_product').focus();
        var price_group_id = $('#price_group_id').val();

        $.ajax({
            url:"{{ url('sales/search/product') }}" + "/" + status + "/" + product_code + "/" + __price_group_id + "/" + warehouse_id,
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

                            $('#search_product').val('');
                            $('#stock_quantity').val(qty_limit);
                            $('.select_area').hide();
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

                                var name = product.name.length > 30 ? product.name.substring(0, 30) + '...' : product.name;

                                var tr = '';
                                tr += '<tr>';
                                tr += '<td class="serial">1</td>';

                                tr += '<td class="text-start">';
                                tr += '<a href="#" class="product-name text-info" id="edit_product" title="'+ product.product_code +'" tabindex="-1">' + name + '</a><br/><input type="'+(product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control description_input scanable" placeholder="@lang('IMEI, Serial number or other info.')">';
                                tr += '<input value="'+ product.id +'" type="hidden" class="productId-'+ product.id +'" id = "product_id" name="product_ids[]" >';
                                tr +='<input input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                                tr += '<input value="'+ product.tax_type +'" type="hidden" id="tax_type">';
                                tr +='<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent +'">';
                                tr +='<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+ parseFloat(tax_amount).toFixed(2) +'">';
                                tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                                tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                                tr += '<input value="'+discount_amount+'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                                tr += '<input value="'+(product.update_product_cost ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax)+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                                tr += '<input type="hidden" id="previous_qty" value="0.00">';
                                tr += '<input type="hidden" id="qty_limit" value="'+ qty_limit +'">';
                                tr += '<input class="index-'+ unique_index +'" type="hidden" id="index">';
                                tr += '</td>';

                                tr += '<td>';
                                tr +='<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<b><span class="span_unit">'+ product.unit.name +'</span></b>';
                                tr += '<input name="units[]" type="hidden" id="unit" value="'+ product.unit.name +'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr +='<input name="unit_prices_exc_tax[]" type="hidden" value="'+ parseFloat(price).toFixed(2) +'" id="unit_price_exc_tax">';
                                tr +='<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="'+parseFloat(unitPriceIncTax).toFixed(2) + '">';
                                tr += '<b><span class="span_unit_price_inc_tax">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span> </b>';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="'+ parseFloat(unitPriceIncTax).toFixed(2) +'" name="subtotals[]" type="hidden" id="subtotal">';
                                tr += '<b><span class="span_subtotal">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span></b>';
                                tr += '</td>';

                                tr +='<td><a href="#" class="action-btn c-delete" id="remove_product_btn" tabindex="-1"><span class="fas fa-trash"></span></a></td>';
                                tr += '</tr>';

                                $('#product_list').prepend(tr);
                                calculateTotalAmount();
                                unique_index++;
                                activeSelectedItems();
                            }
                        } else {

                            var li = "";
                            var imgUrl = "{{ asset('uploads/product/thumbnail') }}";
                            var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0.00;

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

                                var tax_amount = parseFloat(price) / 100 * parseFloat(tax_percent);
                                var unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);

                                if (product.tax_type == 2) {

                                    var inclusiveTax = 100 + parseFloat(tax_percent);
                                    var calcTax = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                    var __tax_amount = parseFloat(price) - parseFloat(calcTax);
                                    unitPriceIncTax = parseFloat(price) + parseFloat(__tax_amount);
                                    tax_amount = __tax_amount;
                                }

                                li += '<li class="mt-1">';
                                li += '<a href="#" class="product-name s" id="'+product.id+variant.id+'" onclick="salectVariant(this); return false;" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="'+ variant.id +'" data-p_name="'+ product.name +'" data-p_tax_id="' +product.tax_id + '" data-unit="'+ product.unit.name + '" data-tax_type="'+product.tax_type+'" data-tax_percent="'+ tax_percent +'" data-tax_amount="'+ tax_amount +'" data-v_code="'+ variant.variant_code +'" data-v_price="'+ variant.variant_price +'" data-v_name="'+ variant.variant_name +'" data-v_cost_inc_tax="'+(variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : product.variant_cost_with_tax)+'">'+product.name + ' - ' + variant.variant_name + ' - Price: '+parseFloat(unitPriceIncTax).toFixed(2) +'</a>';
                                li += '</li>';
                            });

                            $('.variant_list_area').prepend(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    } else if (!$.isEmptyObject(product.variant_product)) {

                        $('#stock_quantity').val(qty_limit);
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

                            var __price_with_discount = parseFloat(price) - parseFloat(discount_amount);

                            var tax_amount = parseFloat(__price_with_discount) / 100 * parseFloat(tax_percent);
                            var unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);

                            if (variant_product.product.tax_type == 2) {

                                var inclusiveTax = 100 + parseFloat(tax_percent)
                                var calcAmount = parseFloat(__price_with_discount) / parseFloat(inclusiveTax) * 100;
                                tax_amount = parseFloat(__price_with_discount) - parseFloat(calcAmount);
                                unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);
                            }

                            var name = variant_product.product.name.length > 30 ? variant_product.product.name.substring(0, 30)+'...' : variant_product.product.name;
                            var tr = '';
                            tr += '<tr>';
                            tr += '<td class="serial">1</td>';

                            tr += '<td class="text-start">';
                            tr += '<a href="#" class="product-name text-info" id="edit_product" title="'+variant_product.variant_code+'" tabindex="-1">' +name + ' - ' + variant_product.variant_name +'</a><br/><input type="'+(variant_product.product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control description_input scanable" placeholder="@lang('IMEI, Serial number or other info.')">';
                            tr += '<input value="'+ variant_product.product.id +'" type="hidden" class="productId-'+ variant_product.product.id +'" id="product_id" name="product_ids[]" >';
                            tr += '<input input value="'+ variant_product.id +'" type="hidden" class="variantId-'+ variant_product.id +'" id="variant_id" name="variant_ids[]">';
                            tr += '<input value="'+variant_product.product.tax_type+'" type="hidden" id="tax_type">';
                            tr +='<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+ tax_percent +'">';
                            tr +='<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+ parseFloat(tax_amount).toFixed(2) +'">';
                            tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                            tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                            tr += '<input value="'+ discount_amount +'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                            tr += '<input value="'+(variant_product.update_variant_cost ? variant_product.update_variant_cost.net_unit_cost : variant_product.variant_cost_with_tax)+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                            tr += '<input type="hidden" id="previous_qty" value="0.00">';
                            tr += '<input type="hidden" id="qty_limit" value="' + qty_limit +'">';
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
                            tr +='<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="'+parseFloat(unitPriceIncTax).toFixed(2) +'">';
                            tr += '<b><span class="span_unit_price_inc_tax">'+ parseFloat(unitPriceIncTax).toFixed(2) +'</span> </b>';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input value="'+ parseFloat(unitPriceIncTax).toFixed(2) +'" name="subtotals[]" type="hidden" id="subtotal">';
                            tr += '<b><span class="span_subtotal">' + parseFloat(unitPriceIncTax).toFixed(2) + '</span></b>';
                            tr += '</td>';
                            tr +='<td><a href="#" class="action-btn c-delete" id="remove_product_btn"><span class="fas fa-trash "></span></a></td>';
                            tr += '</tr>';

                            $('#product_list').prepend(tr);
                            calculateTotalAmount();
                            unique_index++;
                            activeSelectedItems();
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
                                    li +='<a href="#" class="product-name s" id="'+product.id+product.variant_id+'" onclick="salectVariant(this); return false;" data-p_id="'+ product.id +'" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="'+ product.variant_id +'" data-p_name="' +product.name +'" data-p_tax_id="'+ product.tax_id +'" data-unit="' +product.unit_name +'" data-tax_percent="'+ tax_percent +'" data-tax_type="'+ product.tax_type +'" data-tax_amount="'+ tax_amount +'" data-v_code="' + product.variant_code + '" data-v_price="'+ product.variant_price +'" data-v_name="'+ product.variant_name +'" data-v_cost_inc_tax="'+ product.variant_cost_with_tax+'">'+ product.name + ' - ' + product.variant_name + ' - Price: ' + parseFloat(unitPriceIncTax).toFixed(2) +'</a>';
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
                                    li +='<a href="#" class="product-name s" id="'+product.id+'noid'+'" onclick="singleProduct(this); return false;" data-p_id="'+ product.id +'" data-is_manage_stock="' + product.is_manage_stock + '" data-p_name="'+ product.name +'" data-unit="'+ product.unit_name + '" data-p_code="'+ product.product_code +'" data-p_price_exc_tax="'+ product.product_price +'" data-p_tax_percent="' + tax_percent +'" data-tax_type="'+ product.tax_type +'" data-p_tax_amount="'+ tax_amount +'" data-p_cost_inc_tax="'+product.product_cost_with_tax+'" data-description="'+ product.is_show_emi_on_pos +'"  >' + product.name + ' - Price: ' + parseFloat(unitPriceIncTax).toFixed(2) +'</a>';
                                    li += '</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    }
                } else {

                    $('#search_product').addClass('is-invalid');
                    $('#search_product').select();
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
        var p_tax_amount = e.getAttribute('data-p_tax_amount');
        var p_tax_type = e.getAttribute('data-tax_type');
        var description = e.getAttribute('data-description');
        var status = 'no_status';
        $('#search_product').val('');

        $.ajax({
            url:"{{ url('sales/check/single/product/stock/') }}"+"/"+ status +"/"+product_id+ "/" + __price_group_id + "/" + warehouse_id,
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

                                toastr.error('Quantity Limit is - ' + qty_limit + ' ' + product_unit);
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

                        var name = product_name.length > 30 ? product_name.substring(0, 30)+'...' : product_name;

                        var tr = '';
                        tr += '<tr>';
                        tr += '<td class="serial">1</td>';
                        tr += '<td class="text-start">';
                        tr += '<a href="#" class="product-name text-info" title="'+'SKU-'+product_code+'" id="edit_product" tabindex="-1">' + name +'</a><br/><input type="'+(description == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control description_input scanable" placeholder="@lang('IMEI, Serial number or other info')">';
                        tr += '<input value="'+ product_id +'" type="hidden" class="productId-'+
                            product_id +'" id = "product_id" name="product_ids[]" >';
                        tr +='<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                        tr +='<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+ p_tax_percent + '">';
                        tr +='<input type="hidden" id="tax_type" value="'+ p_tax_type +'">';
                        tr +='<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+ parseFloat(p_tax_amount).toFixed(2) +'">';
                        tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                        tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                        tr += '<input value="'+ discount_amount +'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                        tr += '<input value="' + product_cost_inc_tax + '" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                        tr += '<input type="hidden" id="previous_qty" value="0.00">';
                        tr += '<input type="hidden" id="qty_limit" value="' + data.stock +
                            '">';
                        tr += '<input class="index-' + unique_index  + '" type="hidden" id="index">';
                        tr += '</td>';

                        tr += '<td>';
                        tr +='<input type="number" name="quantities[]" value="1.00" class="form-control text-center" id="quantity">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<b><span class="span_unit">' + product_unit + '</span></b>';
                        tr += '<input name="units[]" type="hidden" id="unit" value="'+ product_unit +'">';
                        tr += '</td>';
                        tr += '<td>';
                        tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+parseFloat(price).toFixed(2)+ '" id="unit_price_exc_tax">';

                        tr +='<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'">';
                        tr += '<b><span class="span_unit_price_inc_tax">' + parseFloat(unitPriceIncTax).toFixed(2)+'</span> </b>';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="'+ parseFloat(unitPriceIncTax).toFixed(2) +'" name="subtotals[]" type="hidden" id="subtotal">';
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
                        activeSelectedItems();
                    }
                } else {

                    toastr.error(data.errorMsg);
                }
            }
        });
    }

    // select variant product and add purchase table
    function salectVariant(e) {

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
            url:"{{url('sales/check/branch/variant/qty/')}}"+ "/" + status + "/" + product_id + "/" + variant_id + "/" +  __price_group_id + "/" + warehouse_id,
            type: 'get',
            dataType: 'json',
            success: function(data) {

                if ($.isEmptyObject(data.errorMsg)) {

                    if (is_manage_stock) {

                        $('#stock_quantity').val(data.stock);
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

                                    toastr.error('Quantity Limit is - ' + qty_limit + ' ' + product_unit);
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

                        var name = product_name.length > 30 ? product_name.substring(0, 30)+'...' : product_name;
                        var tr = '';
                        tr += '<tr>';
                        tr += '<td class="serial">1</td>';
                        tr += '<td class="text-start">';
                        tr += '<a href="#" class="product-name text-info" title="'+'SKU-'+ variant_code +'" id="edit_product" tabindex="-1">' + name +' - ' + variant_name + '</a><br/><input type="'+(description == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control description_input scanable" placeholder="@lang('IMEI, Serial number or other info')">';
                        tr += '<input value="'+ product_id +'" type="hidden" class="productId-'+ product_id +'" id = "product_id" name="product_ids[]">';
                        tr += '<input value="'+ variant_id +'" type="hidden" class="variantId-'+ variant_id +'" id="variant_id" name="variant_ids[]">';
                        tr +='<input type="hidden" id="tax_type" value="'+ tax_type +'">';
                        tr +='<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent+'">';
                        tr +='<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+ parseFloat(tax_amount).toFixed(2) + '">';
                        tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                        tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                        tr += '<input value="'+ discount_amount +'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                        tr += '<input value="'+ variant_cost_inc_tax +'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                        tr += '<input type="hidden" id="previous_qty" value="0.00">';
                        tr += '<input type="hidden" id="qty_limit" value="' + data.stock +
                            '">';
                        tr += '<input class="index-'+ unique_index +'" type="hidden" id="index">';
                        tr += '</td>';

                        tr += '<td>';
                        tr +='<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<b><span class="span_unit">'+ product_unit +'</span></b>';
                        tr += '<input name="units[]" type="hidden" id="unit" value="'+ product_unit +'">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+ parseFloat(price).toFixed(2) +'" id="unit_price_exc_tax">';
                        tr +='<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="' +parseFloat(unitPriceIncTax).toFixed(2) + '">';
                        tr += '<b><span class="span_unit_price_inc_tax">' + parseFloat(unitPriceIncTax).toFixed(2) + '</span> </b>';
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
                        activeSelectedItems();
                    }
                } else {

                    toastr.error(data.errorMsg);
                }
            }
        });
    }

    // Get all unite for form field
    var unites = [];
    function getUnites(){
        $.ajax({
            async: false,
            url:"{{ route('purchases.get.all.unites') }}",
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
            async: false,
            url:"{{ route('purchases.get.all.taxes') }}",
            success:function(taxes){
                taxArray = taxes;
                $('#order_tax').append('<option value="">@lang('No Tax')</option>');
                $.each(taxes, function(key, val){
                    $('#order_tax').append('<option value="'+val.tax_percent+'">'+val.tax_name+'</option>');
                });
                $('#order_tax').val("{{json_decode($generalSettings->sale, true)['default_tax_id'] != 'null' ? json_decode($generalSettings->sale, true)['default_tax_id'] : '' }}");
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

    $(document).on('click', '#hard_reload', function () {

        window.location.reload(true);
    });
</script>
@endpush
