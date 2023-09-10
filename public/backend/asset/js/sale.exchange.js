//data delete by ajax
$(document).on('submit', '#search_inv_form', function (e) {
    e.preventDefault();
    $('#get_inv_preloader').show();
    var url = $(this).attr('action');
    var request = $(this).serialize();
    $.ajax({
        url: url,
        type: 'get',
        data: request,
        success: function (data) {

            $('#get_inv_preloader').hide();
            $('#invoice_description').empty();
            if (!$.isEmptyObject(data.errorMsg)) {

                toastr.error(data.errorMsg);
            } else {

                $('#invoice_description').html(data);
            }
        }
    });
});

$('#submit_form_btn').on('click', function (e) {
    e.preventDefault();

    $('#search_inv_form').submit();
});

$('#exchange_btn').on('click', function (e) {
    e.preventDefault();

    $('#invoice_description').empty(); $('#invoice_id').val('');
});

$(document).on('input', '#ex_quantity',function () {
    var ex_qty = $(this).val();
    var closestTr = $(this).closest('tr');
    var soldQty = closestTr.find('#sold_quantity').val();
    console.log(soldQty);

    if (parseFloat(ex_qty) < 0) {

        var sum = parseFloat(soldQty) + parseFloat(ex_qty);

        if (sum < 0) {

            toastr.error('Exchange quantity subtraction value must not be greater then sold quantity.');
            $(this).val(- parseFloat(soldQty));
        }
    }
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('submit', '#prepare_to_exchange',function (e) {
    e.preventDefault();

    var url = $(this).attr('action');
    var request = $(this).serialize();
    console.log(request);
    $.ajax({
        url:url,
        type:'post',
        data:request,
        success:function(data){

            if (data.ex_items.length == 0) {

                return;
            }

            var qty_limits = data.qty_limits;
            var tr = '';

            $.each(data.ex_items, function (key, item) {

                var name = item.product.name.substring(0, 30);
                tr += '<tr>';
                tr += '<td class="serial">'+(key + 1)+'</td>';
                tr += '<td class="text-start">';
                tr += '<p class="product-name text-dark" title="'+'SKU-'+(item.variant ? item.variant.variant_code : item.product.product_code )+'">' + name +(item.variant ? ' - '+item.variant.variant_name : '') +'</p><input type="hidden" name="descriptions[]" class="form-control description_input scanable" placeholder="IMEI, Serial number or other info" value="'+(item.description ? item.description : '')+'">';
                tr += '<input value="'+item.product_id+'" type="hidden" name="product_ids[]">';
                tr +='<input value="'+(item.product_variant_id ? item.product_variant_id : 'noid')+'" type="hidden" name="variant_ids[]">';
                tr += '<input value="'+ item.product.tax_type +'" type="hidden" id="tax_type">';
                tr +='<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+item.unit_tax_percent+'">';
                tr +='<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+item.unit_tax_amount+'">';
                tr +='<input value="'+item.unit_discount_type+'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                tr +='<input value="'+item.unit_discount+'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                tr +='<input value="'+item.unit_discount_amount+'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                tr += '<input value="'+item.unit_cost_inc_tax+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_costs_inc_tax">';
                tr += '<input type="hidden" id="previous_qty" value="'+item.quantity+'">';
                tr += '<input type="hidden" id="qty_limit" value="'+qty_limits[key]+'">';
                tr += '<input class="index-'+(key + 1)+'" type="hidden" id="index">';
                tr += '</td>';

                tr += '<td>';
                tr +='<input type="number" name="quantities[]" value="'+item.ex_quantity+'" class="form-control text-center" id="quantity">';
                tr += '</td>';

                tr += '<td>';
                tr += '<b><span class="span_unit">'+item.unit+'</span></b>';
                tr += '<input name="units[]" type="hidden" id="unit" value="'+item.unit+'">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+item.unit_price_exc_tax +'" id="unit_price_exc_tax">';

                tr +='<input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="'+item.unit_price_inc_tax+'">';
                tr += '<b><span class="span_unit_price_inc_tax">' + parseFloat(item.unit_price_inc_tax).toFixed(2) + '</span> </b>';
                tr += '</td>';

                tr += '<td>';
                var ex_quantity = parseFloat(item.ex_quantity);
                var subtotal = parseFloat(item.unit_price_inc_tax) * parseFloat(ex_quantity);
                tr += '<input value="'+parseFloat(subtotal).toFixed(2)+'" name="subtotals[]" type="hidden" id="subtotal">';
                tr += '<b><span class="span_subtotal">' + parseFloat(subtotal).toFixed(2) + '</span></b>';
                tr += '</td>';
                tr +='<td><a href="#" class="action-btn c-delete"><span class="fas fa-trash text-dark"></span></a></td>';
                tr += '</tr>';
            });

            $('#product_list').empty();
            $('#product_list').prepend(tr);
            $('#pos_submit_form')[0].reset();

            $('#ex_sale_id').val(data.sale.id);

            $('#order_discount_type').val(data.sale.order_discount_type);

            if (data.sale.order_discount_type == 1) {

                $('#order_discount').val('-' + data.sale.order_discount);
            }else{

                $('#order_discount').val(data.sale.order_discount);
            }

            
            $('#order_discount_amount').val('-'+data.sale.order_discount_amount);

            //$('#previous_due').val(data.sale.due);

            $('#customer_id').val(data.sale.customer_id);
            calculateTotalAmount();
            var exchange_url = $('#exchange_url').val();
            $('#pos_submit_form').attr('action', exchange_url);
            $('#exchangeModal').modal('hide');
        }
    });
});

