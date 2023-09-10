@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 94%;z-index: 9999999;padding: 0;left: 3%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 13px;padding: 4px 3px;display: block;}
        .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
        .selectProduct{background-color: #ab1c59; color: #fff!important;}
    </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_purchase_return_form" action="{{ route('purchases.returns.supplier.return.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="text" name="action" id="action">
                <section class="mt-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-8">
                                            <h6>@lang('Add Purchase Return')</h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('Supplier') :</b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select name="supplier_id" class="form-control add_input"
                                                        data-name="Supplier" id="supplier_id">
                                                        <option value="">@lang('Select Supplier')</option>
                                                        @foreach ($suppliers as $sup)
                                                            <option value="{{$sup->id}}">{{ $sup->name.' ('.$sup->phone.')' }}</option>
                                                        @endforeach
                                                    </select>

                                                    <span class="error error_supplier_id"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('B.Location') :</b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control" value="{{auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'] }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('P. Invoice ID') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="@lang('Invoice ID')">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('Warehouse') :</b></label>
                                                <div class="col-8">
                                                    <select class="form-control changeable add_input"
                                                        name="sender_warehouse_id" data-name="Warehouse" id="warehouse_id">
                                                        <option value="">@lang('Select Warehouse')</option>
                                                        @foreach ($warehouses as $w)
                                                            <option value="{{ $w->id }}">{{ $w->warehouse_name.'/'.$w->warehouse_code }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Date') :</b> <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="text" name="date" class="form-control changeable" autocomplete="off"
                                                        value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" id="datepicker">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('Return A/C') : <span
                                                    class="text-danger">*</span></b></label>
                                                <div class="col-8">
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

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Attachment') :</b></label>
                                                <div class="col-8">
                                                    <input type="file" class="form-control" name="attachment">
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
                                                {{-- <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('Item Search')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-barcode text-dark input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" placeholder="@lang('Search Product by product code(SKU) / Scan bar code')">
                                                    </div>
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
                                                </div> --}}
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('Item Search')</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode text-dark input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="@lang('Search Product by product code(SKU) / Scan bar code')" autofocus>
                                                        @if (auth()->user()->permission->product['product_add'] == '1')
                                                            <div class="input-group-prepend">
                                                                <span id="add_product" class="input-group-text add_button"><i class="fas fa-plus-square text-dark input_f"></i></span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>@lang('Product')</th>
                                                                    <th>@lang('Purchase Price')</th>
                                                                    <th>@lang('Current Stock')</th>
                                                                    <th>@lang('Return Quantity')</th>
                                                                    <th>@lang('Return Subtotal')</th>
                                                                    <th><i class="fas fa-trash-alt"></i></th>
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
                                        <div class="col-md-3">
                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-4"><b>@lang('Tax') :</b>  <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select name="purchase_tax" class="form-control" id="purchase_tax">
                                                    </select>
                                                    <input name="purchase_tax_amount" type="number" step="any" class="d-none" id="purchase_tax_amount" value="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label><strong> @lang('Tax Amount (+)') : </strong> <span class="label_purchase_tax_amount">  0.00</span></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4 text-center"><b>@lang('Net Total Amount')</b> : {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly name="total_return_amount" type="number" step="any" id="total_return_amount" class="form-control" value="0.00" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn loading_button d-none"><i
                            class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                        <button type="submit" data-action="save" class="btn btn-sm btn-primary submit_button float-end">@lang('Save')</button>
                        <button type="submit" data-action="save_and_print" class="btn btn-sm btn-primary submit_button float-end me-1">@lang('Save & Print')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="/assets/plugins/custom/select_li/selectli.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function getTaxes(){
            $.ajax({
                url:"{{ route('purchases.get.all.taxes') }}",
                async:false,
                type:'get',
                dataType: 'json',
                success:function(taxes){

                    $('#purchase_tax').append('<option value="0">@lang('No Tax')</option>');

                    $.each(taxes, function(key, val){

                        $('#purchase_tax').append('<option value="'+val.tax_percent+'">'+val.tax_name+'</option>');
                    });
                }
            });
        }
        getTaxes();

        var delay = (function() {

            var timer = 0;
            return function(callback, ms) {

                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();

        $('#search_product').on('input', function(e) {

            $('.variant_list_area').empty();
            $('.select_area').hide();
            var product_code = $(this).val();
            var __product_code = product_code.replaceAll('/', '~');
            var warehouse_id = $('#warehouse_id').val() ? $('#warehouse_id').val() : 'no_id';

            delay(function() { searchProduct(__product_code, warehouse_id); }, 150); //sendAjaxical is the name of remote-command
        });

        // add purchase product by searching product code
        function searchProduct(product_code, warehouse_id){

            $.ajax({

                url:"{{url('purchases/returns/search/product')}}" + "/" + product_code + "/" + warehouse_id,

                dataType: 'json',

                success:function(product){

                    if(!$.isEmptyObject(product.errorMsg)){

                        toastr.error(product.errorMsg);
                        return;
                    }

                    var qty_limit = product.qty_limit;

                    if(
                        !$.isEmptyObject(product.product) ||
                        !$.isEmptyObject(product.variant_product) ||
                        !$.isEmptyObject(product.namedProducts)
                    ) {

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
                                        var presentQty = closestTr.find('#return_quantity').val() ? closestTr.find('#return_quantity').val() : 0;
                                        var qty_limit = closestTr.find('#qty_limit').val();

                                        if(parseFloat(qty_limit) == parseFloat(presentQty)){

                                            alert('Quantity Limit is - '+qty_limit+' '+product.unit.name);
                                            return;
                                        }

                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#return_quantity').val(parseFloat(updateQty).toFixed(2));

                                        //Update Subtotal
                                        var unitCost = closestTr.find('#unit_cost').val();
                                        var calcSubtotal = parseFloat(unitCost) * parseFloat(updateQty);
                                        closestTr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                        closestTr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                        calculateTotalAmount();
                                        return;
                                    }
                                });

                                if(sameProduct == 0){

                                    var tr = '';
                                    tr += '<tr>';
                                    tr += '<td class="text">';
                                    tr += '<span class="product_name">'+product.name+'</span>';
                                    tr += '<span class="product_variant"></span>';
                                    tr += '<span class="product_code">'+' ('+product.product_code+')'+'</span>';
                                    tr += '<input value="'+product.id+'" type="hidden" class="productId-'+product.id+'" id="product_id" name="product_ids[]">';
                                    tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                                    tr += '<input type="hidden" id="qty_limit" value="'+qty_limit+'">';
                                    tr += '<input  name="units[]" type="hidden" id="unit" value="'+product.unit.name+'">';
                                    tr += '</td>';

                                    tr += '<td class="text">';
                                    tr += '<input name="unit_costs[]" type="number" step="any" class="form-control" id="unit_cost" value="'+product.product_cost_with_tax+'" autocomplete="off">';
                                    tr += '</td>';

                                    tr += '<td class="text"><span class="span_warehouse_stock">'+qty_limit+' ('+product.unit.name+')'+'</span></td>';

                                    tr += '<td>';
                                    tr += '<input value="1.00" required name="return_quantities[]" type="text" class="form-control" id="return_quantity" autocomplete="off">';
                                    tr += '</td>';

                                    tr += '<td class="text">';
                                    tr += '<span class="span_return_subtotal">'+product.product_cost_with_tax+'</span>';
                                    tr += '<input value="'+product.product_cost_with_tax+'" name="return_subtotals[]" type="hidden" id="return_subtotal">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                    tr += '</td>';
                                    tr += '</tr>';
                                    $('#purchase_return_list').append(tr);
                                    calculateTotalAmount();
                                }
                            }else{

                                var li = "";
                                $.each(product.product_variants, function(key, variant){

                                    li += '<li>';
                                    li += '<a class="select_variant_product" onclick="variantProduct(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-unit="'+product.unit.name+'" data-v_code="'+variant.variant_code+'" data-v_cost="'+variant.variant_cost_with_tax+'" data-v_name="'+variant.variant_name+'" href="#"><img style="width:25px; height:25px;" src="'+imgUrl+'/'+product.thumbnail_photo+'">'+product.name+' ('+variant.variant_name+')'+' - Purchase Price: '+ variant.variant_cost_with_tax +'</a>';
                                    li +='</li>';
                                });

                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        }else if(!$.isEmptyObject(product.variant_product)){

                            $('.select_area').hide();
                            $('#search_product').val('');

                            var variant_product = product.variant_product;
                            var variant_ids = document.querySelectorAll('#variant_id');
                            var sameVariant = 0;

                            variant_ids.forEach(function(input){

                                if(input.value != 'noid'){

                                    if(input.value == variant_product.id){

                                        sameVariant += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.'+className).closest('tr');
                                        var presentQty = closestTr.find('#return_quantity').val() ? closestTr.find('#return_quantity').val() : 0;
                                        var qty_limit = closestTr.find('#qty_limit').val();

                                        if(parseFloat(qty_limit) == parseFloat(presentQty)){

                                            alert('Quantity Limit is - '+qty_limit+' '+variant_product.product.unit.name);
                                            return;
                                        }

                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#return_quantity').val(parseFloat(updateQty).toFixed(2));
                                        //Update Subtotal
                                        var unitPrice = closestTr.find('#unit_cost').val();
                                        var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                        closestTr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                        closestTr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                        calculateTotalAmount();
                                        return;
                                    }
                                }
                            });

                            if(sameVariant == 0){

                                var tr = '';
                                tr += '<tr>';
                                tr += '<td colspan="2" class="text">';
                                tr += '<span class="product_name">'+variant_product.product.name+'</span>';
                                tr += '<span class="product_variant">'+' -'+variant_product.variant_name+'- '+'</span>';
                                tr += '<span class="product_code">'+'('+variant_product.variant_code+')'+'</span>';
                                tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                                tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                                tr += '<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                                tr += '<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                                tr += '<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                                tr += '<input type="hidden" id="qty_limit" value="'+qty_limit+'">';
                                tr += '<input  name="units[]" type="hidden" id="unit" value="'+variant_product.product.unit.name+'">';
                                tr += '</td>';

                                tr += '<td class="text">';
                                tr += '<input name="unit_costs[]" type="number" step="any" class="form-control" id="unit_cost" value="'+variant_product.variant_cost_with_tax+'" autocomplete="off">';
                                tr += '</td>';

                                tr += '<td class="text"><span class="span_warehouse_stock">'+qty_limit+' ('+variant_product.product.unit.name+')'+'</span></td>';

                                tr += '<td>';
                                tr += '<input value="1.00" required name="return_quantities[]" type="text" class="form-control text-center" id="return_quantity" autocomplete="off">';
                                tr += '</td>';

                                tr += '<td class="text">';
                                tr += '<span class="span_return_subtotal">'+variant_product.variant_cost_with_tax+'</span>';
                                tr += '<input value="'+variant_product.variant_cost_with_tax+'"  name="return_subtotals[]" type="hidden" id="return_subtotal">';
                                tr += '</td>';

                                tr += '<td class="text-center">';
                                tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                tr += '</td>';
                                tr += '</tr>';
                                $('#purchase_return_list').append(tr);
                                calculateTotalAmount();
                            }
                        }else if (!$.isEmptyObject(product.namedProducts)) {

                            if(product.namedProducts.length > 0){

                                var imgUrl = "{{asset('uploads/product/thumbnail')}}";
                                var li = "";
                                var products = product.namedProducts;

                                $.each(products, function (key, product) {

                                    if (product.is_variant == 1) {

                                        li += '<li>';
                                        li += '<a class="select_variant_product" onclick="variantProduct(this); return false;" data-p_id="'+product.id+'" data-v_id="'+product.variant_id+'" data-p_name="'+product.name+'" data-unit="'+product.unit_name+'" data-v_code="'+product.variant_code+'" data-v_cost="'+product.variant_cost_with_tax+'" data-v_name="'+product.variant_name+'" href="#"><img style="width:25px; height:25px;" src="'+imgUrl+'/'+product.thumbnail_photo+'">'+product.name+' ('+product.variant_name+')'+' - Purchase Price : '+product.variant_cost_with_tax+'</a>';
                                        li +='</li>';
                                    }else{

                                        li += '<li>';
                                        li += '<a class="select_single_product" onclick="singleProduct(this); return false;" data-p_id="'+product.id+'" data-p_name="'+product.name+'" data-unit="'+product.unit_name+'" data-p_code="'+product.product_cost_with_tax+'" data-p_cost="'+product.product_cost_with_tax+'" href="#"><img style="width:25px; height:25px;" src="'+imgUrl+'/'+product.thumbnail_photo+'">'+product.name+' - Purchase Price : '+product.product_cost_with_tax+'</a>';
                                        li +='</li>';
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

        // select Single product and add purchase return table
        function singleProduct(e){

            $('.select_area').hide();
            $('#search_product').val('');
            var product_id = e.getAttribute('data-p_id');
            var warehouse_id = $('#warehouse_id').val() ? $('#warehouse_id').val() : 'no_id';

            var product_name = e.getAttribute('data-p_name');
            var product_unit = e.getAttribute('data-unit');
            var p_code = e.getAttribute('data-p_code');
            var p_cost = e.getAttribute('data-p_cost');

            $.ajax({

                url:"{{url('purchases/returns/check/single/product/stock/')}}" + "/" + product_id + "/" + warehouse_id,
                type:'get',
                dataType: 'json',
                success:function(stock){

                    if($.isEmptyObject(stock.errorMsg)){

                        var product_ids = document.querySelectorAll('#product_id');
                        var sameProduct = 0;

                        product_ids.forEach(function(input){

                            if(input.value != 'noid'){

                                if(input.value == product_id){

                                    sameProduct += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.'+className).closest('tr');
                                    var presentQty = closestTr.find('#return_quantity').val() ? closestTr.find('#return_quantity').val() : 0;
                                    var qty_limit = closestTr.find('#qty_limit').val();

                                    if(parseFloat(qty_limit) === parseFloat(presentQty)){

                                        alert('Quantity Limit is - '+qty_limit+' '+product_unit);
                                        return;
                                    }

                                    var updateQty = parseFloat(presentQty) + 1;

                                    closestTr.find('#return_quantity').val(parseFloat(updateQty).toFixed(2));

                                    //Update Subtotal
                                    var unitCost = closestTr.find('#unit_cost').val();
                                    var calcSubtotal = parseFloat(unitCost) * parseFloat(updateQty);
                                    closestTr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    return;
                                }
                            }
                        });

                        if(sameProduct == 0){

                            var tr = '';
                            tr += '<tr>';
                            tr += '<td class="text">';
                            tr += '<span class="product_name">'+product_name+'</span>';
                            tr += '<span class="product_variant"></span>';
                            tr += '<span class="product_code"></span>';
                            tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                            tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                            tr += '<input  name="units[]" type="hidden" id="unit" value="'+product_unit+'">';
                            tr += '<input type="hidden" id="qty_limit" value="'+stock+'">';
                            tr += '</td>';

                            tr += '<td class="text">';
                            tr += '<input required name="unit_costs[]" type="number" step="any" class="form-control" id="unit_cost" value="'+p_cost+'">';
                            tr += '</td>';

                            tr += '<td class="text"><span class="span_warehouse_stock">'+stock+' ('+product_unit+')'+'</span></td>';

                            tr += '<td>';
                            tr += '<input value="1.00" required name="return_quantities[]" type="text" class="form-control" id="return_quantity" autocomplete="off">';
                            tr += '</td>';

                            tr += '<td class="text">';
                            tr += '<span class="span_return_subtotal">'+p_cost+'</span>';
                            tr += '<input value="'+p_cost+'" name="return_subtotals[]" type="hidden" id="return_subtotal">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';
                            $('#purchase_return_list').append(tr);
                            calculateTotalAmount();
                        }
                    }else{

                        toastr.error(stock.errorMsg);
                    }
                }
            });
        };

        // select variant product and add purchase table
        function variantProduct(e){

            $('.select_area').hide();
            $('#search_product').val('');
            var product_id = e.getAttribute('data-p_id');
            var warehouse_id = $('#warehouse_id').val() ? $('#warehouse_id').val() : 'no_id';

            var product_name = e.getAttribute('data-p_name');
            var product_unit = e.getAttribute('data-unit');
            var variant_id = e.getAttribute('data-v_id');
            var variant_name = e.getAttribute('data-v_name');
            var variant_code = e.getAttribute('data-v_code');
            var variant_cost = e.getAttribute('data-v_cost');

            $.ajax({

                url:"{{url('purchases/returns/check/variant/product/stock/')}}"+ "/" + product_id+ "/" +variant_id+ "/" + warehouse_id,

                type:'get',

                dataType: 'json',

                success:function(stock) {

                    if($.isEmptyObject(stock.errorMsg)) {

                        var variant_ids = document.querySelectorAll('#variant_id');
                        var sameVariant = 0;

                        variant_ids.forEach(function(input){

                            if(input.value != 'noid'){

                                if(input.value == variant_id){

                                    sameVariant += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.'+className).closest('tr');

                                    var presentQty = closestTr.find('#return_quantity').val() ? closestTr.find('#return_quantity').val() : 0;
                                    var qty_limit = closestTr.find('#qty_limit').val();

                                    if(parseFloat(qty_limit) === parseFloat(presentQty)){

                                        alert('Quantity Limit is - '+qty_limit+' '+product_unit);
                                        return;
                                    }

                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#return_quantity').val(parseFloat(updateQty).toFixed(2));

                                    //Update Subtotal
                                    var unitCost = closestTr.find('#unit_cost').val();
                                    var calcSubtotal = parseFloat(unitCost) * parseFloat(updateQty);
                                    closestTr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    return;
                                }
                            }
                        });

                        if(sameVariant == 0){

                            var tr = '';
                            tr += '<tr>';

                            tr += '<td class="text">';
                            tr += '<span class="product_name">'+product_name+'</span>';
                            tr += '<span class="product_variant">'+' -'+variant_name+'- '+'</span>';
                            tr += '<span class="product_code">'+'('+variant_code+')'+'</span>';
                            tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                            tr += '<input value="'+variant_id+'" type="hidden" class="variantId-'+variant_id+'" id="variant_id" name="variant_ids[]">';
                            tr += '<input  name="units[]" type="hidden" id="unit" value="'+product_unit+'">';
                            tr += '<input type="hidden" id="qty_limit" value="'+stock+'">';
                            tr += '</td>';

                            tr += '<td class="text">';
                            tr += '<input name="unit_costs[]" type="number" step="any" class="form-control" id="unit_cost" value="'+variant_cost+'">';
                            tr += '</td>';

                            tr += '<td class="text"><span class="span_warehouse_stock">'+stock+' ('+product_unit+')'+'</span></td>';

                            tr += '<td>';
                            tr += '<input value="1.00" required name="return_quantities[]" type="text" class="form-control" id="return_quantity" autocomplete="off">';
                            tr += '</td>';

                            tr += '<td class="text">';
                            tr += '<span class="span_return_subtotal">' + variant_cost + '</span>';
                            tr += '<input value="'+variant_cost+'" name="return_subtotals[]" type="hidden" id="return_subtotal">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';
                            $('#purchase_return_list').append(tr);
                            calculateTotalAmount();
                        }
                    } else {

                        toastr.error(stock.errorMsg);
                    }
                }
            });
        };

        // Calculate total amount functionalitie
        function calculateTotalAmount(){

            var subtotals = document.querySelectorAll('#return_subtotal');
            // Update Net total Amount
            var netTotalAmount = 0;

            subtotals.forEach(function(subtotal){

                netTotalAmount += parseFloat(subtotal.value);
            });

            var purchaseTaxAmount = $('#purchase_tax_amount').val() ? $('#purchase_tax_amount').val() : 0;

            var calcTotalReturnAmount = parseFloat(netTotalAmount) + parseFloat(purchaseTaxAmount);

            $('.span_total_return_amount').html(parseFloat(calcTotalReturnAmount).toFixed(2));

            $('#total_return_amount').val(parseFloat(calcTotalReturnAmount).toFixed(2));
        }

        // Quantity increase or dicrease and clculate row amount
        $(document).on('input', '#return_quantity', function() {

            var qty = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            //Update subtotal
            var qty_limit = tr.find('#qty_limit').val();
            var unit = tr.find('#unit').val();

            if(parseInt(qty) > parseInt(qty_limit)){

                alert('Quantity Limit Is - '+qty_limit+' '+unit);
                $(this).val(qty_limit);
                var unitCost = tr.find('#unit_cost').val();
                var calcReturnSubtotal = parseFloat(unitCost) * parseFloat(qty_limit);
                tr.find('#return_subtotal').val(parseFloat(calcReturnSubtotal).toFixed(2));
                tr.find('.span_return_subtotal').html(parseFloat(calcReturnSubtotal).toFixed(2));
                calculateTotalAmount();
                return;
            } else{

                var unitCost = tr.find('#unit_cost').val();
                var calcReturnSubtotal = parseFloat(unitCost) * parseFloat(qty);
                tr.find('#return_subtotal').val(parseFloat(calcReturnSubtotal).toFixed(2));
                tr.find('.span_return_subtotal').html(parseFloat(calcReturnSubtotal).toFixed(2));
                calculateTotalAmount();
            }
        });

        // Return Quantity increase or dicrease and clculate row amount
        $(document).on('input', '#unit_cost', function(){

            var unitCost = $(this).val() ? $(this).val() : 0;

            var tr = $(this).closest('tr');

            var return_quantity = tr.find('#return_quantity').val() ? tr.find('#return_quantity').val() : 0;

            var calcSubtotal = parseFloat(unitCost) * parseFloat(return_quantity);

            tr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            tr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
            calculateTotalAmount();
        });

        // chane purchase tax and clculate total amount
        $(document).on('change', '#purchase_tax', function() {

            var purchaseTax = $(this).val() ? $(this).val() : 0;
            var totalReturnAmount = $('#total_return_amount').val();
            var calcPurchaseTaxAmount = parseFloat(totalReturnAmount) / 100 * parseFloat(purchaseTax);
            $('.label_purchase_tax_amount').html(parseFloat(calcPurchaseTaxAmount).toFixed(2));
            $('#purchase_tax_amount').val(parseFloat(calcPurchaseTaxAmount).toFixed(2));
            calculateTotalAmount();
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

        $('.submit_button').on('click', function () {

            var value = $(this).val();
            $('#action').val(value);
        });

        //Add purchase request by ajax
        $('#add_purchase_return_form').on('submit', function(e){
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            $('.submit_button').prop('type', 'button');
            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){

                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'sumbit');
                    $('.error').html('');

                    if(!$.isEmptyObject(data.errorMsg)){

                        toastr.error(data.errorMsg,'ERROR');
                    }else if (data.successMsg) {

                        toastr.success(data.successMsg);
                        $('#add_purchase_return_form')[0].reset();
                        $('#purchase_return_list').empty();
                    } else{

                        toastr.success('Successfully Purchase return is added.');
                        $('#add_purchase_return_form')[0].reset();
                        $('#purchase_return_list').empty();

                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{asset('assets/css/print/purchase.print.css')}}",
                            removeInline: false,
                            printDelay: 1000,
                            header: null,
                        });
                    }
                },error: function(err) {

                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if(err.status == 500) {

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

        $('body').keyup(function(e) {
            e.preventDefault();

            if (e.keyCode == 13){

                $(".selectProduct").click();
                $('#list').empty();
            }
        });

        $(document).keypress(".scanable",function(event){

            if (event.which == '10' || event.which == '13') {

                event.preventDefault();
            }
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

        $(document).on('change', '#warehouse_id', function () {

            $('#purchase_return_list').empty();
            calculateTotalAmount();
        });

        document.onkeyup = function () {
            var e = e || window.event; // for IE to cover IEs window event-object

            if(e.ctrlKey && e.which == 13) {

                $('#save_and_print').click();
                return false;
            }else if (e.shiftKey && e.which == 13) {

                $('#save').click();
                return false;
            }
        }
    </script>
@endpush
