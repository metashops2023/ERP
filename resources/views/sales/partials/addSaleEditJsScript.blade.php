<script src="/assets/plugins/custom/select_li/selectli.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var branch_id = "{{ auth()->user()->branch_id }}";
    var branch_name = "{{  auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}";

    calculateTotalAmount();

    // Get all price group
    var price_groups = '';
    function getPriceGroupProducts(){
        $.ajax({
            url:"{{route('sales.product.price.groups')}}",
            success:function(data){

                price_groups = data;
            }
        });
    }
    getPriceGroupProducts();

    // Get all unite for form field
    var unites = [];
    function getUnites(){

        $.get("{{ route('purchases.get.all.unites') }}", function(units) {

            $.each(units, function(key, unit){

                unites.push(unit.name);
            });
        });
    }
    getUnites();

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

        if ($('#order_discount_type').val() == 2) {

            var orderDisAmount = parseFloat(netTotalAmount) /100 * parseFloat($('#order_discount').val() ? $('#order_discount').val() : 0);
            $('#order_discount_amount').val(parseFloat(orderDisAmount).toFixed(2));
        } else {

            var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0
            $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
        }

        // Calc order tax amount
        var orderDiscountAmount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
        var orderTax = $('#order_tax').val() ? $('#order_tax').val() : 0;
        var calcOrderTaxAmount = (parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount)) / 100 * parseFloat(orderTax);
        $('#order_tax_amount').val(parseFloat(calcOrderTaxAmount).toFixed(2));

        // Update Total payable Amount
        var shipmentCharge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;

        var calcTotalPayableAmount = parseFloat(netTotalAmount)
                                    - parseFloat(orderDiscountAmount)
                                    + parseFloat(calcOrderTaxAmount)
                                    + parseFloat(shipmentCharge);
                                    // - parseFloat(previous_paid)

        $('#total_payable_amount').val(parseFloat(calcTotalPayableAmount).toFixed(2));

        var previous_paid = $('#previous_paid').val();

        var currentReceivable = parseFloat(calcTotalPayableAmount) - parseFloat(previous_paid);

        $('#current_receivable').val(parseFloat(currentReceivable).toFixed(2));

        var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;

        var calcCurrentDue = parseFloat(currentReceivable) - parseFloat(payingAmount);
        $('#total_due').val(parseFloat(calcCurrentDue >= 0 ? calcCurrentDue : 0).toFixed(2));
    }

    var delay = (function() {

        var timer = 0;
        return function(callback, ms) {

            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    var unique_index = 0;
    $('#search_product').on('input', function(e) {

        $('.variant_list_area').empty();
        $('.select_area').hide();
        var product_code = $(this).val();
        var __product_code = product_code.replaceAll('/', '~');
        var warehouse_id = $('#warehouse_id').val();
        var __warehouse_id = warehouse_id == '' ? 'NULL' : warehouse_id;
        var warehouse_name = $('#warehouse_id').find('option:selected').data('w_name');
        var status = $('#status').val() ? $('#status').val() : 'no_status';
        var __price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';
        delay(function() { searchProduct(status, __product_code, __price_group_id, __warehouse_id, warehouse_name); }, 200);
    });

    // add Sale product by searching product code
    function searchProduct(status, product_code, __price_group_id, __warehouse_id, warehouse_name) {

        $('#search_product').focus();
        var price_group_id = $('#price_group_id').val();
        $('.variant_list_area').empty();
        $('.select_area').hide();
        $.ajax({
            url:"{{ url('sales/search/product') }}" + "/" + status + "/" + product_code + "/" + __price_group_id + "/" + __warehouse_id,
            dataType: 'json',
            success:function(product){

                if(!$.isEmptyObject(product.errorMsg)){

                    toastr.error(product.errorMsg);
                    $('#search_product').val("");
                    return;
                }

                var qty_limit = product.qty_limit;
                var discount = product.discount;

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

                            if (product.is_manage_stock == 1) {

                                $('#stock_quantity').val(parseFloat(qty_limit).toFixed(2));
                            }

                            // product_ids = document.querySelectorAll('#product_id');
                            var unique_id = document.querySelectorAll('#unique_id');

                            var __unique_id = product.id+branch_id+__warehouse_id;

                            var sameProduct = 0;

                            unique_id.forEach(function(input){

                                if(input.value == __unique_id){

                                    sameProduct += 1;
                                    var className = input.getAttribute('class');

                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.'+className).closest('tr');

                                    var presentQty = closestTr.find('#quantity').val();
                                    var qty_limit = closestTr.find('#qty_limit').val();

                                    if (status == 'no_status' || status == 1) {

                                        if(parseFloat(qty_limit) == parseFloat(presentQty)){

                                            toastr.error('Quantity Limit is - '+qty_limit+' '+product.unit.name);
                                            return;
                                        }
                                    }

                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                    //Update Subtotal
                                    var unitPrice = closestTr.find('#unit_price').val();
                                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    return;
                                }
                            });

                            if(sameProduct == 0){

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
                                } else {

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

                                var name = product.name.length > 35 ? product.name.substring(0, 35)+'...' : product.name;

                                var tr = '';
                                tr += '<tr>';
                                tr += '<td class="text-start">';
                                tr += '<a href="#" class="text-success" id="edit_product">';
                                tr += '<span class="product_name">'+name+'</span>';
                                tr += '</a><input type="'+(product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="@lang('IMEI, Serial number or other info.')">';
                                tr += '<input value="'+product.id+'" type="hidden" id="product_id" name="product_ids[]">';
                                tr += '<input value="'+product.tax_type+'" type="hidden" id="tax_type">';
                                tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                                tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent+'">';
                                tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(tax_amount).toFixed(2)+'">';
                                tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                                tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                                tr += '<input value="'+discount_amount+'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                                tr += '<input name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax" value="'+(product.update_product_cost ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax)+'">';
                                tr += '<input type="hidden" id="previous_quantity" value="0">';
                                tr += '<input type="hidden" id="qty_limit" value="'+qty_limit+'">';
                                tr += '</td>';

                                tr += '<td class="text-start">';
                                tr += '<input type="hidden" class="'+product.id+branch_id+__warehouse_id+'" id="unique_id" value="'+product.id+branch_id+__warehouse_id+'">';
                                tr += '<input type="hidden" name="branch_ids[]" id="branch_id" value="'+ branch_id +'">';
                                tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="'+ __warehouse_id +'">';

                                if (__warehouse_id != 'NULL') {

                                    tr += '<span>'+ warehouse_name +'</span>';
                                }else{
                                    tr += '<span>'+ branch_name +'</span>';
                                }

                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                                tr += '</td>';
                                tr += '<td class="text">';
                                tr += '<b><span class="span_unit">'+product.unit.name+'</span></b>';
                                tr += '<input  name="units[]" type="hidden" id="unit" value="'+product.unit.name+'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input readonly name="unit_prices_exc_tax[]" type="hidden"  id="unit_price_exc_tax" value="'+parseFloat(price).toFixed(2)+'" tabindex="-1">';
                                tr += '<input readonly name="unit_prices[]" type="text" class="form-control text-center" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" tabindex="-1">';
                                tr += '</td>';

                                tr += '<td class="text text-center">';
                                tr += '<strong><span class="span_subtotal"> '+parseFloat(unitPriceIncTax).toFixed(2)+' </span></strong>';
                                tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal" tabindex="-1">';
                                tr += '</td>';

                                tr += '<td class="text-center">';
                                tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                tr += '</td>';
                                tr += '</tr>';
                                $('#sale_list').prepend(tr);
                                calculateTotalAmount();
                            }
                        } else {

                            var li = "";
                            var imgUrl = "{{ asset('uploads/product/thumbnail') }}";

                            var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0.00;

                            $.each(product.product_variants, function(key, variant){

                                var tax_amount = parseFloat(variant.variant_price/100 * tax_percent);
                                var unitPriceIncTax = (parseFloat(variant.variant_price) / 100 * tax_percent) + parseFloat(variant.variant_price);

                                if (product.tax_type == 2) {

                                    var inclusiveTax = 100 + parseFloat(tax_percent);
                                    var calcTax = parseFloat(variant.variant_price) / parseFloat(inclusiveTax) * 100;
                                    var __tax_amount = parseFloat(variant.variant_price) - parseFloat(calcTax);
                                    unitPriceIncTax = parseFloat(variant.variant_price) + parseFloat(__tax_amount);
                                    tax_amount = __tax_amount;
                                }

                                li += '<li class="mt-1">';
                                li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="'+product.id+'" data-is_manage_stock="'+product.is_manage_stock+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit.name+'" data-tax_percent="'+tax_percent+'" data-tax_type="'+product.tax_type+'" data-tax_amount="'+tax_amount+'" data-v_code="'+variant.variant_code+'" data-v_price="'+variant.variant_price+'" data-v_name="'+variant.variant_name+'" data-v_cost_inc_tax="'+variant.variant_cost_with_tax+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+variant.variant_name+' ('+variant.variant_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                li += '</li>';
                            });

                            $('.variant_list_area').append(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    }else if(!$.isEmptyObject(product.variant_product)){

                        $('.select_area').hide();
                        $('#search_product').val('');

                        if (product.is_manage_stock == 1) {

                            $('#stock_quantity').val(parseFloat(qty_limit).toFixed(2));
                        }

                        var variant_product = product.variant_product;
                        var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.tax_percent : 0;
                        var unique_id = document.querySelectorAll('#unique_id');
                        var __unique_id = variant_product.id+branch_id+__warehouse_id;
                        var sameVariant = 0;

                        unique_id.forEach(function(input){

                            if(input.value != 'noid'){

                                if(input.value == __unique_id){

                                    sameVariant += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.'+className).closest('tr');
                                    var presentQty = closestTr.find('#quantity').val();
                                    var qty_limit = closestTr.find('#qty_limit').val();

                                    if (status == 'no_status' || status == 1) {

                                        if(parseFloat(qty_limit) == parseFloat(presentQty)){

                                            toastr.error('Quantity Limit is - '+ qty_limit +' '+ variant_product.product.unit.name);
                                            return;
                                        }
                                    }

                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                    //Update Subtotal
                                    var unitPrice = closestTr.find('#unit_price').val();
                                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    return;
                                }
                            }
                        });

                        if(sameVariant == 0){

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

                            var tax_amount = parseFloat(__price_with_discount / 100 * tax_percent);
                            var unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);

                            if (variant_product.product.tax_type == 2) {

                                var inclusiveTax = 100 + parseFloat(tax_percent)
                                var calcAmount = parseFloat(__price_with_discount) / parseFloat(inclusiveTax) * 100;
                                tax_amount = parseFloat(__price_with_discount) - parseFloat(calcAmount);
                                unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(tax_amount);
                            }

                            var name = variant_product.product.name.length > 35 ? variant_product.product.name.substring(0, 35)+'...' : variant_product.product.name;

                            var tr = '';
                            tr += '<tr>';
                            tr += '<td class="text-start">';
                            tr += '<a href="#" class="text-success" id="edit_product">';
                            tr += '<span class="product_name">'+name+' -'+variant_product.variant_name+'</span>';
                            tr += '</a><input type="'+(variant_product.product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable" placeholder="@lang('IMEI, Serial number or other info.')">';
                            tr += '<input value="'+variant_product.product.id+'" type="hidden" id="product_id" name="product_ids[]">';
                            tr += '<input value="'+variant_product.id+'" type="hidden" id="variant_id" name="variant_ids[]">';
                            tr += '<input value="'+variant_product.product.tax_type+'" type="hidden" id="tax_type">';
                            tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent+'">';
                            tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(tax_amount).toFixed(2)+'">';
                            tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                            tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                            tr += '<input value="'+ discount_amount +'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                            tr += '<input name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax" value="'+(variant_product.update_variant_cost ? variant_product.update_variant_cost.net_unit_cost : variant_product.variant_cost_with_tax)+'">';
                            tr += '<input type="hidden" id="previous_quantity" value="0">';
                            tr += '<input type="hidden" id="qty_limit" value="'+qty_limit+'">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<input type="hidden" class="'+variant_product.id+branch_id+__warehouse_id+'" id="unique_id" value="'+variant_product.id+branch_id+__warehouse_id+'">';
                            tr += '<input type="hidden" name="branch_ids[]" id="branch_id" value="'+ branch_id +'">';
                            tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="'+ __warehouse_id +'">';

                            if (__warehouse_id != 'NULL') {

                                tr += '<span>'+ warehouse_name +'</span>';
                            }else{

                                tr += '<span>'+ branch_name +'</span>';
                            }
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                            tr += '</td>';
                            tr += '<td class="text">';
                            tr += '<b><span class="span_unit">'+variant_product.product.unit.name+'</span></b>';
                            tr += '<input  name="units[]" type="hidden" id="unit" value="'+variant_product.product.unit.name+'">';
                            tr += '</td>';
                            tr += '<td>';

                            tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+parseFloat(price).toFixed(2)+'" id="unit_price_exc_tax">';
                            tr += '<input readonly name="unit_prices[]" type="text" class="form-control text-center" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2) +'" tabindex="-1">';
                            tr += '</td>';

                            tr += '<td class="text text-center">';
                            tr += '<strong><span class="span_subtotal">'+parseFloat(unitPriceIncTax).toFixed(2)+'</span></strong>';
                            tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal" tabindex="-1">';
                            tr += '</td>';
                            tr += '<td class="text-center">';
                            tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';
                            $('#sale_list').prepend(tr);
                            calculateTotalAmount();
                        }
                        }else if (!$.isEmptyObject(product.namedProducts)) {

                        if(product.namedProducts.length > 0){

                            var imgUrl = "{{asset('uploads/product/thumbnail')}}";
                            var li = "";
                            var products = product.namedProducts;

                            $.each(products, function (key, product) {

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

                                        li += '<li>';
                                        li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-product_type="variant" data-p_id="'+product.id+'" data-is_manage_stock="'+product.is_manage_stock+'" data-v_id="'+product.variant_id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-tax_type="'+product.tax_type+'" data-unit="'+product.unit_name+'" data-tax_percent="'+tax_percent+'" data-tax_amount="'+tax_amount+'" data-description="'+product.is_show_emi_on_pos+'" data-v_code="'+product.variant_code+'" data-v_price="'+product.variant_price+'" data-v_name="'+product.variant_name+'" data-v_cost_inc_tax="'+product.variant_cost_with_tax+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+product.variant_name+' ('+product.variant_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                        li +='</li>';

                                }else {

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

                                    li += '<li>';
                                    li += '<a class="select_single_product" onclick="singleProduct(this); return false;" data-product_type="single" data-p_id="'+product.id+'" data-is_manage_stock="'+product.is_manage_stock+'" data-p_name="'+product.name+'" data-unit="'+product.unit_name+'" data-p_code="'+product.product_code+'" data-p_price_exc_tax="'+product.product_price+'" data-p_tax_percent="'+tax_percent+'" data-tax_type="'+product.tax_type+'" data-description="'+product.is_show_emi_on_pos+'" data-p_tax_amount="'+tax_amount+'" data-p_cost_inc_tax="'+product.product_cost_with_tax+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' ('+product.product_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                    li +='</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    }
                }else{

                    $('#search_product').addClass('is-invalid');
                    // toastr.error('Product not found.', 'Failed');
                    // $('#search_product').select();
                }
            },error: function(err) {

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Please check the connetion.');
                    return;
                }
            }
        });
    }

    // select single product and add stock adjustment table
    var keyName = 1;
    function singleProduct(e){

        var status = $('#status').val() ? $('#status').val() : 'no_status';

        var price_group_id = $('#price_group_id').val();
        var __price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';
        $('.select_area').hide();
        $('#search_product').val('');

        if (keyName == 13 || keyName == 1) {

            document.getElementById('search_product').focus();
        }

        var warehouse_id = $('#warehouse_id').val();
        var __warehouse_id = warehouse_id == '' ? 'NULL' : warehouse_id;
        var warehouse_name = $('#warehouse_id').find('option:selected').data('w_name');

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
        $('#search_product').val('');

        $.ajax({
            url:"{{ url('sales/check/single/product/stock/') }}"+ "/" + status + "/" + product_id + "/" + __price_group_id + "/" + __warehouse_id,
            type:'get',
            dataType: 'json',
            success:function(data) {

                if($.isEmptyObject(data.errorMsg)){

                    if (is_manage_stock == 1) {

                        $('#stock_quantity').val(parseFloat(data.stock).toFixed(2));
                    }

                    var unique_id = document.querySelectorAll('#unique_id');
                    var __unique_id = product_id + branch_id + __warehouse_id;

                    var sameProduct = 0;

                    var discount = data.discount;

                    unique_id.forEach(function(input){

                        if(input.value == __unique_id){

                            sameProduct += 1;
                            var className = input.getAttribute('class');
                            // get closest table row for increasing qty and re calculate product amount
                            var closestTr = $('.'+className).closest('tr');
                            var presentQty = closestTr.find('#quantity').val();
                            var qty_limit = closestTr.find('#qty_limit').val();

                            if (status == 'no_status' || status == 1) {

                                if(parseFloat(qty_limit) === parseFloat(presentQty)){

                                    toastr.error('Quantity Limit is - '+qty_limit+' '+product_unit);
                                    return;
                                }
                            }

                            var updateQty = parseFloat(presentQty) + 1;
                            closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                            //Update Subtotal
                            var unitPrice = closestTr.find('#unit_price').val();
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

                    if(sameProduct == 0){

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

                        var name = product_name.length > 35 ? product_name.substring(0, 35)+'...' : product_name;

                        var tr = '';
                        tr += '<tr>';
                        tr += '<td class="text-start">';
                        tr += '<a href="#" class="text-success" id="edit_product">';
                        tr += '<span class="product_name">'+name+'</span>';
                        tr += '</a><input type="'+(description == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="@lang('IMEI, Serial number or other info')">';
                        tr += '<input value="'+product_id+'" type="hidden" id="product_id" name="product_ids[]">';
                        tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                        tr += '<input value="'+p_tax_type+'" type="hidden" id="tax_type">';
                        tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+p_tax_percent+'">';
                        tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(p_tax_amount).toFixed(2)+'">';
                        tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                        tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                        tr += '<input value="'+ discount_amount +'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                        tr += '<input name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax" value="'+product_cost_inc_tax+'">';
                        tr += '<input type="hidden" id="previous_quantity" value="0">';
                        tr += '<input type="hidden" id="qty_limit" value="'+data.stock+'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="hidden" class="'+ product_id + branch_id + __warehouse_id +'" id="unique_id" value="'+ product_id + branch_id + __warehouse_id +'">';
                        tr += '<input type="hidden" name="branch_ids[]" id="branch_id" value="'+ branch_id +'">';
                        tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="'+ __warehouse_id +'">';

                        if (__warehouse_id != 'NULL') {

                            tr += '<span>'+ warehouse_name +'</span>';
                        } else {

                            tr += '<span>'+ branch_name +'</span>';
                        }

                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                        tr += '<p class="text-danger" id="stock_error"></p>';
                        tr += '</td>';
                        tr += '<td class="text">';
                        tr += '<b><span class="span_unit">'+product_unit+'</span></b>';
                        tr += '<input  name="units[]" type="hidden" id="unit" value="'+product_unit+'">';
                        tr += '</td>';
                        tr += '<td>';

                        tr += '<input readonly name="unit_prices_exc_tax[]" type="hidden"  id="unit_price_exc_tax" value="'+parseFloat(price).toFixed(2)+'" tabindex="-1">';

                        var unitPriceIncTax = parseFloat(__price_with_discount) / 100 * parseFloat(p_tax_percent) + parseFloat(__price_with_discount);

                        if (p_tax_type == 2) {

                            var inclusiveTax = 100 + parseFloat(p_tax_percent);
                            var calcTax = parseFloat(__price_with_discount) / parseFloat(inclusiveTax) * 100;
                            var __tax_amount = parseFloat(__price_with_discount) - parseFloat(calcTax);
                            unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(__tax_amount);
                        }

                        tr += '<input readonly name="unit_prices[]" type="text" class="form-control text-center" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" tabindex="-1">';
                        tr += '</td>';
                        tr += '<td class="text text-center">';
                        tr += '<strong><span class="span_subtotal"> '+parseFloat(unitPriceIncTax).toFixed(2)+' </span></strong>';
                        tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal" tabindex="-1">';
                        tr += '</td>';
                        tr += '<td class="text-center">';
                        tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#sale_list').prepend(tr);
                        calculateTotalAmount();

                        if (keyName == 9) {

                            $("#quantity").select();
                            keyName = 1;
                        }
                    }
                }else{
                    toastr.error(data.errorMsg);
                }
            }
        });
    }

    // select variant product and add purchase table
    // select variant product and add purchase table
    function salectVariant(e){

        var status = $('#status').val() ? $('#status').val() : 'no_status';

        var price_group_id = $('#price_group_id').val();
        var url_param_price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no-price-group';
        var __price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';

        if (keyName == 13 || keyName == 1) {

            document.getElementById('search_product').focus();
        }

        var warehouse_id = $('#warehouse_id').val();
        var __warehouse_id = warehouse_id == '' ? 'NULL' : warehouse_id;
        var warehouse_name = $('#warehouse_id').find('option:selected').data('w_name');

        $('.select_area').hide();
        $('#search_product').val("");
        var product_id = e.getAttribute('data-p_id');
        var is_manage_stock = e.getAttribute('data-is_manage_stock');
        var product_name = e.getAttribute('data-p_name');
        var tax_percent = e.getAttribute('data-tax_percent');
        var tax_type = e.getAttribute('data-tax_type');
        var product_unit = e.getAttribute('data-unit');
        var tax_id = e.getAttribute('data-p_tax_id');
        var tax_amount = e.getAttribute('data-tax_amount');
        var variant_id = e.getAttribute('data-v_id');
        var variant_name = e.getAttribute('data-v_name');
        var variant_code = e.getAttribute('data-v_code');
        var variant_cost_inc_tax = e.getAttribute('data-v_cost_inc_tax');
        var variant_price = e.getAttribute('data-v_price');
        var description = e.getAttribute('data-description');

        $.ajax({
            url:"{{url('sales/check/branch/variant/qty/')}}"+ "/" + status + "/" + product_id + "/" + variant_id + "/" +  __price_group_id + "/" +  __warehouse_id,
            type:'get',
            dataType: 'json',
            success:function(data){

                if($.isEmptyObject(data.errorMsg)){

                    if (is_manage_stock == 1) {

                        $('#stock_quantity').val(parseFloat(data.stock).toFixed(2));
                    }

                    var discount = data.discount;

                    var unique_id = document.querySelectorAll('#unique_id');
                    var __unique_id = variant_id + branch_id + __warehouse_id;

                    var sameVariant = 0;
                    unique_id.forEach(function(input){

                        if(input.value != 'noid'){

                            if(input.value == __unique_id){

                                sameVariant += 1;
                                var className = input.getAttribute('class');
                                // get closest table row for increasing qty and re calculate product amount
                                var closestTr = $('.'+className).closest('tr');
                                var presentQty = closestTr.find('#quantity').val();
                                var qty_limit = closestTr.find('#qty_limit').val();

                                if (status == 'no_status' || status == 1) {

                                    if(parseFloat(qty_limit) === parseFloat(presentQty)){

                                        toastr.error('Quantity Limit is - '+qty_limit+' '+product_unit);
                                        return;
                                    }
                                }

                                var updateQty = parseFloat(presentQty) + 1;
                                closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                                //Update Subtotal
                                var unitPrice = closestTr.find('#unit_price').val();
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

                    if(sameVariant == 0){

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

                        var name = product_name.length > 35 ? product_name.substring(0, 35)+'...' : product_name;
                        var tr = '';
                        tr += '<tr>';
                        tr += '<td class="text-start">';
                        tr += '<a href="#" class="text-success" id="edit_product">';
                        tr += '<span class="product_name">'+name+' -'+variant_name+'</span>';
                        tr += '</a><input type="'+(description == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="@lang('IMEI, Serial number or other info.')">';
                        tr += '<input value="'+product_id+'" type="hidden" id="product_id" name="product_ids[]">';
                        tr += '<input value="'+variant_id+'" type="hidden" id="variant_id" name="variant_ids[]">';
                        tr += '<input value="'+tax_type+'" type="hidden" id="tax_type">';
                        tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent+'">';
                        tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(tax_amount).toFixed(2)+'">';
                        tr += '<input value="'+ discount.discount_type +'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                        tr += '<input value="'+ discount.discount_amount +'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                        tr += '<input value="'+ discount_amount +'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                        tr += '<input name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax" value="'+variant_cost_inc_tax+'">';
                        tr += '<input type="hidden" id="previous_quantity" value="0">';
                        tr += '<input type="hidden" id="qty_limit" value="'+data.stock+'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="hidden" class="'+ variant_id + branch_id + __warehouse_id +'" id="unique_id" value="'+ variant_id + branch_id + __warehouse_id +'">';
                        tr += '<input type="hidden" name="branch_ids[]" id="branch_id" value="'+ branch_id +'">';
                        tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="'+ __warehouse_id +'">';

                        if (__warehouse_id != 'NULL') {

                            tr += '<span>'+ warehouse_name +'</span>';
                        } else {

                            tr += '<span>'+ branch_name +'</span>';
                        }

                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                        tr += '<p class="text-danger" id="stock_error"></p>';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<b><span class="span_unit">'+product_unit+'</span></b>';
                        tr += '<input  name="units[]" type="hidden" id="unit" value="'+product_unit+'">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input name="unit_prices_exc_tax[]" type="hidden" id="unit_price_exc_tax" value="'+parseFloat(price).toFixed(2)+'">';

                        var unitPriceIncTax = parseFloat(__price_with_discount) / 100 * parseFloat(tax_percent) + parseFloat(__price_with_discount);

                        if (tax_type == 2) {

                            var inclusiveTax = 100 + parseFloat(tax_percent);
                            var calcTax = parseFloat(__price_with_discount) / parseFloat(inclusiveTax) * 100;
                            var __tax_amount = parseFloat(__price_with_discount) - parseFloat(calcTax);
                            unitPriceIncTax = parseFloat(__price_with_discount) + parseFloat(__tax_amount);
                        }

                        tr += '<input readonly name="unit_prices[]" type="text" class="form-control text-center" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" tabindex="-1">';
                        tr += '</td>';

                        tr += '<td class="text text-center">';
                        tr += '<strong><span class="span_subtotal">'+parseFloat(unitPriceIncTax).toFixed(2)+'</span></strong>';
                        tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal" tabindex="-1">';
                        tr += '</td>';

                        tr += '<td class="text-center">';
                        tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                        tr += '</td>';

                        tr += '</tr>';
                        $('#sale_list').prepend(tr);
                        calculateTotalAmount();

                        if (keyName == 9) {

                            $("#quantity").select();
                            keyName = 1;
                        }
                    }
                }else{

                    toastr.error(data.errorMsg);
                }
            }
        });
    }

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input', '#quantity', function(){

        var status = $('#status').val() ? $('#status').val() : 'no_status';

        var qty = $(this).val() ? $(this).val() : 0;

        if (parseFloat(qty) >= 0) {

            var tr = $(this).closest('tr');
            var previousQty = tr.find('#previous_quantity').val();
            var limit = tr.find('#qty_limit').val()
            var qty_limit = parseFloat(previousQty) + parseFloat(limit);

            if (status == 'no_status' || status == 1) {

                if(parseInt(qty) > parseInt(qty_limit)){

                    alert('Quantity exceeds stock quantity!');
                    $(this).val(parseFloat(qty_limit).toFixed(2));
                    var unitPrice = tr.find('#unit_price').val();
                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                    tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                    tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                    calculateTotalAmount();
                    return;
                }
            }

            var unitPrice = tr.find('#unit_price').val();
            var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty);
            tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
            calculateTotalAmount();
        }
    });

    // Input order discount and clculate total amount
    $(document).on('input', '#order_discount', function(){

        calculateTotalAmount();
    });

    // Input order discount type and clculate total amount
    $(document).on('change', '#order_discount_type', function(){

        calculateTotalAmount();
    });

    // Input shipment charge and clculate total amount
    $(document).on('input', '#shipment_charge', function(){

        calculateTotalAmount();
    });

    // chane purchase tax and clculate total amount
    $(document).on('change', '#order_tax', function(){

        calculateTotalAmount();
    });

    // Input paying amount and clculate due amount
    $(document).on('input', '#paying_amount', function(){
        calculateTotalAmount();
    });

    $(document).on('blur', '#paying_amount', function () {

        var value = $(this).val();

        if (value == "") {

            $(this).val(parseFloat(0).toFixed(2));
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

    // Show selling product's update modal
    var tableRowIndex = 0;
    $(document).on('click', '#edit_product', function(e) {

        e.preventDefault();
        $('#show_cost_section').hide();
        var parentTableRow = $(this).closest('tr');
        tableRowIndex = parentTableRow.index();
        var quantity = parentTableRow.find('#quantity').val();
        var product_name = parentTableRow.find('.product_name').html();
        var product_variant = parentTableRow.find('.product_variant').html();
        var product_code = parentTableRow.find('.product_code').html();
        var unit_price_exc_tax = parentTableRow.find('#unit_price_exc_tax').val();
        var unit_cost_inc_tax = parentTableRow.find('#unit_cost_inc_tax').val();
        var unit_tax_percent = parentTableRow.find('#unit_tax_percent').val();
        var unit_tax_amount = parentTableRow.find('#unit_tax_amount').val();
        var unit_tax_type = parentTableRow.find('#tax_type').val();
        var unit_discount_type = parentTableRow.find('#unit_discount_type').val();
        var unit_discount = parentTableRow.find('#unit_discount').val();
        var unit_discount_amount = parentTableRow.find('#unit_discount_amount').val();
        var product_unit = parentTableRow.find('#unit').val();
        // Set modal heading
        var heading = product_name;
        $('#product_info').html(heading);
        $('#unit_cost').html(bdFormat(unit_cost_inc_tax));
        $('#e_quantity').val(parseFloat(quantity).toFixed(2));
        $('#e_unit_price').val(parseFloat(unit_price_exc_tax).toFixed(2));
        $('#e_unit_discount_type').val(unit_discount_type);
        $('#e_unit_discount').val(unit_discount);
        $('#e_discount_amount').val(unit_discount_amount);

        $('#e_unit_tax').val(unit_tax_percent);

        $('#e_tax_type').val(unit_tax_type);
        $('#e_unit').empty();

        unites.forEach(function (unit) {

            if (unit == product_unit) {

                $('#e_unit').append('<option SELECTED value="'+unit+'">'+unit+'</option>');
            }else{

                $('#e_unit').append('<option value="'+unit+'">'+unit+'</option>');
            }
        });

        $('#editProductModal').modal('show');
    });

    // Calculate unit discount
    $('#e_unit_discount').on('input', function () {

        var discountValue = $(this).val() ? $(this).val() : 0.00;

        if ($('#e_unit_discount_type').val() == 1) {

            $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
        }else{

            var unit_price = $('#e_unit_price').val();
            var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
            $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
        }
    });

    // change unit discount type var productTableRow
    $('#e_unit_discount_type').on('change', function () {

        var type = $(this).val();
        var discountValue = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;

        if (type == 1) {

            $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
        }else {

            var unit_price = $('#e_unit_price').val();
            var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
            $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
        }
    });

    //Update Selling producdt
    $('#update_selling_product').on('submit', function (e) {

        e.preventDefault();
        var inputs = $('.edit_input');
        $('.error').html('');
        var countErrorField = 0;

        $.each(inputs, function(key, val){

            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val();

            if(idValue == ''){

                countErrorField += 1;
                var fieldName = $('#'+inputId).data('name');
                $('.error_'+inputId).html(fieldName+' is required.');
            }
        });

        if(countErrorField > 0){

            return;
        }

        var e_quantity = $('#e_quantity').val();
        var e_unit_price = $('#e_unit_price').val();
        var e_unit_discount_type = $('#e_unit_discount_type').val() ? $('#e_unit_discount_type').val() : 1;
        var e_unit_discount = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
        var e_unit_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0.00;
        var e_unit_tax_percent = $('#e_unit_tax').val() ? $('#e_unit_tax').val() : 0.00;
        var e_unit_tax_type = $('#e_tax_type').val() ? $('#e_tax_type').val() : 1;
        var e_unit = $('#e_unit').val();

        var productTableRow = $('#sale_list tr:nth-child(' + (tableRowIndex + 1) + ')');
        // calculate unit tax
        productTableRow.find('.span_unit').html(e_unit);
        productTableRow.find('#unit').val(e_unit);
        productTableRow.find('#unit').val(e_unit);
        productTableRow.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
        productTableRow.find('#unit_price_exc_tax').val(parseFloat(e_unit_price).toFixed(2));
        productTableRow.find('#unit_discount_type').val(e_unit_discount_type);
        productTableRow.find('#unit_discount').val(parseFloat(e_unit_discount).toFixed(2));
        productTableRow.find('#unit_discount_amount').val(parseFloat(e_unit_discount_amount).toFixed(2));

        var calcUnitPriceWithDiscount = parseFloat(e_unit_price) - parseFloat(e_unit_discount_amount);
        var calsUninTaxAmount = parseFloat(calcUnitPriceWithDiscount) / 100 * parseFloat(e_unit_tax_percent);

        if (e_unit_tax_type == 2) {

            var inclusiveTax = 100 + parseFloat(e_unit_tax_percent);
            var calc = parseFloat(calcUnitPriceWithDiscount) / parseFloat(inclusiveTax) * 100;
            calsUninTaxAmount = parseFloat(calcUnitPriceWithDiscount) - parseFloat(calc);
        }

        productTableRow.find('#unit_tax_percent').val(parseFloat(e_unit_tax_percent).toFixed(2));
        productTableRow.find('#tax_type').val(e_unit_tax_type);
        productTableRow.find('#unit_tax_amount').val(parseFloat(calsUninTaxAmount).toFixed(2));

        var calcUnitPriceIncTax = parseFloat(calcUnitPriceWithDiscount) + parseFloat(calsUninTaxAmount);

        productTableRow.find('#unit_price').val(parseFloat(calcUnitPriceIncTax).toFixed(2));

        var calcSubtotal = parseFloat(calcUnitPriceIncTax) * parseFloat(e_quantity);
        productTableRow.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
        productTableRow.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        $('#editProductModal').modal('hide');
        calculateTotalAmount();
    });

    // change unit price
    $('#e_unit_price').on('input', function () {

        var unit_price = $(this).val() ? $(this).val() : 0.00;
        var discountValue = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;

        if ($('#e_unit_discount_type').val() == 1) {

            $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
        } else {

            var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
            $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
        }
    });

    var stockErrors = 0;
    function __chackStockLimitation(tr) {

        var quantity = tr.find('#quantity').val() ? tr.find('#quantity').val() : 0;

        var previous_qty = tr.find('#previous_quantity').val();

        var stock_limit = tr.find('#qty_limit').val();

        var __stock_limit = parseFloat(previous_qty) + parseFloat(stock_limit);

        var unitName = tr.find('#qty_limit').data('unit');

        tr.find('#stock_error').html('');
        tr.find('#quantity').removeClass('border_red');

        if(parseFloat(quantity) > parseFloat(__stock_limit)) {

            tr.find('#stock_error').html('Only '+ stock_limit +' is available.');
            tr.find('#quantity').addClass('border_red');
            tr.find('#quantity').focus();
            stockErrors++;
        }
    }

    //Add purchase request by ajax
    $('#edit_sale_form').on('submit', function(e){
        e.preventDefault();
        stockErrors = 0;
        var status = $('#status').val();

        var totalItem = $('#total_item').val();

        if (parseFloat(totalItem) == 0) {

            toastr.error('Product table is empty.','Some thing went wrong.');
            return;
        }

        var allTr = $('#sale_list').find('tr');

        if (status == 1) {

            allTr.each(function () {

                __chackStockLimitation($(this));
            });
        }

        if (status == 1 && stockErrors > 0) {

            $('.loading_button').hide();
            toastr.error('Stock Limitation Error.', 'Some thing went wrong.');
            return;
        }

        var current_receivable = $('#current_receivable').val() ? $('#current_receivable').val() : 0;
        var paying_amount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;

        if (parseFloat(paying_amount) > 0 && parseFloat(paying_amount) > parseFloat(current_receivable)) {

            toastr.error('Received amount must not be greater then current receivable amount.');
            return;
        }

        $('.loading_button').show();
        var url = $(this).attr('action');

        $.ajax({
            url:url,
            type:'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data){

                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg,'ERROR');
                    $('.loading_button').hide();
                }else{

                    $('.loading_button').hide();
                    toastr.success(data.successMsg);
                    window.location = "{{ url()->previous() }}";
                }
            },error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
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

    // Automatic remove searching product is found signal
    setInterval(function(){

        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function(){

        $('#search_product').removeClass('is-valid');
    }, 1000);

    // Disable branch field after add the products
    $('.submit_button').on('click', function () {

        var value = $(this).val();
        $('#action').val(value);
    });

    @if (auth()->user()->permission->product['product_add'] == '1')

        $('#add_product').on('click', function () {

            $.ajax({
                url:"{{ route('sales.add.product.modal.view') }}",
                type:'get',
                success:function(data){

                    $('#add_product_body').html(data);
                    $('#addProductModal').modal('show');
                }
            });
        });

        // Add product by ajax
        $(document).on('submit', '#add_product_form', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    toastr.success('Successfully product is added.');

                    $.ajax({
                        url:"{{url('sales/get/recent/product')}}"+"/"+data.id,
                        type:'get',
                        success:function(data){

                            $('.loading_button').hide();
                            $('#addProductModal').modal('hide');

                            if (!$.isEmptyObject(data.errorMsg)) {

                                toastr.error(data.errorMsg);
                            }else{

                                $('#sale_list').prepend(data);
                                calculateTotalAmount();
                            }
                        }
                    });
                },
                error: function(err) {

                    $('.loading_button').hide();
                    toastr.error("@lang('Please check again all form fields.')",
                    "@lang('Something went wrong.')");
                    $('.error').html('');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_sale_' + key + '').html(error[0]);
                    });
                }
            });
        });
    @endif

    $(document).keypress(".scanable",function(event) {

        if (event.which == '10' || event.which == '13') {

            event.preventDefault();
        }
    });

    $('#status').on('change', function () {

        var status = $(this).val();
        afterChangeStatusAcivity(status);
    });

    function afterChangeStatusAcivity(status) {
        $('.payment_body').show();

        if (status == 1) {

            $('.payment_body').show();
        } else if(status == 4){

            $('.payment_body').hide();
        }else if(status == 3){

            $('.payment_body').show();
        }else if(status == 2){

            $('.payment_body').hide();
        }
    }

    $('body').keyup(function(e){

        if (e.keyCode == 13 || e.keyCode == 9){

            if ($(".selectProduct").attr('href') == undefined) {

                return;
            }

            $(".selectProduct").click();

            $('#list').empty();
            keyName = e.keyCode;
        }
    });

    // $(document).on('mouseenter', '#list>li>a',function () {

    //     $('#list>li>a').removeClass('selectProduct');
    //     $(this).addClass('selectProduct');
    // });

    $(document).on('click', '#show_cost_button', function () {

        $('#show_cost_section').toggle(500);
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

    document.onkeyup = function () {

        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.shiftKey && e.which == 13) {

            $('#save').click();
            return false;
        }
    }

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
