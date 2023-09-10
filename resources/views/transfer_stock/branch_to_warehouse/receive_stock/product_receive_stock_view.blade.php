@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {
            font-size: 12px !important;
        }
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="receive_stock_form" action="{{ route('transfer.stocks.to.warehouse.receive.stock.process.save', $sendStockId) }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>@lang('Process To Receive Stock')</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <p class="m-0"><strong>@lang('Transfer Stock Details') </strong></p>
                                    <hr class="m-1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="m-0"><strong>@lang('Reference ID'): </strong> <span class="transfer_invoice_id"></span> </p>
                                            <p class="m-0"><strong>@lang('Date'): </strong> <span class="transfer_date"></span></p>
                                         </div>
                                         <div class="col-md-6">
                                             <p class="m-0"><strong>@lang('Warehouse (From)') : </strong> <span class="warehouse"></span> </p>
                                             <p class="m-0"><strong>@lang('Business Location') : </strong> <span class="branch"></span></p>
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
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>@lang('Product')</th>
                                                                    <th class="text-center">@lang('Send Quantity')</th>
                                                                    <th class="text-center">@lang('Unit')</th>
                                                                    <th class="text-center">@lang('Pending Qty')</th>
                                                                    <th class="text-center">@lang('Receive Quantity')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="send_stock_list">

                                                            </tbody>
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
                        <div class="form_element">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <label class="col-4">@lang('Receiver Note'):</label>
                                            <div class="col-8">
                                                <input type="text" name="receiver_note" id="receiver_note" class="form-control" placeholder="@lang('Receiver note')">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <label class=" col-4">@lang('Net Total') :</label>
                                            <div class="col-8">
                                                <input readonly type="number" step="any" name="total_received_quantity" id="total_received_quantity" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area py-2">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button class="btn btn-sm btn-primary float-end">@lang('Save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    var headBranch = "{{json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}";
    // Get editable data by ajax
    function getReceiveableStock(){
        $.ajax({
            url:"{{route('transfer.stocks.to.warehouse.receive.stock.get.receivable.stock', $sendStockId)}}",
            async:true,
            type:'get',
            dataType: 'json',
            success:function(sendStock){
                $('.transfer_invoice_id').html(sendStock.invoice_id);
                $('.warehouse').html(sendStock.warehouse.warehouse_name+'/'+sendStock.warehouse.warehouse_code);
                $('.branch').html(sendStock.branch ? sendStock.branch.name+'/'+sendStock.branch.branch_code : headBranch);
                $('.transfer_date').html(sendStock.date);
                $('#receiver_note').val(sendStock.receiver_note);
                $.each(sendStock.transfer_products, function (key, sendProduct) {
                    var tr = '';
                    tr += '<tr>';
                    tr += '<td class="text">';
                    tr += '<a href="#" id="edit_product">';
                    tr += '<span class="product_name">'+sendProduct.product.name+'</span>';
                    var variant = sendProduct.product_variant_id != null ? ' -'+sendProduct.variant.variant_name+'- ' : '';
                    tr += '<span class="product_variant">'+variant+'</span>';
                    var code = sendProduct.product_variant_id != null ? sendProduct.variant.variant_code : sendProduct.product.product_code;
                    tr += '<span class="product_code">'+'('+code+')'+'</span>';
                    tr += '</a>';
                    tr += '<input value="'+sendProduct.product_id+'" type="hidden" class="productId-'+sendProduct.product_id+'" id="product_id" name="product_ids[]">';

                    if (sendProduct.product_variant_id != null) {
                        tr += '<input value="'+sendProduct.product_variant_id+'" type="hidden" class="variantId-'+sendProduct.product_variant_id+'" id="variant_id" name="variant_ids[]">';
                    }else{
                        tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                    }
                    tr += '<input type="hidden" id="unit" value="'+sendProduct.unit+'">';
                    tr += '<input type="hidden" name="previous_received_quantities[]" id="previous_received_quantity" value="'+sendProduct.received_qty+'">';
                    tr += '<input type="hidden" id="qty_limit" value="'+sendProduct.quantity+'">';
                    tr += '</td>';

                    tr += '<td class="text text-center">';
                    tr += '<span>'+sendProduct.quantity+'</span>';
                    tr += '</td>';

                    tr += '<td class="text text-center">';
                    tr += '<span>'+sendProduct.unit+'</span>';
                    tr += '</td>';

                    var pending_qty = parseFloat(sendProduct.quantity) - parseFloat(sendProduct.received_qty);
                    tr += '<td class="text text-center">';
                    tr += '<b><span class="text-danger" id="pending_qty">'+parseFloat(pending_qty).toFixed(2)+'</span><b>';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input value="'+sendProduct.received_qty+'" required name="receive_quantities[]" type="text" class="form-control text-center form-control-sm" id="receive_quantity">';
                    tr += '</td>';
                    tr += '</tr>';
                    $('#send_stock_list').append(tr);
                });
                calculateTotalAmount();
            }
        });
    }
    getReceiveableStock();

     // Calculate total amount functionalitie
     function calculateTotalAmount(){
        var quantities = document.querySelectorAll('#receive_quantity');
        // Update Total Item
        var total_receive_qty = 0
        quantities.forEach(function(qty){
            total_receive_qty += parseFloat(qty.value ? qty.value : 0);
        });

        $('#total_received_quantity').val(parseFloat(total_receive_qty).toFixed(2));
    }

     // Quantity increase or dicrease and clculate row amount
     $(document).on('input', '#receive_quantity', function(){
        var qty = $(this).val() ? $(this).val() : 0;
        if (parseFloat(qty) >= 0) {
            var tr = $(this).closest('tr');
            var qty_limit = tr.find('#qty_limit').val();
            var unit = tr.find('#unit').val();
            var pending_qty = parseInt(qty_limit) - parseFloat(qty);
            tr.find('#pending_qty').html(parseFloat(pending_qty).toFixed(2));
            if(parseInt(qty) > parseInt(qty_limit)){
                alert('Only - '+qty_limit+' '+unit+' is available.');
                $(this).val(qty_limit);
                tr.find('#pending_qty').html(parseFloat(0).toFixed(2));
                calculateTotalAmount();
                return;
            }
            calculateTotalAmount();
        }
    });

    //Add purchase request by ajax
    $('#receive_stock_form').on('submit', function(e){
        e.preventDefault();
        var totalItem = $('#total_item').val();
        if (parseFloat(totalItem) == 0) {
            toastr.error('Transfer product table is empty.','Some thing went wrong.');
            return;
        }
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.add_input');
            inputs.removeClass('is-invalid');
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
            $('.loading_button').hide();
            toastr.error("@lang('Please check again all form fields.')",
                    "@lang('Something went wrong.')");
            return;
        }

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                console.log(data);
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'ERROR');
                    $('.loading_button').hide();
                }else{
                    $('.loading_button').hide();
                    toastr.success(data.successMsg);
                    window.location = "{{route('transfer.stocks.to.warehouse.receive.stock.index')}}";
                }
            }
        });
    });
</script>
@endpush
