@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute; width: 94%;z-index: 9999999;padding: 0;left: 6%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 13px;padding: 4px 3px;display: block;}
        .select_area ul li a:hover {background-color: #999396;color: #fff;}
        .selectProduct{background-color: #746e70; color: #fff!important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        table.display td input {height: 26px!important; padding: 3px;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_process_form" action="{{ route('manufacturing.process.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['p_id'] }}">
                <input type="hidden" name="variant_id" value="{{ $product['v_id'] ? $product['v_id'] : 'noid' }}">
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>@lang('Create Process')</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @php
                                                $p_code = $product['v_code'] ? $product['v_code'] : $product['p_code'];
                                            @endphp
                                            <p> <strong>@lang('Product') :</strong> {{ $product['p_name'].' '.$product['v_name'].' ('.$p_code.')' }}</p>
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
                                            <div class="col-md-6 offset-md-3">
                                                <div class="searching_area" style="position: relative;">
                                                    <label for="inputEmail3" class="col-form-label">@lang('Select Ingredients')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode text-dark"></i></span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" placeholder="@lang('Search Ingredient by product code(SKU) / Scan bar code')" autofocus>
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
                                                                    <th>@lang('Ingredient')</th>
                                                                    <th>@lang('Final Quantity')</th>
                                                                    <th>@lang('Unit')</th>
                                                                    <th>@lang('Unit Cost')</th>
                                                                    <th>@lang('SubTotal')</th>
                                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="ingredient_list"></tbody>
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

                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="total_ingredient_cost" id="total_ingredient_cost">
                        <p class="mt-1 float-end clearfix"><strong>@lang('Total Ingrediant Cost') : </strong> <span id="span_total_ingredient_cost">0.00</span></p>
                    </div>
                </div>
                
                <section class="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><b>@lang('Total Output Qty') :</b></label>
                                            <div class="row">
                                                <div class="col-7">
                                                    <input type="number" step="any" name="total_output_qty" class="form-control" autocomplete="off" id="total_output_qty" placeholder="@lang('Total Output Quantity')" value="1.00">
                                                </div>

                                                <div class="col-5">
                                                    <select name="unit_id" class="form-control" id="unit_id"></select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label><b>@lang('Addtional Production Cost') :</b></label>
                                            <input type="number" step="any" name="production_cost" class="form-control" autocomplete="off" id="production_cost" placeholder="@lang('Production Cost')" value="0">
                                        </div>

                                        <div class="col-md-3">
                                            <label><b>@lang('Total Cost') :</b></label>
                                            <input required type="number" step="any" name="total_cost" class="form-control" autocomplete="off" id="total_cost" placeholder="@lang('Total Cost')">
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
                            <button class="btn btn-sm btn-primary submit_button float-end">@lang('Save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="/assets/plugins/custom/select_li/selectli.js"></script>
    <script>
        var unit_id = "{{ $product['unit_id'] }}";
        var unites = [];
        function getUnites(){

            $.ajax({
                url:"{{ route('purchases.get.all.unites') }}",
                success:function(units){
                    $.each(units, function(key, unit){

                        $('#unit_id').append('<option '+(unit.id == unit_id ? 'SELECTED' : '')+' value="'+unit.id+'">'+unit.name+'</option>');
                        unites.push({id : unit.id, name : unit.name}); 
                    });
                }
            });
        }
        getUnites();

        $('#search_product').on('input', function(e) {

            $('.variant_list_area').empty();
            $('.select_area').hide();
            var product_code = $(this).val();
            delay(function() { searchProduct(product_code); }, 200); //sendAjaxical is the name of remote-command
        });

        var delay = (function() {

            var timer = 0;
            return function(callback, ms) { clearTimeout (timer); timer = setTimeout(callback, ms);};
        })();

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

                    if(!$.isEmptyObject(product.product) || !$.isEmptyObject(product.variant_product) || !$.isEmptyObject(product.namedProducts)) {
                        
                        $('#search_product').addClass('is-valid');

                        if(!$.isEmptyObject(product.product)){

                            var product = product.product;
                            if(product.product_variants.length == 0) {

                                $('.select_area').hide();
                                $('#search_product').val('');
                                product_ids = document.querySelectorAll('#product_id');
                                var sameProduct = 0;
                                product_ids.forEach(function(input){

                                    if(input.value == product.id){

                                        sameProduct += 1;
                                        var className = input.getAttribute('class');
                                        var closestTr = $('.'+className).closest('tr');
                                        // update same product qty 
                                        var presentQty = closestTr.find('#final_quantity').val();
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#final_quantity').val(updateQty);
                                        var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                                        // update subtotal
                                        var totalCost = parseFloat(unitCostIncTax) * parseFloat(updateQty); 
                                        closestTr.find('#subtotal').val(parseFloat(totalCost).toFixed(2));
                                        closestTr.find('#span_subtotal').html(parseFloat(totalCost).toFixed(2));
                                        __calculateTotalAmount();
                                        return;
                                    }
                                });

                                if(sameProduct == 0){

                                    var tr = '';
                                    tr += '<tr class="text-start">';
                                    tr += '<td>';
                                    tr += '<span class="product_name">'+product.name+'</span><br>';
                                    tr += '<span class="product_variant"></span>';  
                                    tr += '<input value="'+product.id+'" type="hidden" class="productId-'+product.id+'" id="product_id" name="product_ids[]">';
                                    tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="1" required name="final_quantities[]" type="number" step="any" class="form-control text-center" id="final_quantity">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<select name="unit_ids[]" id="unit_id" class="form-control">';
                                        unites.forEach(function(unit) {

                                            if (product.unit.id == unit.id) {

                                                tr += '<option SELECTED value="'+unit.id+'">'+unit.name+'</option>'; 
                                            }else{

                                                tr += '<option value="'+unit.id+'">'+unit.name+'</option>';   
                                            }
                                        })
                                    tr += '</select>';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input readonly value="'+product.product_cost_with_tax+'" type="text" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" class="form-control text-center">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input readonly value="'+product.product_cost_with_tax+'" type="text" name="subtotals[]" id="subtotal" class="form-control text-center">';
                                    tr += '</td>';

                                    tr += '<td class="text-start">';
                                    tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
                                    tr += '</td>';
                                    
                                    tr += '</tr>';
                                    $('#ingredient_list').prepend(tr); 
                                    __calculateTotalAmount();  
                                }
                            } else {

                                var li = "";
                                $.each(product.product_variants, function(key, variant){
                                    li += '<li>';
                                    li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'"  data-unit="'+product.unit.id+'" data-v_code="'+variant.variant_code+'" data-v_cost="'+variant.variant_cost+'" data-v_cost_with_tax="'+variant.variant_cost_with_tax+'"  data-v_name="'+variant.variant_name+'" href="#">'+product.name+' - '+variant.variant_name+' ('+variant.variant_code+')'+' - Unit Cost: '+variant.variant_cost_with_tax+'</a>';
                                    li +='</li>';
                                });
                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        } else if(!$.isEmptyObject(product.namedProducts)) {

                            if(product.namedProducts.length > 0){

                                var li = "";
                                var products = product.namedProducts; 

                                $.each(products, function (key, product) {

                                    if (product.is_variant == 1) {
                                      
                                            li += '<li class="mt-1">';
                                            li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="'+product.id+'" data-v_id="'+product.variant_id+'" data-p_name="'+product.name+'"  data-unit="'+product.unit_id+'" data-v_code="'+product.variant_code+'" data-v_cost="'+product.variant_cost+'" data-v_cost_with_tax="'+product.variant_cost_with_tax+'" data-v_name="'+product.variant_name+'" href="#">'+product.name+' - '+product.variant_name+' ('+product.variant_code+')'+' - Unit Cost: '+product.variant_cost_with_tax+'</a>';
                                            li +='</li>';
                                    } else {

                                        li += '<li class="mt-1">';
                                        li += '<a class="select_single_product" onclick="singleProduct(this); return false;" data-p_id="'+product.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit_id+'" data-p_code="'+product.product_code+'" data-p_cost_with_tax="'+product.product_cost_with_tax+'" data-p_name="'+product.name+'" href="#">'+product.name+' ('+product.product_code+')'+' - Unit Cost: '+product.product_cost_with_tax+'</a>';
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
                            var variant_ids = document.querySelectorAll('#variant_id');
                            var sameVariant = 0;

                            variant_ids.forEach(function(input){

                                if(input.value != 'noid'){

                                    if(input.value == variant_product.id){

                                        sameVariant += 1;
                                        var className = input.getAttribute('class');
                                        var closestTr = $('.'+className).closest('tr');
                                        // update same product qty 
                                        var presentQty = closestTr.find('#final_quantity').val();
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#final_quantity').val(updateQty);
                                        var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                                        // update subtotal
                                        var totalCost = parseFloat(unitCostIncTax) * parseFloat(updateQty); 
                                        closestTr.find('#subtotal').val(parseFloat(totalCost).toFixed(2));
                                        __calculateTotalAmount();
                                        return;
                                    }
                                }    
                            });
                            
                            if(sameVariant == 0){

                                var tr = '';
                                tr += '<tr class="text-center">';
                                tr += '<td>';
                                tr += '<span class="product_name">'+variant_product.product.name+'</span>';
                                tr += '<span class="product_variant">('+variant_product.variant_name+')</span>';  
                                tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                                tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="1" required name="final_quantities[]" type="number" step="any" class="form-control text-start" id="final_quantity">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<select name="unit_ids[]" id="unit_id" class="form-control">';

                                unites.forEach(function(unit) {

                                    if (product.unit.id == unit.id) {

                                        tr += '<option SELECTED value="'+unit.id+'">'+unit.name+'</option>'; 
                                    } else{

                                        tr += '<option value="'+unit.id+'">'+unit.name+'</option>';   
                                    }
                                });

                                tr += '</select>';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input readonly value="'+variant_product.variant_cost_with_tax+'" type="text" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" class="form-control text-start">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input readonly value="'+variant_product.variant_cost_with_tax+'" type="text" name="subtotals[]" id="subtotal" class="form-control text-start">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash"></span></a>';
                                tr += '</td>';
                                
                                tr += '</tr>';
                                $('#purchase_list').prepend(tr);
                                __calculateTotalAmount(); 
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

            var productId = e.getAttribute('data-p_id');
            var productName = e.getAttribute('data-p_name');
            var productUnit = e.getAttribute('data-unit');
            var productCode = e.getAttribute('data-p_code');
            var productCostIncTax = e.getAttribute('data-p_cost_with_tax'); 
            product_ids = document.querySelectorAll('#product_id');
            var sameProduct = 0;
            product_ids.forEach(function(input){

                if(input.value == productId){

                    sameProduct += 1;
                    var className = input.getAttribute('class');
                    var closestTr = $('.'+className).closest('tr');
                    // update same product qty 
                    var presentQty = closestTr.find('#final_quantity').val();
                    var updateQty = parseFloat(presentQty) + 1;
                    closestTr.find('#final_quantity').val(updateQty);
                    var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                    // update subtotal
                    var totalCost = parseFloat(unitCostIncTax) * parseFloat(updateQty); 
                    closestTr.find('#subtotal').val(parseFloat(totalCost).toFixed(2));
                    __calculateTotalAmount();

                    if (keyName == 9) {

                        closestTr.find('#final_quantity').focus();
                        closestTr.find('#final_quantity').select();
                        keyName = 1;
                    }
                    return;
                }
            });

            if(sameProduct == 0){

                var tr = '';
                tr += '<tr class="text-start">';
                tr += '<td>';
                tr += '<span class="product_name">'+productName+'</span><br>';
                tr += '<span class="product_variant"></span>';  
                tr += '<input value="'+productId+'" type="hidden" class="productId-'+productId+'" id="product_id" name="product_ids[]">';
                tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input value="1" required name="final_quantities[]" type="number" step="any" class="form-control text-center" id="final_quantity">';
                tr += '</td>';

                tr += '<td>';
                tr += '<select name="unit_ids[]" id="unit_id" class="form-control">';

                unites.forEach(function(unit) {

                    if (productUnit == unit.id) {

                        tr += '<option SELECTED value="'+unit.id+'">'+unit.name+'</option>'; 
                    }else{

                        tr += '<option value="'+unit.id+'">'+unit.name+'</option>';   
                    }
                });

                tr += '</select>';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly value="'+productCostIncTax+'" type="text" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" class="form-control text-center">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly value="'+productCostIncTax+'" type="text" name="subtotals[]" id="subtotal" class="form-control text-center">';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
                tr += '</td>';
                
                tr += '</tr>';
                $('#ingredient_list').prepend(tr); 

                __calculateTotalAmount();  
                if (keyName == 9) {
                    $("#final_quantity").select();
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
            $('#search_product').val('');

            var productId = e.getAttribute('data-p_id');
            var productName = e.getAttribute('data-p_name');
            var productUnit = e.getAttribute('data-unit');
            var productCode = e.getAttribute('data-p_code');
            var variantId = e.getAttribute('data-v_id');
            var variantName = e.getAttribute('data-v_name');
            var variantCode = e.getAttribute('data-v_code');
            var variantCost = e.getAttribute('data-v_cost'); 
            variant_id = document.querySelectorAll('#variant_id');

            __calculateTotalAmount();
            var sameVariant = 0;

            variant_id.forEach(function(input){

                if(input.value != 'noid'){

                    if(input.value == variantId){

                        sameVariant += 1;
                        var className = input.getAttribute('class');
                        var closestTr = $('.'+className).closest('tr');
                        // update same product qty 
                        var presentQty = closestTr.find('#final_quantity').val();
                        var updateQty = parseFloat(presentQty) + 1;
                        closestTr.find('#final_quantity').val(updateQty);
                        var unitCostIncTax = closestTr.find('#unit_cost_inc_tax').val();
                        // update subtotal
                        var totalCost = parseFloat(unitCostIncTax) * parseFloat(updateQty); 
                        closestTr.find('#subtotal').val(parseFloat(totalCost).toFixed(2));
                        __calculateTotalAmount();
                        return;
                    }
                }    
            });

            if(sameVariant == 0){

                var tr = '';
                tr += '<tr>';
                tr += '<td class="text-start">';
                tr += '<span class="product_name">'+productName+'</span>';
                tr += '<span class="product_variant">('+variantName+')</span>';  
                tr += '<input value="'+productId+'" type="hidden" class="productId-'+productId+'" id="product_id" name="product_ids[]">';
                tr += '<input value="'+variantId+'" type="hidden" class="variantId-'+variantId+'" id="variant_id" name="variant_ids[]">';
                tr += '<input type="hidden" value="'+variantCost+'" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax">';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<input value="1" required name="final_quantities[]" type="number" step="any" class="form-control text-center" id="final_quantity">';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<select name="unit_ids[]" id="unit_id" class="form-control">';

                unites.forEach(function(unit) {

                    if (productUnit == unit.id) {

                        tr += '<option SELECTED value="'+unit.id+'">'+unit.name+'</option>'; 
                    } else {

                        tr += '<option value="'+unit.id+'">'+unit.name+'</option>';   
                    }
                });

                tr += '</select>';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<input readonly value="'+variantCost+'" type="text" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" class="form-control text-center">';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<input readonly value="'+variantCost+'" type="text" name="subtotals[]" id="subtotal" class="form-control text-center">';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash"></span></a>';
                tr += '</td>';
                
                tr += '</tr>';
                $('#ingredient_list').prepend(tr);
                __calculateTotalAmount();

                if (keyName == 9) {
                    
                    $("#final_quantity").select();
                    keyName = 1;
                }
            }
        }

        // Quantity increase or dicrease and clculate row amount
        $(document).on('input', '#final_quantity', function(){
            var tr = $(this).closest('tr');
            __calculateIngredientsTableAmount(tr);
        });

        $(document).on('input', '#production_cost', function(){
            var tr = $(this).closest('tr');
            __calculateTotalAmount();
        });

        function __calculateIngredientsTableAmount(tr) {
            var qty = tr.find('#final_quantity').val() ? tr.find('#final_quantity').val() : 0;
            //Update subtotal 
            var unitCostIncTax = tr.find('#unit_cost_inc_tax').val();
            var totalCost = parseFloat(unitCostIncTax) * parseFloat(qty);
            var subtotal = tr.find('#subtotal').val(parseFloat(totalCost).toFixed(2));
            __calculateTotalAmount();
        }

        function __calculateTotalAmount(){
            var subtotals = document.querySelectorAll('#subtotal');
            var totalIngredientCost = 0;
            subtotals.forEach(function(price){
                totalIngredientCost += parseFloat(price.value);
            });

            $('#total_ingredient_cost').val(parseFloat(totalIngredientCost));
            $('#span_total_ingredient_cost').html(parseFloat(totalIngredientCost).toFixed(2));
            var productionCost = $('#production_cost').val() ? $('#production_cost').val() : 0;
            var totalCost =  parseFloat(totalIngredientCost) + parseFloat(productionCost);
            $('#total_cost').val(parseFloat(totalCost).toFixed(2));
        }

        // Remove product form ingredient list (Table) 
        $(document).on('click', '#remove_product_btn',function(e){
            e.preventDefault();
            $(this).closest('tr').remove();
            __calculateTotalAmount();
        });

        //Add process request by ajax
        $('#add_process_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $('.submit_button').prop('type', 'button');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    $('.submit_button').prop('type', 'sumbit');
                    if(!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg); 
                        $('.loading_button').hide();
                    } else {
                        $('.loading_button').hide();
                        toastr.success(data); 
                        window.location = "{{ route('manufacturing.process.index') }}";
                    }
                },error: function(err) {
                    
                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();
                    $('.error').html('');
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.'); 
                    }else{

                        toastr.error('Server error please contact to the support.');
                    }
                }
            });
        });

        $('body').keyup(function(e){
            if (e.keyCode == 13){  
                $(".selectProduct").click();
                $('#list').empty();
            }
        });

        $(document).keypress(".scanable",function(event) {
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();
            }
        });

        setInterval(function(){$('#search_product').removeClass('is-invalid');}, 500); 
        setInterval(function(){$('#search_product').removeClass('is-valid');}, 1000);
    </script>
@endpush
