@extends('layout.master')
@push('stylesheets')
<style>
    .select_area {background: #ffffff;box-sizing: border-box;position: absolute;width: 64.2%;z-index: 9999999;padding: 0;left: 17.9%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
    .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
    .select_area ul li a {color: #000000;text-decoration: none;font-size: 13px;padding: 4px 3px;display: block;border: 1px solid lightgray; margin-bottom: 3px}
    .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
    .table_product_list {max-height: 70vh;overflow-x: scroll;}
</style>
@endpush
{{-- @section('title', 'Generate Barcode - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h5>@lang('Generate Barcode')</h5>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-7">
                            <div class="card ">
                                <form id="multiple_completed_form" class="d-none"
                                    action="{{ route('barcode.multiple.generate.completed') }}" method="post">
                                    @csrf
                                    <table>
                                        <tbody id="deleteable_supplier_products"></tbody>
                                    </table>
                                </form>

                                <form action="{{ route('barcode.preview') }}" method="POST" target="_blank">
                                    @csrf
                                    <div class="card-body">
                                        <input type="hidden" id="business_name"
                                            value="{{ json_decode($generalSettings->business, true)['shop_name'] }}">
                                        <div class="form-group row">
                                            <div class="col-md-8">
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

                                        <div class="extra_label mt-1">
                                            <div class="form-group">
                                                <div class="row">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <p><input checked type="checkbox" name="is_price" class="checkbox" id="is_price"> &nbsp; @lang('Supplier Prefix') &nbsp;</p>
                                                        </li>

                                                        <li>
                                                            <p><input checked type="checkbox" name="is_product_name" class="checkbox" id="is_product_name"> &nbsp; @lang('Product Name') &nbsp; </p>
                                                        </li>

                                                        <li>
                                                            <p class="checkbox_input_wrap"><input checked type="checkbox" name="is_product_variant" class="checkbox" id="is_product_variant"> &nbsp; @lang('Variant Name') &nbsp; </p>
                                                        </li>

                                                        <li>
                                                            <p class="checkbox_input_wrap"><input checked type="checkbox" name="is_tax" class="checkbox" id="is_tax"> &nbsp; @lang('Tax') &nbsp; </p>
                                                        </li>

                                                        <li>
                                                            <p><input checked type="checkbox" name="is_business_name" class="checkbox" id="is_business_name"> &nbsp; @lang('Business Name') &nbsp; </p>
                                                        </li>

                                                        <li>
                                                            <p><input checked type="checkbox" name="is_supplier_prefix" class="checkbox" id="is_supplier_prefix"> &nbsp; @lang('Supplier Prefix') &nbsp; </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mt-3 row">
                                            <div class="col-md-12">
                                                <div class="input-group ">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                    </div>
                                                    <input type="text" name="search_product" class="form-control "
                                                        autocomplete="off" id="search_product"
                                                        placeholder="@lang('Search Product by Product name / Product code(SKU)')">
                                                </div>
                                                <div class="select_area">
                                                    <ul class="product_dropdown_list"></ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="barcode_product_table_area mt-2">
                                                    <div class="table_heading">
                                                        <p class="p-0 m-0"><strong>@lang('Product List')</strong></p>
                                                    </div>
                                                    <div class="table_area">
                                                        <div class="data_preloader d-none">
                                                            <h6><i class="fas fa-spinner"></i> @lang('Processing')...</h6>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table modal-table table-sm" style="margin: 50px auto;">
                                                                <thead>
                                                                    <tr class="bg-primary text-white text-start">
                                                                        <th class="text-start">@lang('Product')</th>
                                                                        <th class="text-start">@lang('Supplier')</th>
                                                                        <th class="text-start">@lang('Quantity')</th>
                                                                        <th class="text-start">@lang('Action')</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="barcode_product_list"></tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="2" class="text-end">@lang('Total Prepired Qty')</th>
                                                                        <th class="text-start">(<span id="prepired_qty">0</span>)</th>
                                                                        <th ></th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6 multiple_cmp_btn_area">
                                                <a href="" class="btn btn-sm btn-danger multiple_completed" style=""> @lang('DELETE SELECTED ALL') </a> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Note : Delete all items from puchased products which is selected for generation the barcodes" class="fas fa-info-circle tp"></i>
                                            </div>
                                            <div class="col-md-6 multiple_cmp_btn_area">
                                                <button type="submit" class="btn btn-sm btn-primary float-end">@lang('Preview & Print')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="table_product_list">
                                <div class="card p-1">
                                    <div class="heading">
                                        <p><strong>@lang('Purchased Product List')</strong></p>
                                    </div>
                                    <table class="display data_tbl data__table table-hover" id="data">
                                        <thead>
                                            <tr>
                                                <th class="text-start"><input type="checkbox" id="chack_all">@lang('All')</th>
                                                <th class="text-start">@lang('Product')</th>
                                                <th class="text-start">@lang('Supplier')</th>
                                                <th class="text-start">@lang('Quantity')</th>
                                            </tr>
                                        </thead>
                                        <tbody id="purchased_product_list"></tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">@lang('Total Pending Qty') :</th>
                                                <th colspan="3" class="text-end">0</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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
    // $('.multiple_cmp_btn_area').hide();
    // Get all supplier products
    function getSupplierProducts(){
        $.ajax({
            url:"{{route('barcode.supplier.get.products')}}",
            type:'get',
            success:function(data){
                $('#data').html(data);
            }
        });
    }
    getSupplierProducts();

    // Searcha product
    $('#search_product').on('input', function () {
       var searchKeyWord = $(this).val();
       $('.product_dropdown_list').empty();
       $('.select_area').hide();
       $.ajax({
            url:"{{url('product/barcode/search/product')}}"+"/"+searchKeyWord,
            type:'get',
            success:function(products){
                $('.product_dropdown_list').empty();
                if (products.length > 0) {
                    li = '';
                    $.each(products, function(key, product){
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
                    var tax = sProduct.product.tax != null ? sProduct.product.tax.tax_percent : 0.00 ;
                    var createPrefix = sProduct.supplier_id+''+sProduct.product_id+''+sProduct.product_variant_id;
                    var sameProduct = rows.filter(function (row) {
                        return row.productPrefix == createPrefix;
                    });

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
                        }else {
                            tr += '<span class="span_product_code">'+' ('+sProduct.product.product_code+')'+'</span>';
                        }

                        var variant_id = sProduct.product_variant_id != null ? sProduct.product_variant_id : null;
                        tr += '<input type="hidden" name="product_ids[]" class="productPrefix-'+ sProduct.product.id+sProduct.supplier_id+variant_id +'" id="product_id" value="'+ sProduct.product.id +'">';
                        tr += '<input type="hidden" name="product_name[]" value="'+ sProduct.product.name+'">';

                        var priceIncTax = parseFloat(sProduct.product.product_price) /100 * parseFloat(tax) + parseFloat(sProduct.product.product_price);
                        if (sProduct.product.tax_type == 2) {
                            var inclusiveTax = 100 + parseFloat(tax)
                            var calcAmount = parseFloat(sProduct.product.product_price) / parseFloat(inclusiveTax) * 100;
                            tax_amount = parseFloat(sProduct.product.product_price) - parseFloat(calcAmount);
                            priceIncTax = parseFloat(sProduct.product.product_price) + parseFloat(tax_amount);
                        }

                        tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="noid">';
                        tr += '<input type="hidden" name="product_variant[]" value="">';
                        tr += '<input type="hidden" class="productCode-'+ sProduct.product.product_code+'" name="product_code[]" value="'+ sProduct.product.product_code +'">';
                        tr += '<input type="hidden" name="product_price[]" id="product_price" value="'+ parseFloat(priceIncTax).toFixed(2) +'">';

                        tr += '<input type="hidden" name="product_tax[]" value="'+ tax +'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<span class="span_supplier_name">'+ sProduct.supplier.name +'</span>';
                        tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="'+ sProduct.supplier_id +'">';
                        tr += '<input type="hidden" name="supplier_prefix[]" id="supplier_prefix" value="'+ sProduct.supplier.prefix+'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="number" name="left_qty[]" class="form-control " id="left_qty" value="'+1+'">';
                        tr += '<input type="hidden" name="barcode_type[]" id="barcode_type" value="'+ sProduct.product.barcode_type +'">';
                        tr += '</td>';
                        tr += '<td class="text-start">';
                        tr += '<a href="#" class="btn btn-sm btn-danger remove_btn float-right ms-1">X</a>';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#barcode_product_list').prepend(tr);
                        calculateQty();
                    }
                });
            }
        });
    });

    //Get Seleled product requested by ajax
    $(document).on('click', '.select_variant_product', function(e) {
        e.preventDefault();
        var product_id = $(this).data('p_id');
        var variant_id = $(this).data('v_id');
        $('.select_area').hide();
        $('#search_product').val('');
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
                    console.log('Class_name-'+className);
                    var tr = $('.'+className).closest('tr');
                    var supplier_id = tr.find('#supplier_id').val();
                    var variant_id = tr.find('#product_variant_id').val() != 'noid' ? tr.find('#product_variant_id').val() : null;
                    rows.push({
                        productPrefix : supplier_id + productId + variant_id,
                    });
                });

                $.each(supplierProducts, function (key, sProduct) {
                    var tax = sProduct.product.tax != null ? sProduct.product.tax.tax_percent : 0.00;
                    var createPrefix = sProduct.supplier_id+''+sProduct.product_id+''+sProduct.product_variant_id;
                    var sameProduct = rows.filter(function (row) {
                       return row.productPrefix == createPrefix;
                    });

                    if (sameProduct.length > 0) {
                       alert('This variant is exists in barcode table.');
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
                        tr += '<input type="hidden" name="product_name[]" id="product_name" value="'+sProduct.product.name+'">';

                        tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="'+sProduct.variant.id+'">';
                        tr += '<input type="hidden" name="product_variant[]" id="product_variant" value="'+sProduct.variant.variant_name+'">';
                        tr += '<input type="hidden" class="productCode-'+sProduct.variant.variant_code+'" name="product_code[]" value="'+sProduct.variant.variant_code+'">';

                        var priceIncTax = parseFloat(sProduct.variant.variant_price) / 100 * parseFloat(tax) + parseFloat(sProduct.variant.variant_price);
                        if (sProduct.product.tax_type == 2) {
                            var inclusiveTax = 100 + parseFloat(tax)
                            var calcAmount = parseFloat(sProduct.variant.variant_price) / parseFloat(inclusiveTax) * 100;
                            tax_amount = parseFloat(sProduct.variant.variant_price) - parseFloat(calcAmount);
                            priceIncTax = parseFloat(sProduct.variant.variant_price) + parseFloat(tax_amount);
                        }

                        tr += '<input type="hidden" name="product_price[]" id="product_price" value="'+parseFloat(priceIncTax).toFixed(2)+'">';

                        tr += '<input type="hidden" name="product_tax[]" id="product_tax" value="'+tax+'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<span class="span_supplier_name">'+ sProduct.supplier.name +'</span>';
                        tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="'+sProduct.supplier_id+'">';
                        tr += '<input type="hidden" name="supplier_prefix[]" id="supplier_name" value="'+sProduct.supplier.prefix+'">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="number" name="left_qty[]" class="form-control" id="left_qty" value="'+1+'">';
                        tr += '<input type="hidden" name="barcode_type[]" id="barcode_type" value="'+sProduct.product.barcode_type+'">';
                        tr += '</td>';
                        tr += '<td class="text-start">';
                        tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ms-1">X</a>';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#barcode_product_list').prepend(tr);
                        calculateQty();
                    }
                });
            }
        });
    });

    // Generate confirm request send by ajax
    $(document).on('click', '.remove_btn',function (e) {
        e.preventDefault();
        var tr = $(this).closest('tr').remove();
        calculateQty();
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
                    getSupplierProducts();
                    $('#barcode_product_list').empty();
                    $('#deleteable_supplier_products').empty();
                    $('.check').prop('checked', false);
                }
            }
        });
    });

    $(document).on('change','#chack_all',function() {
        if($(this).is(':CHECKED', true)){
            $('.check').click();
        }else{
            $('.check').click();
        }
    });

    $(document).on('click', '.check', function () {
        var tr = $(this).closest('tr');
        var product_id = tr.data('p_id');
        var product_code = tr.data('p_code');
        var product_name = tr.data('p_name');
        var variant_id = tr.data('v_id');
        var variant_code = tr.data('v_code');
        var variant_name = tr.data('v_name');
        var tax = tr.data('tax');
        var price = tr.data('price');
        var supplier_id = tr.data('supplier_id');
        var supplier_name = tr.data('supplier_name');
        var supplier_prefix = tr.data('supplier_prefix');
        var label_qty = tr.data('label_qty');
        var barcode_type = tr.data('barcode_type');

        if ($(this).is(':CHECKED', true)) {
            var tr = '';
            tr += '<tr class="'+supplier_prefix+product_id+(variant_id  ? variant_id : null)+'">';
            tr += '<td class="text-start">';
            tr += '<span class="span_product_name">'+product_name+'</span>';

            if (variant_id) {
                tr += '<span class="span_variant_name">'+' - '+variant_name+'</span>';
            }

            if (variant_code) {
                tr += '<span class="span_product_code">'+' ('+variant_code+')'+'</span>';
            }else {
                tr += '<span class="span_product_code">'+' ('+product_code+')'+'</span>';
            }

            var variant_id = variant_id  ? variant_id : null;
            tr += '<input type="hidden" name="product_ids[]" class="productPrefix-'+ product_id+supplier_id+variant_id +'" id="product_id" value="'+ product_id +'">';
            tr += '<input type="hidden" name="product_name[]" value="'+ product_name+'">';

            tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-'+variant_id+'" value="'+variant_id+'">';
            tr += '<input type="hidden" name="product_variant[]" value="'+variant_name+'">';
            tr += '<input type="hidden" class="productCode-'+ (variant_code ? variant_code : product_code)+'" name="product_code[]" value="'+ (variant_code ? variant_code : product_code) +'">';
            tr += '<input type="hidden" name="product_price[]" id="product_price" value="'+ parseFloat(price).toFixed(2) +'">';

            tr += '<input type="hidden" name="product_tax[]" value="'+ tax +'">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<span class="span_supplier_name">'+ supplier_name +'</span>';
            tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="'+ supplier_id +'">';
            tr += '<input type="hidden" name="supplier_prefix[]" id="supplier_prefix" value="'+supplier_prefix+'">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<input type="number" name="left_qty[]" class="form-control " id="left_qty" value="'+label_qty+'">';
            tr += '<input type="hidden" name="barcode_type[]" id="barcode_type" value="'+ barcode_type +'">';
            tr += '</td>';
            tr += '</tr>';
            $('#barcode_product_list').prepend(tr);
            tr2 = '';
            tr2 += '<tr class="'+supplier_prefix+product_id+variant_id+'">';
            tr2 += '<td>';
            tr2 += '<input type="hidden" name="supplier_ids[]" value="'+ supplier_id +'">';
            tr2 += '<input type="hidden" name="product_ids[]" value="'+ product_id +'">';
            tr2 += '<input type="hidden" name="product_variant_ids[]" class="variantId-'+variant_id+'" value="'+variant_id+'">';
            tr2 += '</td>';
            tr2 += '</tr>';
            $('#deleteable_supplier_products').prepend(tr2);
            calculateQty();
        }else{
            $('.'+supplier_prefix+product_id+(variant_id  ? variant_id : null)).remove();
            calculateQty();
        }
    });

    function calculateQty() {
        var left_quantities = document.querySelectorAll('#left_qty');
        var total_qty = 0;
        left_quantities.forEach(function (left_qty) {
            total_qty += parseFloat(left_qty.value);
        });
        $('#prepired_qty').html(total_qty);

        if (parseFloat(total_qty) > 0) {
            $('.multiple_cmp_btn_area').show();
        }else{
            $('.multiple_cmp_btn_area').hide();
        }
    }

    $(document).on('input', '#left_qty', function () {
        calculateQty();
    });
</script>
@endpush
