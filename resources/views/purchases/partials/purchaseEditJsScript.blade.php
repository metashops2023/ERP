<script src="/assets/plugins/custom/select_li/selectli.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('#payment_method').on('change', function () {
        var value = $(this).val();
        $('.payment_method').hide();
        $('#'+value).show();
    });

    // Get all unite for form field
    var unites = [];
    function getUnites(){
        $.ajax({
            url:"{{route('purchases.get.all.unites')}}",
            async : false,
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
        $.get("{{route('purchases.get.all.taxes')}}", function(taxes) {
            taxArray = taxes;
            $.each(taxes, function(key, val){
                $('#purchase_tax').append('<option value="'+val.tax_percent+'">'+val.tax_name+'</option>');
            });
        });
    }
    getTaxes();

    function calculateTotalAmount(){
        var quantities = document.querySelectorAll('#quantity');
        var line_totals = document.querySelectorAll('#line_total');
        var total_item = 0;
        var total_qty = 0;
        quantities.forEach(function(qty) {
            total_item += 1;
            total_qty += parseFloat(qty.value)
        });
        $('#total_qty').val(parseFloat(total_qty));
        $('#total_item').val(parseFloat(total_item));

        //Update Net Total Amount
        var netTotalAmount = 0;
        line_totals.forEach(function(line_total){
            netTotalAmount += parseFloat(line_total.value);
        });
        $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

        // Update total purchase amount
        var order_discount_amount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
        var purchaseTaxAmount = $('#purchase_tax_amount').val() ? $('#purchase_tax_amount').val() : 0;
        var shipment_charge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;

        var calcTotalPurchaseAmount = parseFloat(netTotalAmount) - parseFloat(order_discount_amount) + parseFloat(purchaseTaxAmount) + parseFloat(shipment_charge);

        $('#total_purchase_amount').val(parseFloat(calcTotalPurchaseAmount).toFixed(2));
        $('#paying_amount').val(parseFloat(calcTotalPurchaseAmount).toFixed(2));
        // Update purchase due
        var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
        var calcPurchaseDue = parseFloat(calcTotalPurchaseAmount) - parseFloat(payingAmount);
        $('#purchase_due').val(parseFloat(calcPurchaseDue).toFixed(2));
    }

    var delay = (function() {
        var timer = 0;
        return function(callback, ms) {
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('#search_product').on('input', function(e) {
        $('.variant_list_area').empty();
        $('.select_area').hide();
        var product_code = $(this).val();
        var __product_code = product_code.replaceAll('/', '~');
        delay(function() { searchProduct(__product_code); }, 200); //sendAjaxical is the name of remote-command
    });

    function searchProduct(product_code){
        $('.variant_list_area').empty();
        $('.select_area').hide();
        $.ajax({
            url:"{{url('purchases/search/product')}}"+"/"+product_code,
            dataType: 'json',
            success:function(product){

                if (!$.isEmptyObject(product.errorMsg)) {

                    toastr.error(product.errorMsg);
                    $('#search_product').val('');
                    return;
                }

                if(!$.isEmptyObject(product.product) || !$.isEmptyObject(product.variant_product) || !$.isEmptyObject(product.namedProducts)){
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
                                    // update same product qty
                                    var presentQty = closestTr.find('#quantity').val();
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(updateQty);

                                    // update unit cost with discount
                                    unitCost = closestTr.find('#unit_cost').val();
                                    discount = closestTr.find('#unit_discount').val();
                                    var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(discount);
                                    var unitCostWithDiscount = closestTr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

                                    // update subtotal
                                    var calcSubTotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(updateQty);
                                    var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));

                                    // update net unit cost
                                    var unit_tax = closestTr.find('#unit_tax').val();
                                    var calsNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(unit_tax);
                                    var netUnitCost = closestTr.find('#net_unit_cost').val(parseFloat(calsNetUnitCost).toFixed(2));

                                    // update line total
                                    var calcLineTotal = parseFloat(calsNetUnitCost) * parseFloat(updateQty);
                                    var lineTotal = closestTr.find('#line_total').val(parseFloat(calcLineTotal));
                                    calculateTotalAmount();
                                    return;
                                }
                            });

                            if(sameProduct == 0){
                                var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0;
                                var tax_amount = parseFloat(product.tax != null ? product.product_cost/100 * product.tax.tax_percent : 0);
                                var tr = '';
                                tr += '<tr class="text-start>';
                                tr += '<td>';
                                tr += '<a class="text-success product_name" id="select_product">'+product.name+'</a> ';
                                tr += '<input type="hidden" name="descriptions[]" id="description">';
                                tr += '<input value="'+product.id+'" type="hidden" class="productId-'+product.id+'" id="product_id" name="product_ids[]">';
                                tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="1" required name="quantities[]" type="number" step="any" class="form-control" id="quantity">';
                                tr += '<select name="unit_names[]" id="unit_name" class="form-control mt-1">';
                                    unites.forEach(function(unit) {
                                        if (product.unit.name == unit) {
                                            tr += '<option SELECTED value="'+unit+'">'+unit+'</option>';
                                        } else {
                                            tr += '<option value="'+unit+'">'+unit+'</option>';
                                        }
                                    });
                                tr += '</select>';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="'+product.product_cost+'" required name="unit_costs[]" type="text" class="form-control" id="unit_cost">';
                                @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                    tr += '<input name="lot_number[]" placeholder="@lang('Lot No')" type="text" class="form-control mt-1" id="lot_number" value="">';
                                @endif
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="0.00" required name="unit_discounts[]" type="text" class="form-control" id="unit_discount">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="'+product.product_cost+'" required name="unit_costs_with_discount[]" type="text" class="form-control" id="unit_cost_with_discount">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="'+product.product_cost+'" required name="subtotals[]" type="text" class="form-control" id="subtotal">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<span>'+tax_percent+'%'+'</span>';
                                tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" value="'+tax_percent+'">';
                                tr += '<input type="hidden" value="'+parseFloat(tax_amount).toFixed(2)+'" name="unit_taxes[]" id="unit_tax">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input type="hidden" value="'+product.product_cost_with_tax+'" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax">';
                                tr += '<input value="'+product.product_cost_with_tax+'" name="net_unit_costs[]" type="text" class="form-control" id="net_unit_cost">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="'+product.product_cost_with_tax+'" type="text" name="linetotals[]" id="line_total" class="form-control">';
                                tr += '</td>';

                                @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                    tr += '<td>';
                                    tr += '<input value="'+product.profit+'" type="text" name="profits[]" class="form-control" id="profit">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="'+product.product_price+'" type="text" name="selling_prices[]" class="form-control" id="selling_price">';
                                    tr += '</td>';
                                @endif

                                tr += '<td class="text-start">';
                                tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
                                tr += '</td>';

                                tr += '</tr>';
                                $('#purchase_list').prepend(tr);
                                calculateTotalAmount();
                            }
                        }else{
                            var li = "";
                            var imgUrl = "{{asset('uploads/product/thumbnail')}}";
                            var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0.00;
                            $.each(product.product_variants, function(key, variant){
                                var tax_amount = parseFloat(product.tax != null ? variant.variant_cost/100 * product.tax.tax_percent : 0.00);
                                var unitPriceIncTax = (parseFloat(variant.variant_price) / 100 * tax_percent) + parseFloat(variant.variant_price) ;
                                li += '<li>';
                                li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit.name+'" data-tax_percent="'+tax_percent+'" data-tax_amount="'+tax_amount+'" data-v_code="'+variant.variant_code+'" data-v_cost="'+variant.variant_cost+'" data-v_profit="'+variant.variant_profit+'" data-v_price="'+variant.variant_price+'" data-v_cost_with_tax="'+variant.variant_cost_with_tax+'"  data-v_name="'+variant.variant_name+'" href="#"><img style="width:30px; height:30px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+variant.variant_name+' ('+variant.variant_code+')'+' - Unit Cost: '+variant.variant_cost_with_tax+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                li +='</li>';
                            });
                            $('.variant_list_area').append(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    }else if(!$.isEmptyObject(product.namedProducts)){

                        if(product.namedProducts.length > 0) {

                            var li = "";
                            var imgUrl = "{{asset('uploads/product/thumbnail')}}";
                            var products = product.namedProducts;

                            $.each(products, function (key, product) {

                                var tax_percent = product.tax_percent != null ? product.tax_percent : 0.00;

                                if (product.is_variant == 1) {

                                    var tax_amount = parseFloat(product.variant_cost/100 * product.tax_percent);
                                    var unitPriceIncTax = (parseFloat(product.variant_price) / 100 * tax_percent) + parseFloat(product.variant_price);
                                    li += '<li class="mt-1">';
                                    li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="'+product.id+'" data-v_id="'+product.variant_id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit_name+'" data-tax_percent="'+tax_percent+'" data-tax_amount="'+tax_amount+'" data-v_code="'+product.variant_code+'" data-v_cost="'+product.variant_cost+'" data-v_profit="'+product.variant_profit+'" data-v_price="'+product.variant_price+'" data-v_cost_with_tax="'+product.variant_cost_with_tax+'"  data-v_name="'+product.variant_name+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+product.variant_name+' ('+product.variant_code+')'+' - Unit Cost: '+product.variant_cost_with_tax+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                    li +='</li>';

                                } else {

                                    var tax_amount = parseFloat(product.product_cost/100 * product.tax_percent);

                                    var unitPriceIncTax = (parseFloat(product.product_price) / 100 * tax_percent) + parseFloat(product.product_price);

                                    li += '<li class="mt-1">';
                                    li += '<a class="select_single_product" onclick="singleProduct(this); return false;" data-p_id="'+product.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit_name+'" data-tax_percent="'+tax_percent+'" data-tax_amount="'+tax_amount+'" data-p_code="'+product.product_code+'" data-p_cost="'+product.product_cost+'" data-p_profit="'+product.profit+'" data-p_price="'+product.product_price+'" data-p_cost_with_tax="'+product.product_cost_with_tax+'" data-p_name="'+product.name+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' ('+product.product_code+')'+' - Unit Cost: '+product.product_cost_with_tax+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                    li +='</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    }else if(!$.isEmptyObject(product.variant_product)){

                        $('.select_area').hide();
                        $('#search_product').val('');
                        var variant_product = product.variant_product;
                        console.log(variant_product);
                        var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.percent : 0;
                        var tax_rate = parseFloat(variant_product.product.tax != null ? variant_product.variant_cost/100 * tax_percent : 0);
                        var variant_ids = document.querySelectorAll('#variant_id');
                        var sameVariant = 0;

                        variant_ids.forEach(function(input){

                            if(input.value != 'noid'){

                                if(input.value == variant_product.id){

                                    sameVariant += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.'+className).closest('tr');
                                    // update same product qty
                                    var presentQty = closestTr.find('#quantity').val();
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(updateQty);

                                    // update unit cost with discount
                                    unitCost = closestTr.find('#unit_cost').val();
                                    discount = closestTr.find('#unit_discount').val();
                                    var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(discount);
                                    var unitCostWithDiscount = closestTr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

                                    // update subtotal
                                    var calcSubTotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(updateQty);
                                    var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));

                                    // update net unit cost
                                    var unit_tax = closestTr.find('#unit_tax').val();
                                    var calsNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(unit_tax);
                                    var netUnitCost = closestTr.find('#net_unit_cost').val(parseFloat(calsNetUnitCost).toFixed(2));

                                    // update line total
                                    var calcLineTotal = parseFloat(calsNetUnitCost) * parseFloat(updateQty);
                                    var lineTotal = closestTr.find('#line_total').val(parseFloat(calcLineTotal));
                                    calculateTotalAmount();
                                    return;
                                }
                            }
                        });

                        if(sameVariant == 0){
                            var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.tax_percent : 0;
                            var tax_amount = parseFloat(variant_product.product.tax != null ? variant_product.variant_cost/100 * variant_product.product.tax.tax_percent : 0);
                            var tr = '';
                            tr += '<tr class="text-start">';
                            tr += '<td>';
                            tr += '<a class="text-success product_name" id="select_product">'+variant_product.product.name+' - '+variant_product.variant_name+'</a>';
                            tr += '<input type="hidden" name="descriptions[]" id="description">';
                            tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                            tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input value="1" required name="quantities[]" type="number" step="any" class="form-control" id="quantity">';
                            tr += '<select name="unit_names[]" id="unit_name" class="form-control mt-1">';
                            unites.forEach(function(unit) {
                                if (variant_product.product.unit.name == unit) {
                                    tr += '<option SELECTED value="'+unit+'">'+unit+'</option>';
                                }else{
                                    tr += '<option value="'+unit+'">'+unit+'</option>';
                                }
                            })
                            tr += '</select>';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input value="'+variant_product.variant_cost+'" required name="unit_costs[]" type="text" class="form-control" id="unit_cost">';
                            @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                tr += '<input name="lot_number[]" placeholder="@lang('Lot No')" type="text" class="form-control mt-1" id="lot_number" value="">';
                            @endif
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input value="0.00" required name="unit_discounts[]" type="text" class="form-control" id="unit_discount">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input value="'+variant_product.variant_cost+'" required name="unit_costs_with_discount[]" type="text" class="form-control" id="unit_cost_with_discount">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input readonly value="'+variant_product.variant_cost+'" required name="subtotals[]" type="text" class="form-control" id="subtotal">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span>'+tax_percent+'%'+'</span>';
                            tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" id="unit_tax" value="'+tax_percent+'">';
                            tr += '<input type="hidden" value="'+parseFloat(tax_amount).toFixed(2)+'" name="unit_taxes[]" id="unit_tax">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input type="hidden" value="'+variant_product.variant_cost_with_tax+'" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax">';
                            tr += '<input value="'+variant_product.variant_cost_with_tax+'" name="net_unit_costs[]" type="text" class="form-control" id="net_unit_cost">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input readonly value="'+variant_product.variant_cost_with_tax+'" type="text" name="linetotals[]" id="line_total" class="form-control">';
                            tr += '</td>';

                            @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                tr += '<td>';
                                tr += '<input value="'+variant_product.variant_profit+'" type="text" name="profits[]" class="form-control" id="profit">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="'+variant_product.variant_price+'" type="text" name="selling_prices[]" class="form-control" id="selling_price">';
                                tr += '</td>';
                            @endif

                            tr += '<td>';
                            tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
                            tr += '</td>';

                            tr += '</tr>';
                            $('#purchase_list').prepend(tr);
                            calculateTotalAmount();
                        }
                    }
                }else{
                    $('#search_product').addClass('is-invalid');
                }
            }
        });
    }

    // select single product and add purchase table
    var keyName = 1;
    function singleProduct(e){
        if (keyName == 13 || keyName == 1) {
            document.getElementById('search_product').focus();
        }
        $('.select_area').hide();
        $('#search_product').val('');

        var product_id = e.getAttribute('data-p_id');
        var product_name = e.getAttribute('data-p_name');
        var tax_percent = e.getAttribute('data-tax_percent');
        var product_unit = e.getAttribute('data-unit');
        var tax_id = e.getAttribute('data-p_tax_id') != null ? e.getAttribute('data-p_tax_id') : '';
        var tax_amount = e.getAttribute('data-tax_amount');

        var product_code = e.getAttribute('data-p_code');
        var product_cost = e.getAttribute('data-p_cost');
        var product_cost_with_tax  = e.getAttribute('data-p_cost_with_tax');
        var product_profit = e.getAttribute('data-p_profit');
        var product_price = e.getAttribute('data-p_price');
        product_ids = document.querySelectorAll('#product_id');
        var sameProduct = 0;
        product_ids.forEach(function(input){
            if(input.value == product_id){
                sameProduct += 1;
                var className = input.getAttribute('class');
                // get closest table row for increasing qty and re calculate product amount
                var closestTr = $('.'+className).closest('tr');
                // update same product qty
                var presentQty = closestTr.find('#quantity').val();
                var updateQty = parseFloat(presentQty) + 1;
                closestTr.find('#quantity').val(updateQty);

                // update unit cost with discount
                unitCost = closestTr.find('#unit_cost').val();
                discount = closestTr.find('#unit_discount').val();
                var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(discount);
                var unitCostWithDiscount = closestTr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

                // update subtotal
                var calcSubTotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(updateQty);
                var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));

                // update net unit cost
                var unit_tax = closestTr.find('#unit_tax').val();
                var calsNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(unit_tax);
                var netUnitCost = closestTr.find('#net_unit_cost').val(parseFloat(calsNetUnitCost).toFixed(2));

                // update line total
                var calcLineTotal = parseFloat(calsNetUnitCost) * parseFloat(updateQty);
                var lineTotal = closestTr.find('#line_total').val(parseFloat(calcLineTotal));
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
            var tr = '';
            tr += '<tr class="text-start">';
            tr += '<td>';
            tr += '<a class="text-success product_name" id="select_product">'+product_name.substring(0, 20)+'</a>';
            tr += '<input type="hidden" name="descriptions[]" id="description">';
            tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
            tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input value="1" required name="quantities[]" type="number" step="any" class="form-control" id="quantity">';
            tr += '<select name="unit_names[]" id="unit_name" class="form-control mt-1">';
            unites.forEach(function(unit) {
                if (product_unit == unit) {
                    tr += '<option SELECTED value="'+unit+'">'+unit+'</option>';
                }else{
                    tr += '<option value="'+unit+'">'+unit+'</option>';
                }
            })
            tr += '</select>';
            tr += '</td>';

            tr += '<td>';
            tr += '<input value="'+product_cost+'" required name="unit_costs[]" type="text" class="form-control" id="unit_cost">';
            @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                tr += '<input name="lot_number[]" placeholder="@lang('Lot No')" type="text" class="form-control mt-1" id="lot_number" value="">';
            @endif
            tr += '</td>';

            tr += '<td>';
            tr += '<input value="0.00" required name="unit_discounts[]" type="text" class="form-control" id="unit_discount">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input value="'+product_cost+'" required name="unit_costs_with_discount[]" type="text" class="form-control" id="unit_cost_with_discount">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input value="'+product_cost+'" required name="subtotals[]" type="text" class="form-control" id="subtotal">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input readonly type="text" name="tax_percents[]"  id="tax_percent" class="form-control" value="'+tax_percent+'">'
            tr += '<input type="hidden" value="'+parseFloat(tax_amount).toFixed(2)+'" name="unit_taxes[]"   id="unit_tax">';
            ;

            tr += '</td>';

            tr += '<td>';
            tr += '<input type="hidden" value="'+product_cost_with_tax+'" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax">';
            tr += '<input value="'+product_cost_with_tax+'" name="net_unit_costs[]" type="text" class="form-control" id="net_unit_cost">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input readonly value="'+product_cost_with_tax+'" type="text" name="linetotals[]" id="line_total" class="form-control">';
            tr += '</td>';

            @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                tr += '<td>';
                tr += '<input value="'+product_profit+'" type="text" name="profits[]" class="form-control" id="profit">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input value="'+product_price+'" type="text" name="selling_prices[]" class="form-control" id="selling_price">';
                tr += '</td>';
            @endif

            tr += '<td class="text-start">';
            tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
            tr += '</td>';

            tr += '</tr>';
            $('#purchase_list').prepend(tr);
            calculateTotalAmount();
            if (keyName == 9) {
                $("#quantity").select();
                keyName = 1;
            }
        }
    }

    // select variant product and add purchase table
    function salectVariant(e){
        if (keyName == 13 || keyName == 1) {
            document.getElementById('search_product').focus();
        }

        $('.select_area').hide();
        $('#search_product').val("");
        var product_id = e.getAttribute('data-p_id');
        var product_name = e.getAttribute('data-p_name');
        var tax_percent = e.getAttribute('data-tax_percent');
        var product_unit = e.getAttribute('data-purchase_unit');
        var tax_id = e.getAttribute('data-p_tax_id') != null ? e.getAttribute('data-p_tax_id') : '';
        var tax_amount = e.getAttribute('data-tax_amount');
        var variant_id = e.getAttribute('data-v_id');
        var variant_name = e.getAttribute('data-v_name');
        var variant_code = e.getAttribute('data-v_code');
        var variant_cost = e.getAttribute('data-v_cost');
        var variant_cost_with_tax  = e.getAttribute('data-v_cost_with_tax');
        var variant_profit = e.getAttribute('data-v_profit');
        var variant_price = e.getAttribute('data-v_price');
        var variant_ids = document.querySelectorAll('#variant_id');
        var sameVariant = 0;
        variant_ids.forEach(function(input){

            if(input.value != 'noid'){

                if(input.value == variant_id){

                    sameVariant += 1;
                    var className = input.getAttribute('class');
                    // get closest table row for increasing qty and re calculate product amount
                    var closestTr = $('.'+className).closest('tr');
                    // update same product qty
                    var presentQty = closestTr.find('#quantity').val();
                    var updateQty = parseFloat(presentQty) + 1;
                    closestTr.find('#quantity').val(updateQty);

                    // update unit cost with discount
                    unitCost = closestTr.find('#unit_cost').val();
                    discount = closestTr.find('#unit_discount').val();
                    var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(discount);
                    var unitCostWithDiscount = closestTr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

                    // update subtotal
                    var calcSubTotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(updateQty);
                    var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));

                    // update net unit cost
                    var unit_tax = closestTr.find('#unit_tax').val();
                    var calsNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(unit_tax);
                    var netUnitCost = closestTr.find('#net_unit_cost').val(parseFloat(calsNetUnitCost).toFixed(2));

                    // update line total
                    var calcLineTotal = parseFloat(calsNetUnitCost) * parseFloat(updateQty);
                    var lineTotal = closestTr.find('#line_total').val(parseFloat(calcLineTotal));
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

            var tr = '';
            tr += '<tr class="text-start">';
            tr += '<td>';
            tr += '<a class="text-success product_name" id="select_product">'+product_name.substring(0, 20)+' - '+variant_name+'</a>';
            tr += '<input type="hidden" name="descriptions[]" id="description" value="">';
            tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
            tr += '<input value="'+variant_id+'" type="hidden" class="variantId-'+variant_id+'" id="variant_id" name="variant_ids[]">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input value="1" required name="quantities[]" type="number" step="any" class="form-control" id="quantity">';
            tr += '<select name="unit_names[]" id="unit_name" class="form-control mt-1">';

            unites.forEach(function(unit) {

                if (product_unit == unit) {

                    tr += '<option SELECTED value="'+unit+'">'+unit+'</option>';
                } else {

                    tr += '<option value="'+unit+'">'+unit+'</option>';
                }
            })
            tr += '</select>';
            tr += '</td>';

            tr += '<td>';
            tr += '<input value="'+variant_cost+'" required name="unit_costs[]" type="text" class="form-control" id="unit_cost">';

            @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')

                tr += '<input name="lot_number[]" placeholder="@lang('Lot No')" type="text" class="form-control mt-1" id="lot_number" value="">';
            @endif

            tr += '</td>';
            tr += '<td>';
            tr += '<input value="0.00" required name="unit_discounts[]" type="number" class="form-control" id="unit_discount">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input value="'+variant_cost+'" required name="unit_costs_with_discount[]" type="number" class="form-control" id="unit_cost_with_discount">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input readonly value="'+variant_cost+'" required name="subtotals[]" type="number" class="form-control" id="subtotal">';
            tr += '</td>';

            tr += '<td>';

            tr += '<input readonly type="text" name="tax_percents[]"  id="tax_percent" class="form-control" value="'+tax_percent+'">';
            tr += '<input type="hidden" value="'+parseFloat(tax_amount).toFixed(2)+'" name="unit_taxes[]" type="text" id="unit_tax">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input type="hidden" value="'+variant_cost_with_tax+'" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax">';
            tr += '<input value="'+variant_cost_with_tax+'" name="net_unit_costs[]" type="number" class="form-control" id="net_unit_cost">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input readonly value="'+variant_cost_with_tax+'" type="number" name="linetotals[]" id="line_total" class="form-control">';
            tr += '</td>';

            @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')

                tr += '<td>';
                tr += '<input value="'+variant_profit+'" type="text" name="profits[]" class="form-control" type="number" id="profit">';
                tr += '</td>';

                tr += '<td class="text-right">';
                tr += '<input value="'+variant_price+'" type="number" name="selling_prices[]" class="form-control" id="selling_price">';
                tr += '</td>';
            @endif

            tr += '<td class="text-start">';
            tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
            tr += '</td>';

            tr += '</tr>';
            $('#purchase_list').prepend(tr);
            calculateTotalAmount();
            if (keyName == 9) {
                $("#quantity").select();
                keyName = 1;
            }
        }
    }

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input', '#quantity', function(){
        var qty = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        //Update subtotal
        var unitCostWithDiscount = tr.find('#unit_cost_with_discount').val();
        var calcSubtotal = parseFloat(unitCostWithDiscount) * parseFloat(qty);
        var subtotal = tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));

        //Update line total
        var netUnitCost = tr.find('#net_unit_cost').val();
        var calcLineTotal = parseFloat(netUnitCost) * parseFloat(qty);
        var lineTotal = tr.find('#line_total').val(parseFloat(calcLineTotal).toFixed(2));
        // console.log(tr);
        calculateTotalAmount();
    });

    // Change tax percent and clculate row amount
    $(document).on('input', '#unit_cost', function(){
        var unitCost = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');

        // update unit cost with discount
        var discount = tr.find('#unit_discount').val();
        var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(discount);
        var unitCostWithDiscount = tr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

        // update subtotal
        var quantity = tr.find('#quantity').val();
        var calcSubtotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(quantity);
        tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));

        // Update net unit cost
        var tax_percent = tr.find('#tax_percent').val();
        //Calc Unit tax
        var calcTaxAmount = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(tax_percent);
        tr.find('#unit_tax').val(parseFloat(calcTaxAmount).toFixed(2));
        var calcNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(calcTaxAmount);
        tr.find('#net_unit_cost').val(parseFloat(calcNetUnitCost).toFixed(2));

        // Calc unit inc
        var unitCostIncTax = parseFloat(unitCost) + parseFloat(calcTaxAmount);
        tr.find('#unit_cost_inc_tax').val(parseFloat(unitCostIncTax).toFixed(2));
        // Update line total
        var calcLineTotal = parseFloat(calcNetUnitCost) * parseFloat(quantity);
        var lineTotal = tr.find('#line_total').val(parseFloat(calcLineTotal).toFixed(2));

        // Update profit
        // var profitMargin = tr.find('#profit').val();
        // var calcProfit = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(profitMargin) + parseFloat(calcUnitCostWithDiscount);
        // var sellingPrice = tr.find('#selling_price').val(parseFloat(calcProfit).toFixed(2));

        var selling_price = tr.find('#selling_price').val() ? tr.find('#selling_price').val() : 0;
        var profitAmount = parseFloat(selling_price) - parseFloat(calcUnitCostWithDiscount);
        var __cost = parseFloat(calcUnitCostWithDiscount) > 0 ? parseFloat(calcUnitCostWithDiscount) : parseFloat(profitAmount);
        var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __calcProfit = calcProfit ? calcProfit : 0;
        tr.find('#profit').val(parseFloat(__calcProfit).toFixed(2));

        calculateTotalAmount();
    });

    $(document).on('input', '#selling_price',function() {

        var selling_price = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        var product_cost = tr.find('#unit_cost_with_discount').val() ? tr.find('#unit_cost_with_discount').val() : 0;
        var profitAmount = parseFloat(selling_price) - parseFloat(product_cost);
        var __cost = parseFloat(product_cost) > 0 ? parseFloat(product_cost) : parseFloat(profitAmount);
        var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __calcProfit = calcProfit ? calcProfit : 0;
        tr.find('#profit').val(parseFloat(__calcProfit).toFixed(2));
    });

    // Input discount and clculate row amount
    $(document).on('input', '#unit_discount', function(){
        var unit_discount = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        //Update unit cost with discount
        var unitCost = tr.find('#unit_cost').val();
        var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(unit_discount);
        var unitCostWithDiscount = tr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

        // Update sub-total
        var quantity = tr.find('#quantity').val();
        var calcSubtotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(quantity);
        var subtotal = tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));

        // Update net unit cost
        var tax_percent = tr.find('#tax_percent').val();
        // Calc unit tax
        var calcTaxAmount = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(tax_percent);
        tr.find('#unit_tax').val(parseFloat(calcTaxAmount).toFixed(2));
        var calsNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(calcTaxAmount);
        tr.find('#net_unit_cost').val(parseFloat(calsNetUnitCost).toFixed(2));

        // Update line total
        var calcLineTotal = parseFloat(calsNetUnitCost) * parseFloat(quantity);
        var lineTotal = tr.find('#line_total').val(parseFloat(calcLineTotal).toFixed(2));

        // Update profit
        // var profitMargin = tr.find('#profit').val();
        // var calcProfit = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(profitMargin) + parseFloat(calcUnitCostWithDiscount);
        // var sellingPrice = tr.find('#selling_price').val(parseFloat(calcProfit).toFixed(2));

        var selling_price = tr.find('#selling_price').val() ? tr.find('#selling_price').val() : 0;
        var profitAmount = parseFloat(selling_price) - parseFloat(calcUnitCostWithDiscount);
        var __cost = parseFloat(calcUnitCostWithDiscount) > 0 ? parseFloat(calcUnitCostWithDiscount) : parseFloat(profitAmount);
        var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __calcProfit = calcProfit ? calcProfit : 0;
        tr.find('#profit').val(parseFloat(__calcProfit).toFixed(2));
        calculateTotalAmount();
    });

    $(document).on('blur', '#unit_discount', function(){

        if ($(this).val() == '') {

            $(this).val(parseFloat(0).toFixed(2));
        }
    });

    // Input profit margin and clculate row amount
    $(document).on('input', '#profit', function(){

        var profit = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');

        // Update selling price
        var unit_cost_with_discount = tr.find('#unit_cost_with_discount').val();
        var calcProfit = parseFloat(unit_cost_with_discount)  /100 * parseFloat(profit) + parseFloat(unit_cost_with_discount);
        var sellingPrice = tr.find('#selling_price').val(parseFloat(calcProfit).toFixed(2));
        calculateTotalAmount();
    });

     // Input profit margin and clculate row amount
     $(document).on('input', '#selling_price', function(){

        var price = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');

        // Update selling price
        var unit_cost_with_discount = tr.find('#unit_cost_with_discount').val();
        var profitAmount = parseFloat(price) - parseFloat(unit_cost_with_discount);
        var calcProfit = parseFloat(profitAmount) / parseFloat(unit_cost_with_discount) * 100;
        var sellingPrice = tr.find('#profit').val(parseFloat(calcProfit).toFixed(2));
    });

    $(document).on('blur', '#profit', function(){

        if ($(this).val() == '') {

            $(this).val(parseFloat(0).toFixed(2));
        }
    });

    // Input order discount and clculate total amount
    $(document).on('input', '#order_discount', function(){
        var orderDiscount = $(this).val() ? $(this).val() : 0;
        var orderDiscountType = $('#order_discount_type').val();
        var netTotalAmount = $('#net_total_amount').val();

        if (orderDiscountType == 1) {

            $('.label_order_discount_amount').html(parseFloat(orderDiscount).toFixed(2));
            $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
        }else{

            var calsOrderDiscount = parseFloat(netTotalAmount) / 100 * parseFloat(orderDiscount);
            $('.label_order_discount_amount').html(parseFloat(calsOrderDiscount).toFixed(2));
            $('#order_discount_amount').val(parseFloat(calsOrderDiscount).toFixed(2));
        }
        calculateTotalAmount();
    });

    // Input order discount type and clculate total amount
    $(document).on('change', '#order_discount_type', function(){
        var orderDiscountType = $(this).val() ? $(this).val() : 0;
        var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0.00;
        var netTotalAmount = $('#net_total_amount').val();
        if (orderDiscountType == 1) {
            $('.label_order_discount_amount').html(parseFloat(orderDiscount).toFixed(2));
            $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
        }else{
            var calsOrderDiscount = parseFloat(netTotalAmount) / 100 * parseFloat(orderDiscount);
            $('.label_order_discount_amount').html(parseFloat(calsOrderDiscount).toFixed(2));
            $('#order_discount_amount').val(parseFloat(calsOrderDiscount).toFixed(2));
        }
        calculateTotalAmount();
    });

        // Input shipment charge and clculate total amount
        $(document).on('input', '#shipment_charge', function(){
        calculateTotalAmount();
    });

    // chane purchase tax and clculate total amount
    $(document).on('change', '#purchase_tax', function() {
        var purchaseTax = $(this).val() ? $(this).val() : 0;
        var netTotalAmount = $('#net_total_amount').val();
        var calcPurchaseTaxAmount = parseFloat(netTotalAmount) / 100 * parseFloat(purchaseTax);
        $('#purchase_tax_amount').val(parseFloat(calcPurchaseTaxAmount).toFixed(2));
        calculateTotalAmount();
    });

    // Input paying amount and clculate due amount
    $(document).on('input', '#paying_amount', function(){
        var payingAmount = $(this).val() ? $(this).val() : 0;
        var total_purchase_amount = $('#total_purchase_amount').val() ? $('#total_purchase_amount').val() : 0;
        var calcDueAmount = parseFloat(total_purchase_amount) - parseFloat(payingAmount);
        $('#purchase_due').val(parseFloat(calcDueAmount).toFixed(2));
    });

    // Remove product form purchase product list (Table)
    $(document).on('click', '#remove_product_btn',function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        calculateTotalAmount();
        document.getElementById('search_product').focus();
    });

    //Add purchase request by ajax
    $('#edit_purchase_form').on('submit', function(e){
        e.preventDefault();
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
                } else {

                    $('.loading_button').hide();
                    toastr.success(data);

                    window.location = "{{ url()->previous() }}";

                }
            },error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }else if (err.status == 500) {

                    toastr.error('Server error. Please contact the support team.');
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

    setInterval(function(){

        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function(){

        $('#search_product').removeClass('is-valid');
    }, 1000);

    // Show add product modal with data
    $('#add_product').on('click', function () {
        $.ajax({
            url:"{{route('purchases.add.product.modal.view')}}",
            type:'get',
            success:function(data){

                $('#add_product_body').html(data);
                $('#addProductModal').modal('show');
            }
        });
    });

    var lastSelectedTr = '';
    $(document).on('click', '#select_product', function (e) {
        e.preventDefault();

        is_prevent_default = 0;
        var tr = $(this).closest('tr');
        lastSelectedTr = tr;
        var product_name = tr.find('.product_name').html();
        $('#product_name').html('('+product_name+')');
        var value = tr.find('#description').val();
        $('#product_description').val(value);
        $('#addDescriptionModal').modal('show');
        //document.getElementById('product_description').focus();
    });

    $(document).on('click', '#add_description', function () {

        var value = $('#product_description').val();
        lastSelectedTr.find('#description').val(value);
        $('#product_description').val('');
        $('#addDescriptionModal').modal('hide');
    });

    $(document).keypress(".scanable",function(event){

        if (event.which == '10' || event.which == '13') {

            event.preventDefault();
        }
    });

    $('body').keyup(function(e){

        if (e.keyCode == 13 || e.keyCode == 9){

            $(".selectProduct").click();
            $('#list').empty();
            keyName = e.keyCode;
        }
    });

    $('.changeable').on('change', function () {
        document.getElementById('search_product').focus();
    });

    document.getElementById('search_product').focus();

    // Get edit able data
    function getEditablePurchase(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{route('purchases.get.editable.purchase',[$purchaseId, $editType])}}",
            async:true,
            type:'get',
            dataType: 'json',
            success:function(purchase){

                $('#supplier_name').val(purchase.supplier.name +' ('+purchase.supplier.phone+')');
                $('#pay_term').val(purchase.pay_term);
                $('#pay_term_number').val(purchase.pay_term_number);
                $('#invoice_id').val(purchase.invoice_id);
                $('#purchase_status').val(purchase.purchase_status);
                $('#pay').val(purchase.purchase_status);
                $('#paid').val(purchase.paid);
                $('#purchase_account_id').val(purchase.purchase_account_id);
                var product_rows = '';

                if ($.isEmptyObject(purchase.purchase_products)) {

                    product_rows = purchase.purchase_order_products;
                }else{

                    product_rows = purchase.purchase_products;
                }

                $.each(product_rows,function (key, product) {

                    var variant = product.product_variant_id != null ? ' - '+product.variant.variant_name : '';
                    var tr = '';
                    tr += '<tr class="text-start">';
                    tr += '<td>';
                    tr += '<a class="text-success product_name" id="select_product">'+product.product.name + variant +'</a> ';
                    tr += '<input type="hidden" name="descriptions[]" value="'+(product.description != null ? product.description : '')+'" id="description">';
                    tr += '<input value="'+product.product_id+'" type="hidden" class="productId-'+product.product_id+'" id="product_id" name="product_ids[]">';

                    if (product.product_variant_id != null) {

                        tr += '<input value="'+product.product_variant_id+'" class="variantId-'+product.product_variant_id+'" type="hidden" id="variant_id" name="variant_ids[]">';
                    }else{

                        tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                    }

                    tr += '</td>';
                    tr += '<td>';
                    tr += '<input value="'+(product.quantity ? product.quantity : product.order_quantity)+'" required name="quantities[]" type="number" class="form-control" id="quantity">';
                    tr += '<select name="unit_names[]" id="unit_name" class="form-control mt-1">';

                    unites.forEach(function(unit) {

                        if (product.unit == unit) {

                            tr += '<option SELECTED value="'+unit+'">'+unit+'</option>';
                        }else{

                            tr += '<option value="'+unit+'">'+unit+'</option>';
                        }
                    });

                    tr += '</select>';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input value="'+product.unit_cost+'" required name="unit_costs[]" type="text" class="form-control" id="unit_cost">';
                    @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                        tr += '<input name="lot_number[]" placeholder="@lang('Lot No')" type="text" class="form-control mt-1" id="lot_number" value="'+(product.lot_no ? product.lot_no : '')+'">';
                    @endif
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input value="'+product.unit_discount+'" required name="unit_discounts[]" type="text" class="form-control" id="unit_discount">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input readonly value="'+product.unit_cost_with_discount+'" name="unit_costs_with_discount[]" type="text" class="form-control" id="unit_cost_with_discount">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input readonly value="'+product.subtotal+'" required name="subtotals[]" type="text" class="form-control" id="subtotal">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<span>'+product.unit_tax_percent+'%'+'</span>';
                    tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" value="'+product.unit_tax_percent+'">'
                    tr += '<input type="hidden" value="'+product.unit_tax+'" name="unit_taxes[]" id="unit_tax">';
                    tr += '</td>';

                    var unit_cost_inc_tax = parseFloat(product.unit_cost) / 100 * parseFloat(product.unit_tax_percent) + parseFloat(product.unit_cost);

                    tr += '<td>';
                    tr += '<input type="hidden" value="'+unit_cost_inc_tax+'" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax">';
                    tr += '<input readonly value="'+product.net_unit_cost+'" name="net_unit_costs[]" type="text" class="form-control" id="net_unit_cost">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input readonly value="'+product.line_total+'" type="text" name="linetotals[]" id="line_total" class="form-control">';
                    tr += '</td>';

                    @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                        tr += '<td>';
                        tr += '<input value="'+product.product.profit+'" type="text" name="profits[]" class="form-control" id="profit">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="'+product.product.product_price+'" type="text" name="selling_prices[]" class="form-control" id="selling_price">';
                        tr += '</td>';
                    @endif

                    tr += '<td>';
                    tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash"></span></a>';
                    tr += '</td>';

                    tr += '</tr>';
                    $('#purchase_list').prepend(tr);
                });

                $('#total_item').val(purchase.total_item);
                $('#net_total_amount').val(purchase.net_total_amount);
                $('#order_discount_type').val(purchase.order_discount_type);
                $('#order_discount').val(purchase.order_discount);
                $('#order_discount_amount').val(purchase.order_discount_amount);
                $('#purchase_tax').val(purchase.purchase_tax_percent);
                $('#purchase_tax_amount').val(purchase.purchase_tax_amount);
                $('#shipment_details').val(purchase.shipment_details);
                $('#shipment_charge').val(purchase.shipment_charge);
                $('#purchase_note').val(purchase.purchase_note);
                $('#total_purchase_amount').val(purchase.total_purchase_amount);
                $('.label_total_purchase_amount').html(purchase.total_purchase_amount);
            }
        });
    }
    getEditablePurchase();

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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('delivery_date'),
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
</script>
