@extends('layout.master')
@push('stylesheets')
    <style>
         .select_area {background: #ffffff;box-sizing: border-box;position: absolute;width: 64.2%;z-index: 9999999;padding: 0;left: 17.9%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 13px;padding: 4px 3px;display: block;border: 1px solid lightgray;}
        .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
    </style>
@endpush
{{-- @section('title', 'Generate Barcode - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h5>@lang('Generate Barcode')</h5>
                            </div>
                        </div>
                    </div>

                    <!-- =========================================top section button=================== -->
                    <div class="row mt-1">
                        <div class="card">
                            <div class="card ">
                                <form id="multiple_completed_form"
                                    action="{{ route('barcode.multiple.genereate.completed') }}" method="post">
                                    @csrf
                                </form>
                                <!--begin::Form-->
                                <form id="preview" action="{{ route('barcode.preview') }}" target="_blank" method="get">
                                    @csrf
                                    <div class="card-body">
                                        <input type="hidden" id="business_name"
                                            value="{{ json_decode($generalSettings->business, true)['shop_name'] }}">
                                        <div class="form-group row">
                                            <div class="col-md-8 offset-2">
                                                <div class="input-group ">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                    </div>
                                                    <input type="text" name="search_product" class="form-control "
                                                        autocomplete="off" id="search_product"
                                                        placeholder="@lang('Search Product by Product name / Product code(SKU)')">
                                                </div>
                                                <div class="select_area">
                                                    <ul class="product_dropdown_list">

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="barcode_product_table_area">
                                            <div class="table_heading">
                                                <p class="p-0 m-0"><strong>@lang('Recent Purchased Product List')</strong></p>
                                            </div>
                                            <div class="table_area">
                                                <div class="data_preloader d-none">
                                                    <h6><i class="fas fa-spinner"></i> @lang('Processing')...</h6>
                                                </div>
                                                <table class="table modal-table table-sm">
                                                    <thead>
                                                        <tr class="bg-primary text-white text-start">
                                                            <th class="text-start">@lang('Product')</th>
                                                            <th class="text-start">@lang('Supplier')</th>
                                                            <th class="text-start">@lang('Quantity')</th>
                                                            <th class="text-start">@lang('Packing Date')</th>
                                                            <th class="text-start">@lang('Action')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="barcode_product_list">

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="5" class="text-start"><a href="" class="btn btn-sm btn-success multiple_completed"> @lang('Generate Completed All')</a></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="extra_label">
                                            <div class="form-group">
                                                <div class="row">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <p><input checked type="checkbox" name="is_price" class="checkbox" id="is_price"> &nbsp; Price Price. &nbsp;</p>
                                                        </li>

                                                        <li>
                                                            <p><input checked type="checkbox" name="is_product_name" class="checkbox" id="is_product_name"> &nbsp; Product Name &nbsp; </p>
                                                        </li>

                                                        <li>
                                                            <p class="checkbox_input_wrap"><input checked type="checkbox" name="is_product_variant" class="checkbox" id="is_product_variant"> &nbsp; Product Variant &nbsp; </p>
                                                        </li>

                                                        <li>
                                                            <p class="checkbox_input_wrap"><input checked type="checkbox" name="is_tax" class="checkbox" id="is_tax"> &nbsp; Product Tax &nbsp; </p>
                                                        </li>

                                                        <li>
                                                            <p><input checked type="checkbox" name="is_business_name" class="checkbox" id="is_business_name"> &nbsp; Business Name &nbsp; </p>
                                                        </li>

                                                        <li>
                                                            <p><input checked type="checkbox" name="is_supplier_prefix" class="checkbox" id="is_supplier_prefix"> &nbsp; Supplier Prefix &nbsp; </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <label><b>@lang('Barcode Setting') :</b></label>
                                                <select name="br_setting_id" class="form-control">
                                                    @foreach ($bc_settings as $bc_setting)
                                                        <option {{ $bc_setting->is_default == 1 ? 'SELECTED' : '' }} value="{{ $bc_setting->id }}">
                                                            {{ $bc_setting->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-sm btn-primary float-end">@lang('Preview')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!--end::Form-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $('.multiple_completed').hide();
    // Get all supplier products
    function getPurchaseProducts(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{route('barcode.get.purchase.products', $purchaseId)}}",
            async:true,
            type:'get',
            dataType: 'json',
            success:function(purchaseProducts){
                $.each(purchaseProducts, function (key, p_product) {
                    var tax = p_product.product.tax != null ? p_product.product.tax.tax_percent : 0.00;
                    var tr = '';
                    tr += '<tr>';
                    tr += '<td class="text-start">';
                    tr += '<span class="span_product_name">'+p_product.product.name+'</span>';
                    if (p_product.product_variant_id != null) {
                        tr += '<span class="span_variant_name">'+' - '+p_product.variant.variant_name+'</span>';
                    }else{
                        tr += '<span class="span_product_code"></span>';
                    }

                    if (p_product.product_variant_id != null) {
                        tr += '<span class="span_product_code">'+' ('+p_product.variant.variant_code+')'+'</span>';
                    }else{
                        tr += '<span class="span_product_code">'+' ('+p_product.product.product_code+')'+'</span>';
                    }

                    var variant_id = p_product.product_variant_id != null ? p_product.product_variant_id : null;
                    tr += '<input type="hidden" name="product_ids[]" class="productPrefix-'+p_product.product.id+p_product.purchase.supplier_id+variant_id+'" id="product_id" value="'+p_product.product.id+'">';
                    tr += '<input type="hidden" name="product_name[]" id="product_name" value="'+p_product.product.name+'">';

                    if (p_product.product_variant_id != null) {
                        tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="'+p_product.product_variant_id+'">';
                        tr += '<input type="hidden" name="product_variant[]" value="'+p_product.variant.variant_name+'">';
                        tr += '<input type="hidden" class="productCode-'+p_product.variant.variant_code+'" name="product_code[]" value="'+p_product.variant.variant_code+'">';
                        var priceIncTax = parseFloat(p_product.variant.variant_price) /100 * parseFloat(tax) + parseFloat(p_product.variant.variant_price);
                        if (p_product.product.tax_type == 2) {
                            var inclusiveTax = 100 + parseFloat(tax)
                            var calcAmount = parseFloat(p_product.variant.variant_price) / parseFloat(inclusiveTax) * 100;
                            tax_amount = parseFloat(p_product.variant.variant_price) - parseFloat(calcAmount);
                            priceIncTax = parseFloat(p_product.variant.variant_price) + parseFloat(tax_amount);
                        }
                        tr += '<input type="hidden" name="product_price[]" value="'+parseFloat(priceIncTax).toFixed(2) +'">';
                    } else {
                        tr += '<input type="hidden" name="product_variant_ids[]" class="variantId-" value="noid">';
                        tr += '<input type="hidden" name="product_variant[]" value="">';
                        tr += '<input type="hidden" class="productCode-'+p_product.product.product_code+'" name="product_code[]" value="'+p_product.product.product_code+'">';
                        var priceIncTax = parseFloat(p_product.product.product_price) /100 * parseFloat(tax) + parseFloat(p_product.product.product_price);
                        if (p_product.product.tax_type == 2) {
                            var inclusiveTax = 100 + parseFloat(tax)
                            var calcAmount = parseFloat(p_product.product.product_price) / parseFloat(inclusiveTax) * 100;
                            tax_amount = parseFloat(p_product.product.product_price) - parseFloat(calcAmount);
                            priceIncTax = parseFloat(p_product.product.product_price) + parseFloat(tax_amount);
                        }
                        tr += '<input type="hidden" name="product_price[]" value="'+parseFloat(priceIncTax).toFixed(2)+'">';
                    }

                    tr += '<input type="hidden" name="product_tax[]" value="'+p_product.unit_tax_percent+'">';
                    tr += '<input type="hidden" name="tax_type[]" value="'+p_product.product.tax_type+'">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<span class="span_supplier_name">'+ p_product.purchase.supplier.name +'</span>';
                    tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="'+p_product.purchase.supplier.id+'">';
                    tr += '<input type="hidden" name="supplier_prefix[]" value="'+p_product.purchase.supplier.prefix+'">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<input type="number" name="left_qty[]" id="left_qty" class="form-control" value="'+p_product.quantity+'">';
                    tr += '<input type="hidden" name="barcode_type[]" value="'+p_product.product.barcode_type+'">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<input type="date" name="packing_date[]" class="form-control">';
                    tr += '</td>';

                    tr += '<td class="text-start">';
                    tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ml-1">X</a>';
                    tr += '</td>';
                    tr += '</tr>';
                    $('#barcode_product_list').append(tr);
                });
                $('.data_preloader').hide();
            }
        });
    }
    getPurchaseProducts();

    // Generate confirm request send by ajax
    $(document).on('click', '.completed_btn',function (e) {
        e.preventDefault();
        var tr = $(this).closest('tr');
        var product_id = tr.find('#product_id').val();
        var variant_id = tr.find('#product_variant_id').val();
        var supplier_id = tr.find('#supplier_id').val();
        var label_qty = tr.find('#left_qty').val();
        $.ajax({
            url:"{{route('barcode.genereate.completed')}}",
            type:'get',
            data: {
                product_id,variant_id,supplier_id,label_qty
            },
            success:function(data){
                if (!$.isEmptyObject(data.successMsg)) {
                    toastr.success(data.successMsg);
                    tr.remove();
                }
            }
        });
    });

    // Searcha product
    $('#search_product').on('input', function () {
       var searchKeyWord = $(this).val();
       $('.product_dropdown_list').empty();
       $('.select_area').hide();
       $.ajax({
            url:"{{url('product/barcode/search/product')}}"+"/"+searchKeyWord,
            type:'get',
            success:function(products){
                if (products.length > 0) {
                    $.each(products, function(key, product){
                        li = '';
                        li += '<li>';
                        li += '<a class="select_product" data-p_id="'+product.id+'" href="#">'+product.name+' - '+product.product_code+'</a>';
                        li +='</li>';
                        if (product.product_purchased_variants.length > 0) {
                            $.each(product.product_purchased_variants, function (key, product_variant) {
                                li += '<li>';
                                li += '<a class="select_variant_product" data-p_id="'+product_variant.product_id+'" data-v_id="'+product_variant.id+'" href="#">'+product.name+' - '+product_variant.variant_name+' - '+'('+product_variant.variant_code+')'+'</a>';
                                li +='</li>';
                            });
                        }
                    });
                    $('.product_dropdown_list').append(li);
                    $('.select_area').show();
                }else{
                    $('.select_area').hide();
                }
            }
        });
    });

    //Get Seleled product requested by ajax
    $(document).on('click', '.select_product', function(e) {
        e.preventDefault();
        var product_id = $(this).data('p_id');
        $('.select_area').hide();
        $('#search_product').val('');
        $.ajax({
            url:"{{url('product/barcode/get/selected/product/')}}"+"/"+product_id,
            type:'get',
            success:function(supplierProducts){
                var productIds = document.querySelectorAll('#product_id');
                var rows = [
                    {
                        productPrefix : 78555858,
                    },
                ];

                productIds.forEach(function (product_id) {
                    var productId = product_id.value;
                    var className = product_id.getAttribute('class');
                    var tr = $('.'+className).closest('tr');
                    var supplier_id = tr.find('#supplier_id').val();
                    var variant_id = tr.find('#product_variant_id').val() != 'noid' ? tr.find('#product_variant_id').val() : null;
                    rows.push({
                        productPrefix : supplier_id+productId+variant_id,
                    });
                });

                $.each(supplierProducts, function (key, sProduct) {
                    var createPrefix = sProduct.supplier_id+''+sProduct.product_id+''+sProduct.product_variant_id;
                    var tax = sProduct.product.tax != null ? sProduct.product.tax.tax_percent : 0.00 ;
                    var sameProduct = rows.filter(function (row) {
                        return row.productPrefix == createPrefix;
                    });

                    if (sameProduct.length > 0) {
                       toastr.error('This product has already been added in barcode table.');
                       return;
                    }

                    if (sameProduct.length == 0) {
                        var tr = '';
                        tr += '<tr>';
                        tr += '<td class="text-start">';
                        tr += '<span class="span_product_name">'+sProduct.product.name+'</span>';

                        if (sProduct.product_variant_id != null) {
                            tr += '<span class="span_variant_name">'+' - '+sProduct.variant.variant_name+'</span>';
                        }else{
                            tr += '<span class="span_product_code"></span>';
                        }

                        if (sProduct.product_variant_id != null) {
                            tr += '<span class="span_product_code">'+' ('+sProduct.variant.variant_code+')'+'</span>';
                        }else{
                            tr += '<span class="span_product_code">'+' ('+sProduct.product.product_code+')'+'</span>';
                        }

                        var variant_id = sProduct.product_variant_id != null ? sProduct.product_variant_id : null;
                        tr += '<input type="hidden" name="product_ids[]" class="productPrefix-'+ sProduct.product.id+sProduct.supplier_id+variant_id +'" id="product_id" value="'+ sProduct.product.id +'">';
                        tr += '<input type="hidden" name="product_name[]" value="'+ sProduct.product.name+'">';

                        tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="noid">';
                        tr += '<input type="hidden" name="product_variant[]" value="">';
                        tr += '<input type="hidden" class="productCode-'+ sProduct.product.product_code +'" name="product_code[]" value="'+ sProduct.product.product_code +'">';

                        var priceIncTax = parseFloat(sProduct.product.product_price) /100 * parseFloat(tax) + parseFloat(sProduct.product.product_price);
                        if (sProduct.product.tax_type == 2) {
                            var inclusiveTax = 100 + parseFloat(tax)
                            var calcAmount = parseFloat(sProduct.product.product_price) / parseFloat(inclusiveTax) * 100;
                            tax_amount = parseFloat(sProduct.product.product_price) - parseFloat(calcAmount);
                            priceIncTax = parseFloat(sProduct.product.product_price) + parseFloat(tax_amount);
                        }

                        tr += '<input type="hidden" name="product_price[]" value="'+ parseFloat(priceIncTax).toFixed(2)+'">';

                        tr += '<input type="hidden" name="product_tax[]" value="'+ tax +'">';
                        tr += '<input type="hidden" name="tax_type[]" value="'+sProduct.product.tax_type+'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<span class="span_supplier_name">'+ sProduct.supplier.name +'</span>';
                        tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="'+ sProduct.supplier_id +'">';
                        tr += '<input type="hidden" name="supplier_prefix[]" value="'+ sProduct.supplier.prefix +'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="number" name="left_qty[]" id="left_qty" class="form-control" value="'+1+'">';
                        tr += '<input type="hidden" name="barcode_type[]" value="'+ sProduct.product.barcode_type +'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="date" name="packing_date[]" class="form-control">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<a href="#" class="btn btn-sm btn-danger remove_btn float-right ml-1">X</a>';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#barcode_product_list').append(tr);
                    }
                });
            }
        });
    });

    //Get Seleled product requested by ajax
    $(document).on('click', '.select_variant_product', function(e) {
        e.preventDefault();
        $('.select_area').hide();
        $('#search_product').val('');
        var product_id = $(this).data('p_id');
        var variant_id = $(this).data('v_id');
        $.ajax({
            url:"{{url('product/barcode/get/selected/product/variant')}}"+"/"+product_id+"/"+variant_id,
            type:'get',
            success:function(supplierProducts){
                var productIds = document.querySelectorAll('#product_id');
                var rows = [
                    {
                        productPrefix : 78555858,
                    },
                ];
                productIds.forEach(function (product_id) {
                    var productId = product_id.value;
                    var className = product_id.getAttribute('class');
                    var tr = $('.'+className).closest('tr');
                    var supplier_id = tr.find('#supplier_id').val();
                    var variant_id = tr.find('#product_variant_id').val() != 'noid' ? tr.find('#product_variant_id').val() : null;
                    rows.push({
                        productPrefix : supplier_id + productId + variant_id,
                    });
                });

                $.each(supplierProducts, function (key, sProduct) {
                    var tax = sProduct.product.tax != null ? sProduct.product.tax.tax_percent : 0.00 ;
                    var createPrefix = sProduct.supplier_id+''+sProduct.product_id+''+sProduct.product_variant_id;
                    var sameProduct = rows.filter(function (row) {
                       return row.productPrefix == createPrefix;
                    });

                    if (sameProduct.length > 0) {
                        toastr.error('This variant has already been added in barcode table.');
                       return;
                    }

                    if (sameProduct.length == 0) {
                        var tr = '';
                        tr += '<tr>';
                        tr += '<td class="text-start">';
                        tr += '<span class="span_product_name">'+sProduct.product.name+'</span>';
                        if (sProduct.product_variant_id != null) {
                            tr += '<span class="span_variant_name">'+' - '+sProduct.variant.variant_name+'</span>';
                        }else{
                            tr += '<span class="span_product_code"></span>';
                        }

                        if (sProduct.product_variant_id != null) {
                            tr += '<span class="span_product_code">'+' ('+sProduct.variant.variant_code+')'+'</span>';
                        }else{
                            tr += '<span class="span_product_code">'+' ('+sProduct.product.product_code+')'+'</span>';
                        }

                        var variant_id = sProduct.product_variant_id != null ? sProduct.product_variant_id : null;
                        tr += '<input type="hidden" name="product_ids[]" class="productPrefix-'+sProduct.product.id+sProduct.supplier_id+variant_id+'" id="product_id" value="'+sProduct.product.id+'">';
                        tr += '<input type="hidden" name="product_name[]" value="'+sProduct.product.name+'">';
                        tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="'+sProduct.variant.id+'">';
                        tr += '<input type="hidden" name="product_variant[]" value="'+sProduct.variant.variant_name+'">';
                        tr += '<input type="hidden" class="productCode-'+sProduct.variant.variant_code+'" name="product_code[]" value="'+sProduct.variant.variant_code+'">';

                        var priceIncTax = parseFloat(sProduct.variant.variant_price) / 100 * parseFloat(tax) + parseFloat(sProduct.variant.variant_price);
                        if (sProduct.product.tax_type == 2) {
                            var inclusiveTax = 100 + parseFloat(tax)
                            var calcAmount = parseFloat(sProduct.variant.variant_price) / parseFloat(inclusiveTax) * 100;
                            tax_amount = parseFloat(sProduct.variant.variant_price) - parseFloat(calcAmount);
                            priceIncTax = parseFloat(sProduct.variant.variant_price) + parseFloat(tax_amount);
                        }

                        tr += '<input type="hidden" name="product_price[]" value="'+parseFloat(priceIncTax).toFixed(2)+'">';

                        tr += '<input type="hidden" name="product_tax[]" value="'+tax+'">';
                        tr += '<input type="hidden" name="tax_type[]" value="'+sProduct.product.tax_type+'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<span class="span_supplier_name">'+ sProduct.supplier.name +'</span>';
                        tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="'+sProduct.supplier_id+'">';
                        tr += '<input type="hidden" name="supplier_prefix[]" value="'+sProduct.supplier.prefix+'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="number" name="left_qty[]" id="left_qty" class="form-control" value="'+1+'">';
                        tr += '<input type="hidden" name="barcode_type[]" value="'+sProduct.product.barcode_type+'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="date" name="packing_date[]" class="form-control">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ml-1">X</a>';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#barcode_product_list').append(tr);
                    }
                });
            }
        });
    });

    // Dispose Select area
    $(document).on('click', '.remove_select_area_btn', function(e){
        e.preventDefault();
        $('.select_area').hide();
    });

    // Generate confirm request send by ajax
    $(document).on('click', '.remove_btn',function (e) {
        e.preventDefault();
        var tr = $(this).closest('tr').remove();
    })

    $(document).on('click', '.multiple_completed',function(e){
        e.preventDefault();
        $('#action').val('multipla_deactive');
        $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    //Multiple generate completed requested by ajax
    $(document).on('submit', '#multiple_completed_form',function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data:request,
            success:function(data){
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg, 'Attention');
                }else{
                    toastr.success(data);
                    $('.multiple_completed').remove();
                    var allRecents = document.querySelectorAll('#recents');
                    allRecents.forEach(function (recent) {
                        var className = recent.getAttribute('class');
                        $('.'+className).closest('tr').remove();
                    });
                }
            }
        });
    });

    $(document).on('submit', '#preview', function (e) {
        var quantities = document.querySelectorAll('#left_qty');
        // Update Total Item
        var total_qty = 0;
        quantities.forEach(function(qty){
            total_qty += parseFloat(qty.value)
        });
        console.log(total_qty);
        if (parseFloat(total_qty) == 0) {
            toastr.error('Total barcode label Quantity is 0');
            return false;
        }
    });
</script>
@endpush
