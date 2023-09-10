@extends('layout.master')
@push('stylesheets')
    <style>
        .data_preloader{top:2.3%}
         /* .search_area{position: relative;}  */
        /* .invoice_search_result{position: relative;} */
        /* Search Product area style */
        .selected_invoice {background-color: #645f61;color: #fff !important;}
        .invoice_search_result {position: absolute; width: 100%;border: 1px solid #E4E6EF;background: white;z-index: 1;padding: 3px;margin-top: 1px;}
        .invoice_search_result ul li {width: 100%;border: 1px solid lightgray;margin-top: 2px;}
        .invoice_search_result ul li a {color: #6b6262;font-size: 10px;display: block;padding: 0px 3px;}
        .invoice_search_result ul li a:hover {color: white;background-color: #ada9a9;}

        .selectProduct {background-color: #645f61;color: #fff !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 88.3%;z-index: 9999999;padding: 0;left: 6%;display: none;border: 1px solid #706a6d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 0px 2px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 11px;padding: 2px 2px;display: block;border: 1px solid lightgray; margin: 2px 0px;}
        .select_area ul li a:hover {background-color: #999396;color: #fff;}
        .element-body {overflow: initial!important;}
        /* Search Product area style end */
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_sale_return_form" action="{{ route('sale.return.random.store') }}" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-8">
                                            <h6>@lang('Add Sale Return')</h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('Sale INV'). ID :</b> </label>
                                                <div class="col-8">
                                                    <div style="position: relative;">
                                                        <input type="text" name="sale_invoice_id" id="sale_invoice_id" class="form-control scanable" placeholder="@lang('Sale Invoice ID')" autocomplete="off">
                                                        <input type="hidden" name="sale_id" id="sale_id" class="resetable" value="">

                                                        <div class="invoice_search_result d-none">
                                                            <ul id="invoice_list" class="list-unstyled">

                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class=" col-4"> <b>@lang('B.Location'):</b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}" tabindex="-1">
                                                    <input type="hidden" value="{{ auth()->user()->branch_id ? auth()->user()->branch_id : 'NULL' }}" id="branch_id">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Customer') :</b> </label>
                                                <div class="col-8">
                                                    <select name="customer_id" class="form-control" id="customer_id">
                                                        <option value="">@lang('Walk-In-Customer')</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4">
                                                    <b>@lang('Return A/C') : <span class="text-danger">*</span></b>
                                                </label>

                                                <div class="col-8">
                                                    <select name="sale_return_account_id" class="form-control add_input"
                                                        id="sale_return_account_id" data-name="Sale Return A/C">
                                                        @foreach ($saleReturnAccounts as $saleReturnAccount)
                                                            <option value="{{ $saleReturnAccount->id }}">
                                                                {{ $saleReturnAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_sale_return_account_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4">
                                                    <b>@lang('Return Date') : <span class="text-danger">*</span></b>
                                                </label>

                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control add_input" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" autocomplete="off" id="date">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4">
                                                    <b> @lang('Price Group') : </b>
                                                </label>

                                                <div class="col-8">
                                                    <select name="price_group_id" class="form-control"
                                                        id="price_group_id">
                                                        <option value="">@lang('Default Selling Price')</option>
                                                        @foreach ($price_groups as $pg)
                                                            <option {{ json_decode($generalSettings->sale, true)['default_price_group_id'] == $pg->id ? 'SELECTED' : '' }} value="{{ $pg->id }}">{{ $pg->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class=" col-4"><b>Re. Invoice ID:</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="@lang('Sale Return Invoice ID')" autocomplete="off">
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
                                            <div class="col-md-4">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('Select Item')</label>
                                                    <select disabled class="form-control" id="sale_products">
                                                        <option value="">@lang('Select Item')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('Item Search')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-barcode text-dark input_f"></i>
                                                            </span>
                                                        </div>

                                                        <input type="text" name="search_product" class="form-control scanable" id="search_product" placeholder="@lang('Search Product by product code(SKU) / Scan bar code')" autocomplete="off" autofocus>
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
                                                        <table class="display data__table table sale-product-table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-start">@lang('Product')</th>
                                                                    <th class="text-center">@lang('Unit Price')</th>
                                                                    <th class="text-center">@lang('Unit')</th>
                                                                    <th class="text-center">@lang('Return Quantity')</th>
                                                                    <th class="text-center">@lang('SubTotal')</th>
                                                                    <th><i class="fas fa-minus text-dark"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="return_item_list"></tbody>
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
                        <div class="col-md-6">
                            <div class="form_element">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">

                                                        <label class="col-4"><b>@lang('Total Item') :</b> </label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Total Return Qty') :</b> </label>
                                                        <div class="col-8">
                                                            <input readonly name="total_qty" type="number" step="any" class="form-control" id="total_qty" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Net Total Amount') :</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="net_total_amount" id="net_total_amount" class="form-control" value="0" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('Return Discount') :</b></label>
                                                        <div class="col-8">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <select name="return_discount_type" class="form-control" id="return_discount_type">
                                                                        <option value="1">@lang('Fixed')(0.00)</option>
                                                                        <option value="2">@lang('Percentage')(%)</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <input name="return_discount" type="number" class="form-control" id="return_discount" value="0.00">
                                                                </div>
                                                            </div>
                                                            <input name="return_discount_amount" type="number" step="any" class="d-none" id="return_discount_amount" value="0.00">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Return Tax') :</b>
                                                        </label>

                                                        <div class="col-8">
                                                            <select name="return_tax" class="form-control" id="return_tax">
                                                                <option value="0.00">@lang('NoTax')</option>
                                                                @foreach ($taxes as $tax)
                                                                    <option value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input name="return_tax_amount" type="number" step="any" class="d-none" id="return_tax_amount" value="0.00">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Total Return Amount') :</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="total_return_amount" id="total_return_amount" class="form-control" value="0.00" placeholder="@lang('Total Return Amount')" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group customer_pre_due_field">
                                                        <label class="col-4"><b>@lang('Customer Previous Due') :</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="customer_previous_due" id="customer_previous_due" class="form-control text-danger" value="0" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group invoice_due_field d-none">
                                                        <label class="col-4"><b>@lang('Invoice Due') :</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="invoice_due" id="invoice_due" class="form-control text-danger" value="0" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Total Refundable Amount') :</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="total_refundable_amount" id="total_refundable_amount" class="form-control" value="0" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Refunding Amount') : </b> <strong>>></strong> </label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="paying_amount" id="paying_amount" class="form-control" value="0">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Payment By') : </b> </label>
                                                        <div class="col-8">
                                                            <select name="payment_method_id" class="form-control" id="payment_method_id">
                                                                @foreach ($methods as $method)
                                                                    <option value="{{ $method->id }}">
                                                                        {{ $method->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Credit A/C'): </b> </label>
                                                        <div class="col-8">
                                                            <select name="account_id" class="form-control" id="account_id" data-name="Debit A/C">
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{ $account->id }}">
                                                                        @php
                                                                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                                                            $balance = ' BL : '.$account->balance;
                                                                        @endphp
                                                                        {{ $account->name.$accountType.$balance}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group mt-1">
                                                            <label class="col-4"><b>@lang('Payment Note') :</b></label>
                                                            <div class="col-8">
                                                                <input type="text" name="payment_note" id="payment_note" class="form-control" value="" placeholder="@lang('Payment Note.')">
                                                            </div>
                                                        </div>
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

                <div class="submitBtn">
                    <div class="row justify-content-center">
                        <div class="col-12 text-end">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-danger"></i> </button>
                            <button type="submit" id="save_and_print" class="btn btn-sm btn-success submit_button" value="save_and_print">@lang('Save & Print')</button>
                            <button type="submit" id="save" class="btn btn-sm btn-success submit_button" data-action="save">@lang('Save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit selling product modal-->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="product_info">@lang('Samsung A')30</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="update_return_product" action="">

                        <div class="form-group">
                            <label> <strong>@lang('Quantity')</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" readonly class="form-control edit_input" data-name="Quantity" id="e_return_quantity" placeholder="@lang('Quantity')" tabindex="-1"/>
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>@lang('Unit Price Exc').Tax</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" step="any" class="form-control edit_input" data-name="Unit price" id="e_unit_price_exc_tax" placeholder="@lang('Unit price')" />
                            <span class="error error_e_unit_price"></span>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>@lang('Discount Type')</strong> :</label>
                                <select class="form-control " id="e_unit_discount_type">
                                    <option value="2">@lang('Percentage')</option>
                                    <option value="1">@lang('Fixed')</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('Discount')</strong> :</label>
                                <input type="number" step="any" class="form-control " id="e_unit_discount" value="0.00"/>
                                <input type="hidden" id="e_discount_amount"/>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>@lang('Tax')</strong> :</label>
                                <select class="form-control" id="e_unit_tax">
                                    <option value="0.00">@lang('NoTax')</option>
                                    @foreach ($taxes as $tax)
                                        <option value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('Tax Type')</strong> :</label>
                                <select class="form-control" id="e_tax_type">
                                    <option value="1">@lang('Exclusive')</option>
                                    <option value="2">@lang('Inclusive')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="submit" class="c-btn button-success float-end me-0">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit selling product modal End-->
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

        $('.submit_button').on('click', function () {

            var value = $(this).val();
            $('#action').val(value);
        });

        $('#customer_id').on('change', function () {

            $('#customer_previous_due').val(parseFloat(0).toFixed(2));

            var customer_id = $(this).val();
            var sale_id = $('#sale_id').val();

            var url = "{{ route('contacts.customer.amounts.branch.wise', ':customer_id') }}";
            var route = url.replace(':customer_id', customer_id);

            var filterObj = {
                branch_id : $('#branch_id').val(),
                from_date : null,
                to_date : null,
            };

            if(customer_id != '' && sale_id == ''){

                $.ajax({
                    url : route,
                    type :'get',
                    data : filterObj,
                    success:function(customerAmounts){

                        if (customerAmounts) {

                            $('#customer_previous_due').val(customerAmounts['total_sale_due']);
                        }

                        calculateTotalAmount();
                    }
                });
            }else{

                calculateTotalAmount();
            }
        });

        var price_groups = '';
        function getPriceGroupProducts(){
            $.ajax({
                url:"{{route('sales.product.price.groups')}}",
                success:function(data) {

                    price_groups = data;
                }
            });
        }
        getPriceGroupProducts();

        var ul = '';
        var selectObjClassName = '';
        $('#sale_invoice_id').mousedown(function(e) {

            afterClickOrFocusSaleInvoiceId();
        }).focus(function(e){

            afterClickOrFocusSaleInvoiceId();
        });

        function afterClickOrFocusSaleInvoiceId() {

            ul = document.getElementById('invoice_list')
            selectObjClassName = 'selected_invoice';
            $('#sale_invoice_id').val('');
            $('#customer_id').val('');
            $('#sale_id').val('');
            $('#sale_products').prop('disabled', true);
            $('#search_product').prop('disabled', false);
            $('.invoice_due_field').addClass('d-none');
            $('.customer_pre_due_field').show();
            $('#return_item_list').empty();
            $('#sale_products').empty();
            $('#sale_products').append('<option value="">@lang('Select Item')</option>');
        }

        function afterFocusSearchItemField() {

            ul = document.getElementById('list')
            selectObjClassName = 'selectProduct';

            $('#sale_id').val('');
        }

        $('#search_product').focus(function(e){

            afterFocusSearchItemField();
        });

        $('#sale_invoice_id').on('input', function () {

            $('.invoice_search_result').hide();

            var invoice_id = $(this).val();

            if (invoice_id === '') {

                $('.invoice_search_result').hide();
                $('#sale_id').val('');
                $('#sale_products').prop('disabled', true);
                $('#search_product').prop('disabled', false);
                return;
            }

            $.ajax({
                url:"{{ url('common/ajax/call/search/final/sale/invoices') }}" + "/" +invoice_id,
                async:true,
                type:'get',
                success:function(data){

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.invoice_search_result').hide();
                    } else {

                        $('.invoice_search_result').show();
                        $('#invoice_list').html(data);
                    }
                }
            });
        });

        $(document).on('click', '#selected_invoice', function (e) {
            e.preventDefault();

            var sale_invoice_id = $(this).html();

            var sale_id = $(this).data('sale_id');
            var customer_id = $(this).data('customer_id');
            var invoice_due = $(this).data('invoice_due');

            $.ajax({
                url:"{{ url('common/ajax/call/get/sale/products') }}" + "/" +sale_id,
                async:true,
                type:'get',
                success:function(sale_products){

                    if (!$.isEmptyObject(sale_products.errorMsg)) {

                        toastr.error(sale_products.errorMsg);
                        return;
                    }

                    $('#sale_invoice_id').val(sale_invoice_id.trim());
                    $('#sale_id').val(sale_id);
                    $('#customer_id').val(customer_id);
                    $('#invoice_due').val(invoice_due);
                    $('.invoice_due_field').removeClass('d-none');
                    $('.customer_pre_due_field').hide();
                    $('.invoice_search_result').hide();
                    $('#return_item_list').empty();

                    $('#sale_products').prop('disabled', false);
                    $('#search_product').prop('disabled', true);

                    $('#sale_products').empty();
                    $('#sale_products').append('<option value="">@lang('Select Item')</option>');

                    $.each(sale_products, function(key, sale_product){

                        $('#sale_products').append(
                            '<option value="'+sale_product.product_id+'" data-product_name="'+sale_product.product_name+'" data-unit_cost_inc_tax="'+(sale_product.variant_cost_with_tax == null ? sale_product.product_cost_with_tax : sale_product.variant_cost_with_tax)+'" data-variant_id="'+sale_product.variant_id+'" data-variant_name="'+sale_product.variant_name+'" data-sale_product_id="'+sale_product.id+'" data-unit_price_exc_tax="'+sale_product.unit_price_exc_tax+'" data-unit_price_inc_tax="'+sale_product.unit_price_inc_tax+'" data-sale_quantity="'+sale_product.quantity+'" data-unit_discount_type="'+sale_product.unit_discount_type+'" data-unit_discount="'+sale_product.unit_discount+'" data-unit_discount_amount="'+sale_product.unit_discount_amount+'" data-unit="'+sale_product.unit+'" data-is_manage_stock="'+sale_product.is_manage_stock+'" data-product_code="'+(sale_product.variant_code != 'null' ? sale_product.variant_code : sale_product.product_code )+'" data-tax_type="'+sale_product.tax_type+'" data-unit_tax_percent="'+sale_product.unit_tax_percent+'" data-unit_tax_amount="'+sale_product.unit_tax_amount+'">'+sale_product.product_name+'</option>'
                        );
                    })
                }
            });
        });

        $(document).on('keyup', 'body', function(e){

            if (e.keyCode == 13){

                $('.'+selectObjClassName).click();
                $('.invoice_search_result').hide();
                $('.select_area').hide();
                $('#list').empty();
                $('#invoice_list').empty();
            }
        });

        var productInfoObj = '';
        $(document).on('change', '#sale_products', function () {

            productInfoObj = {
                sale_product_id : $(this).find(':selected').data('sale_product_id'),
                price_group_id : null,
                product_id : $(this).val(),
                product_name : $(this).find(':selected').data('product_name'),
                product_code : $(this).find(':selected').data('product_code'),
                variant_id : $(this).find(':selected').data('variant_id'),
                variant_name : $(this).find(':selected').data('variant_name'),
                unit : $(this).find(':selected').data('unit'),
                unit_price_exc_tax : $(this).find(':selected').data('unit_price_exc_tax'),
                unit_price_inc_tax : $(this).find(':selected').data('unit_price_inc_tax'),
                unit_cost_inc_tax : $(this).find(':selected').data('unit_cost_inc_tax'),
                sale_quantity : $(this).find(':selected').data('sale_quantity'),
                unit_discount_type : $(this).find(':selected').data('unit_discount_type'),
                unit_discount : $(this).find(':selected').data('unit_discount'),
                unit_discount_amount : $(this).find(':selected').data('unit_discount_amount'),
                tax_type : $(this).find(':selected').data('tax_type'),
                unit_tax_percent : $(this).find(':selected').data('unit_tax_percent'),
                unit_tax_amount : $(this).find(':selected').data('unit_tax_amount'),
            }

            addSingleProduct(productInfoObj);
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

            if ($('#customer_id').val() == '') {

                toastr.error('Please select a listed customer first.');
                $(this).val('');
                return;
            }

            var product_code = $(this).val();
            var __product_code = product_code.replaceAll('/', '~');
            delay(function() { searchProduct(__product_code); }, 200); //sendAjaxical is the name of remote-command
        });

        function searchProduct(product_code) {

            $('#search_product').focus();
            var price_group_id = $('#price_group_id').val();

            $.ajax({
                url:"{{ url('sales/returns/random/search/product/') }}" + "/"+ product_code,
                dataType: 'json',
                success:function(product){

                    if(!$.isEmptyObject(product.errorMsg || product_code == '')){

                        toastr.error(product.errorMsg);
                        $('#search_product').val("");
                        $('.select_area').hide();
                        $('#stock_quantity').val(parseFloat(0).toFixed(2));
                        return;
                    }

                    if(
                        !$.isEmptyObject(product.product)
                        || !$.isEmptyObject(product.variant_product)
                        || !$.isEmptyObject(product.namedProducts)
                    ) {

                        $('#search_product').addClass('is-valid');

                        if(!$.isEmptyObject(product.product)){

                            var product = product.product;

                            if(product.product_variants.length == 0){

                                $('.select_area').hide();
                                $('#search_product').val('');

                                if (product.is_manage_stock == 0) {

                                    $('#stock_quantity').val(parseFloat(qty_limit).toFixed(2));
                                }

                                product_ids = document.querySelectorAll('#product_id');
                                var sameProduct = 0;

                                product_ids.forEach(function(input){

                                    if(input.value == product.id){

                                        sameProduct += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.'+className).closest('tr');
                                        var presentQty = closestTr.find('#return_quantity').val();
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#return_quantity').val(parseFloat(updateQty).toFixed(2));

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

                                    var tax_amount = parseFloat(price / 100 * tax_percent);
                                    var unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);

                                    if (product.tax_type == 2) {

                                        var inclusiveTax = 100 + parseFloat(tax_percent)
                                        var calcAmount = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                        tax_amount = parseFloat(price) - parseFloat(calcAmount);
                                        unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);
                                    }

                                    var name = product.name.length > 35 ? product.name.substring(0, 35)+'...' : product.name;

                                    var tr = '';
                                    tr += '<tr>';
                                    tr += '<td class="text-start">';
                                    tr += '<a href="#" id="edit_product" class="text-success" tabindex="-1">';
                                    tr += '<span class="product_name">'+name+'</span>';
                                    tr += '</a>';
                                    tr += '<input name="unit_costs_inc_tax[]" type="hidden" value="'+product.product_cost_with_tax+'">';
                                    tr += '<input name="sale_product_ids[]" type="hidden" id="sale_product_id">';
                                    tr += '<input value="'+product.id+'" type="hidden" class="productId-'+product.id+'" id="product_id" name="product_ids[]">';
                                    tr += '<input type="hidden" name="tax_types[]" value="'+product.tax_type+'" id="tax_type">';
                                    tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                                    tr += '<input type="hidden" name="unit_discount_types[]" value="1" id="unit_discount_type">';
                                    tr += '<input type="hidden" name="unit_discounts[]" value="0.00" id="unit_discount">';
                                    tr += '<input type="hidden" name="unit_discount_amounts[]" value="0.00" id="unit_discount_amount">';
                                    tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent+'">';
                                    tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(tax_amount).toFixed(2)+'">';
                                    tr += '<input type="hidden" id="qty_limit" value="'+qty_limit+'">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                                    tr += '</td>';

                                    tr += '<td class="text">';
                                    tr += '<b><span class="span_unit">'+product.unit.name+'</span></b>';
                                    tr += '<input  name="units[]" type="hidden" id="unit" value="'+product.unit.name+'">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input name="unit_prices_exc_tax[]" type="hidden" id="unit_price_exc_tax" value="'+parseFloat(price).toFixed(2)+'" tabindex="-1">';
                                    tr += '<input name="unit_prices[]" type="hidden" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" tabindex="-1">';
                                    tr += '<b><span id="span_unit_price">'+parseFloat(unitPriceIncTax).toFixed(2) +'</span></b>';
                                    tr += '</td>';

                                    tr += '<td class="text text-center">';
                                    tr += '<strong><span class="span_subtotal"> '+parseFloat(unitPriceIncTax).toFixed(2)+' </span></strong>';
                                    tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal" tabindex="-1">';
                                    tr += '</td>';

                                    tr += '<td class="text-center">';
                                    tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                    tr += '</td>';
                                    tr += '</tr>';
                                    $('#return_item_list').prepend(tr);
                                    calculateTotalAmount();
                                }
                            }else{

                                var li = "";
                                var imgUrl = "{{asset('uploads/product/thumbnail')}}";
                                var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0.00;
                                $.each(product.product_variants, function(key, variant){

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

                                    li += '<li>';
                                    li += '<a class="select_variant_product" data-product_type="variant" data-p_id="'+product.id+'" data-is_manage_stock="'+product.is_manage_stock+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-tax_type="'+product.tax_type+'" data-unit="'+product.unit.name+'" data-v_tax_percent="'+tax_percent+'" data-v_tax_amount="'+tax_amount+'" data-v_code="'+variant.variant_code+'" data-v_price="'+variant.variant_price+'" data-v_name="'+variant.variant_name+'" data-unit_cost_inc_tax="'+variant.variant_cost_with_tax+'" href="#"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'" tabindex="-1"> '+product.name+' - '+variant.variant_name+' ('+variant.variant_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                    li +='</li>';
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
                            var variant_ids = document.querySelectorAll('#variant_id');
                            var sameVariant = 0;

                            variant_ids.forEach(function(input){

                                if(input.value != 'noid'){

                                    if(input.value == variant_product.id){

                                        sameVariant += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.'+className).closest('tr');
                                        var presentQty = closestTr.find('#return_quantity').val();
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#return_quantity').val(parseFloat(updateQty).toFixed(2));

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

                                var tax_amount = parseFloat(price / 100 * tax_percent);
                                var unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);

                                if (variant_product.product.tax_type == 2) {

                                    var inclusiveTax = 100 + parseFloat(tax_percent)
                                    var calcAmount = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                    tax_amount = parseFloat(price) - parseFloat(calcAmount);
                                    unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);
                                }

                                var name = variant_product.product.name.length > 35 ? variant_product.product.name.substring(0, 35)+'...' : variant_product.product.name;

                                var tr = '';
                                tr += '<tr>';
                                tr += '<td class="text-start">';
                                tr += '<a href="#" id="edit_product" class="text-success" tabindex="-1">';
                                tr += '<span class="product_name">'+name+' -'+variant_product.variant_name+'</span>';
                                tr += '</a>';
                                tr += '<input name="unit_costs_inc_tax[]" type="hidden" value="'+variant_product.variant_cost_with_tax+'">';
                                tr += '<input name="sale_product_ids[]" type="hidden" id="sale_product_id">';
                                tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                                tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                                tr += '<input type="hidden" name="unit_discount_types[]" value="1" id="unit_discount_type">';
                                tr += '<input type="hidden" name="unit_discounts[]" value="0.00" id="unit_discount">';
                                tr += '<input type="hidden" name="unit_discount_amounts[]" value="0.00" id="unit_discount_amount">';
                                tr += '<input type="hidden" name="tax_types[]" value="'+variant_product.product.tax_type+'" id="tax_type">';
                                tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent+'">';
                                tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(tax_amount).toFixed(2)+'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+parseFloat(price).toFixed(2)+'" id="unit_price_exc_tax" tabindex="-1">';
                                tr += '<input name="unit_prices[]" type="hidden" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2) +'" tabindex="-1">';
                                tr += '<b><span id="span_unit_price">'+parseFloat(unitPriceIncTax).toFixed(2) +'</span></b>';
                                tr += '</td>';

                                tr += '<td class="text">';
                                tr += '<b><span class="span_unit">'+variant_product.product.unit.name+'</span></b>';
                                tr += '<input  name="units[]" type="hidden" id="unit" value="'+variant_product.product.unit.name+'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="1.00" required name="return_quantities[]" type="number" step="any" class="form-control text-center" id="return_quantity">';
                                tr += '</td>';

                                tr += '<td class="text text-center">';
                                tr += '<strong><span class="span_subtotal">'+parseFloat(unitPriceIncTax).toFixed(2)+'</span></strong>';
                                tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal" tabindex="-1">';
                                tr += '</td>';
                                tr += '<td class="text-center">';
                                tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                tr += '</td>';
                                tr += '</tr>';
                                $('#return_item_list').prepend(tr);
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
                                        li += '<a href="#" class="select_variant_product" data-product_type="variant" data-p_id="'+product.id+'" data-is_manage_stock="'+product.is_manage_stock+'" data-v_id="'+product.variant_id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-tax_type="'+product.tax_type+'" data-unit="'+product.unit_name+'" data-v_tax_percent="'+tax_percent+'" data-v_tax_amount="'+tax_amount+'" data-v_code="'+product.variant_code+'" data-v_price_exc_tax="'+product.variant_price+'" data-v_price_inc_tax="'+parseFloat(unitPriceIncTax).toFixed(2)+'" data-v_name="'+product.variant_name+'" data-unit_cost_inc_tax="'+product.variant_cost_with_tax+'" tabindex="-1"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+product.variant_name+' ('+product.variant_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
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
                                        li += '<a href="#" class="select_single_product" data-product_type="single" data-p_id="'+product.id+'" data-is_manage_stock="'+product.is_manage_stock+'" data-p_name="'+product.name+'" data-unit="'+product.unit_name+'" data-p_code="'+product.product_code+'" data-p_price_exc_tax="'+product.product_price+'" data-p_price_inc_tax="'+parseFloat(unitPriceIncTax).toFixed(2)+'" data-p_tax_percent="'+tax_percent+'" data-tax_type="'+product.tax_type+'" data-p_tax_amount="'+tax_amount+'" data-unit_cost_inc_tax="'+product.product_cost_with_tax+'" tabindex="-1"><img style="width:20px; height:20px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' ('+product.product_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                        li +='</li>';
                                    }
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                            }
                        }
                    }else{

                        $('#search_product').addClass('is-invalid');
                        toastr.error('Product not found.', 'Failed');
                        $('#search_product').select();
                    }
                }
            });
        }

        // select single product and add stock adjustment table
        $(document).on('click', '.select_single_product', function (e){
            e.preventDefault();
            $('.select_area').hide();

            productInfoObj = {
                sale_product_id : '',
                price_group_id : price_group_id,
                product_id : $(this).data('p_id'),
                product_name : $(this).data('p_name'),
                product_code : $(this).data('p_code'),
                variant_id : 'noid',
                variant_name : '',
                unit : $(this).data('unit'),
                unit_price_exc_tax : $(this).data('p_price_exc_tax'),
                unit_price_inc_tax : $(this).data('p_price_inc_tax'),
                unit_cost_inc_tax : $(this).data('unit_cost_inc_tax'),
                sale_quantity : 0,
                unit_discount_type : 1,
                unit_discount : 0,
                unit_discount_amount : 0,
                tax_type : $(this).data('tax_type'),
                unit_tax_percent : $(this).data('p_tax_percent'),
                unit_tax_amount : $(this).data('p_tax_amount'),
            }

            addSingleProduct(productInfoObj);
            $('#search_product').val('');
            document.getElementById('search_product').focus();
        });

        function addSingleProduct(data) {

            if (data.is_manage_stock == 1) {

                $('#stock_quantity').val(parseFloat(data.stock).toFixed(2));
            }

            var product_ids = document.querySelectorAll('#product_id');
            var sameProduct = 0;

            product_ids.forEach(function(input){

                if(input.value == data.product_id){

                    sameProduct += 1;
                    var className = input.getAttribute('class');
                    // get closest table row for increasing qty and re calculate product amount
                    var closestTr = $('.'+className).closest('tr');
                    var presentQty = closestTr.find('#return_quantity').val();
                    var updateQty = parseFloat(presentQty) + 1;
                    closestTr.find('#return_quantity').val(parseFloat(updateQty).toFixed(2));
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

                var price = data.unit_price_exc_tax;
                var name = data.product_name.length > 35 ? data.product_name.substring(0, 35)+'...' : data.product_name;

                var tr = '';
                tr += '<tr>';
                tr += '<td class="text-start">';
                tr += '<a href="#" id="edit_product" class="text-success" id="edit_product" tabindex="-1">';
                tr += '<span class="product_name">'+ data.product_name +'</span>';
                tr += '</a>';
                tr += '<input name="sale_product_ids[]" type="hidden" id="sale_product_id" value="'+data.sale_product_id+'">';
                tr += '<input name="unit_costs_inc_tax[]" type="hidden" value="'+data.unit_cost_inc_tax+'">';
                tr += '<input value="'+data.product_id+'" type="hidden" class="productId-'+data.product_id+'" id="product_id" name="product_ids[]">';
                tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                tr += '<input type="hidden" name="unit_discount_types[]" value="'+data.unit_discount_type+'" id="unit_discount_type">';
                tr += '<input type="hidden" name="unit_discounts[]" value="'+data.unit_discount+'" id="unit_discount">';
                tr += '<input type="hidden" name="unit_discount_amounts[]" value="'+data.unit_discount_amount+'" id="unit_discount_amount">';
                tr += '<input type="hidden" name="tax_types[]" value="'+data.tax_type+'" id="tax_type">';
                tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+data.unit_tax_percent+'">';
                tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(data.unit_tax_amount).toFixed(2)+'">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly name="unit_prices_exc_tax[]" type="hidden" id="unit_price_exc_tax" value="'+parseFloat(price).toFixed(2)+'" tabindex="-1">';

                var unitPriceIncTax = parseFloat(price) / 100 * parseFloat(data.unit_tax_percent) + parseFloat(price);

                if (data.tax_type == 2) {

                    var inclusiveTax = 100 + parseFloat(data.unit_tax_percent);
                    var calcTax = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                    var __tax_amount = parseFloat(price) - parseFloat(calcTax);
                    unitPriceIncTax = parseFloat(price) + parseFloat(__tax_amount);
                }

                tr += '<input name="unit_prices[]" type="hidden" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" tabindex="-1">';
                tr += '<b><span id="span_unit_price"> '+parseFloat(unitPriceIncTax).toFixed(2)+' </span></b>';
                tr += '</td>';

                tr += '<td class="text">';
                tr += '<b><span class="span_unit">'+data.unit+'</span></b>';
                tr += '<input  name="units[]" type="hidden" id="unit" value="'+data.unit+'">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input type="number" step="any" value="1.00" required name="return_quantities[]" class="form-control text-center" id="return_quantity">';
                tr += '</td>';

                tr += '<td class="text text-center">';
                tr += '<strong><span class="span_subtotal"> '+parseFloat(unitPriceIncTax).toFixed(2)+' </span></strong>';
                tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" name="subtotals[]" type="hidden" id="subtotal" tabindex="-1">';
                tr += '</td>';

                tr += '<td class="text-center">';
                tr += '<a href="#" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                tr += '</td>';
                tr += '</tr>';
                $('#return_item_list').prepend(tr);
                calculateTotalAmount();
            }
        }

         // select single product and add stock adjustment table
        $(document).on('click', '.select_variant_product', function (e){
            e.preventDefault();
            var price_group_id = $('#price_group_id').val();
            $('.select_area').hide();

            productInfoObj = {
                sale_product_id : '',
                price_group_id : price_group_id,
                product_id : $(this).data('p_id'),
                product_name : $(this).data('p_name'),
                product_code : $(this).data('v_code'),
                variant_id : $(this).data('v_id'),
                variant_name : $(this).data('v_name'),
                unit : $(this).data('unit'),
                unit_price_exc_tax : $(this).data('v_price_exc_tax'),
                unit_price_inc_tax : $(this).data('v_price_inc_tax'),
                unit_cost_inc_tax : $(this).data('unit_cost_inc_tax'),
                sale_quantity : 0,
                unit_discount_type : 1,
                unit_discount : 0,
                unit_discount_amount : 0,
                tax_type : $(this).data('tax_type'),
                unit_tax_percent : $(this).data('v_tax_percent'),
                unit_tax_amount : $(this).data('v_tax_amount'),
            }

            addVariantProduct(productInfoObj);
            $('#search_product').val('');
            document.getElementById('search_product').focus();
        });

        function addVariantProduct(data) {

            if (data.is_manage_stock == 1) {

                $('#stock_quantity').val(parseFloat(data.stock).toFixed(2));
            }

            var variant_ids = document.querySelectorAll('#variant_id');
            var sameVariant = 0;

            variant_ids.forEach(function(input){

                if(input.value == data.variant_id){

                    sameVariant += 1;
                    var className = input.getAttribute('class');
                    // get closest table row for increasing qty and re calculate product amount
                    var closestTr = $('.'+className).closest('tr');
                    var presentQty = closestTr.find('#return_quantity').val();
                    var updateQty = parseFloat(presentQty) + 1;
                    closestTr.find('#return_quantity').val(parseFloat(updateQty).toFixed(2));
                    //Update Subtotal
                    var unitPrice = closestTr.find('#unit_price').val();
                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                    closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                    closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                    calculateTotalAmount();

                    return;
                }
            });

            if(sameVariant == 0){

                var price = data.unit_price_exc_tax;

                var name = data.product_name.length > 35 ? data.product_name.substring(0, 35)+'...' : data.product_name;

                var tr = '';
                tr += '<tr>';
                tr += '<td class="text-start">';
                tr += '<a href="#" id="edit_product" class="text-success" id="edit_product" tabindex="-1">';
                tr += '<span class="product_name">'+ data.product_name + ' - ' + data.variant_name +'</span>';
                tr += '</a>';
                tr += '<input name="unit_costs_inc_tax[]" type="hidden" value="'+data.unit_cost_inc_tax+'">';
                tr += '<input name="sale_product_ids[]" type="hidden" id="sale_product_id" value="'+data.sale_product_id+'">';
                tr += '<input value="'+data.product_id+'" type="hidden" class="productId-'+data.product_id+'" id="product_id" name="product_ids[]">';
                tr += '<input value="'+ data.variant_id +'" type="hidden" class="variantId-'+data.variant_id+'" id="variant_id" name="variant_ids[]">';
                tr += '<input type="hidden" name="unit_discount_types[]" value="'+data.unit_discount_type+'" id="unit_discount_type">';
                tr += '<input type="hidden" name="unit_discounts[]" value="'+data.unit_discount+'" id="unit_discount">';
                tr += '<input type="hidden" name="unit_discount_amounts[]" value="'+data.unit_discount_amount+'" id="unit_discount_amount">';
                tr += '<input type="hidden" name="tax_types[]" value="'+data.tax_type+'" id="tax_type">';
                tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+data.unit_tax_percent+'">';
                tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(data.unit_tax_amount).toFixed(2)+'">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly name="unit_prices_exc_tax[]" type="hidden" id="unit_price_exc_tax" value="'+parseFloat(price).toFixed(2)+'" tabindex="-1">';

                var unitPriceIncTax = parseFloat(price) / 100 * parseFloat(data.unit_tax_percent) + parseFloat(price);

                if (data.tax_type == 2) {

                    var inclusiveTax = 100 + parseFloat(data.unit_tax_percent);
                    var calcTax = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                    var __tax_amount = parseFloat(price) - parseFloat(calcTax);
                    unitPriceIncTax = parseFloat(price) + parseFloat(__tax_amount);
                }

                tr += '<input name="unit_prices[]" type="hidden" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" tabindex="-1">';
                tr += '<b><span id="span_unit_price"> '+parseFloat(unitPriceIncTax).toFixed(2)+' </span></b>';
                tr += '</td>';

                tr += '<td class="text">';
                tr += '<b><span class="span_unit">'+data.unit+'</span></b>';
                tr += '<input  name="units[]" type="hidden" id="unit" value="'+data.unit+'">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input type="number" step="any" value="1.00" required name="return_quantities[]" class="form-control text-center" id="return_quantity">';
                tr += '</td>';

                tr += '<td class="text text-center">';
                tr += '<strong><span class="span_subtotal"> '+parseFloat(unitPriceIncTax).toFixed(2)+' </span></strong>';
                tr += '<input type="hidden" id="subtotal" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" name="subtotals[]" tabindex="-1">';
                tr += '</td>';

                tr += '<td class="text-center">';
                tr += '<a href="#" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                tr += '</td>';
                tr += '</tr>';
                $('#return_item_list').prepend(tr);
                calculateTotalAmount();
            }
        }

        // Return Quantity increase or dicrease and clculate row amount
        $(document).on('input', '#return_quantity', function(){

            var return_quantity = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            var unitPrice = tr.find('#unit_price').val();
            var calcSubtotal = parseFloat(unitPrice) * parseFloat(return_quantity);
            tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
            calculateTotalAmount();
        });

        // Calculate total amount functionalitie
        function calculateTotalAmount(){

            var quantities = document.querySelectorAll('#return_quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            // Update Total Item

            var total_item = 0;
            var total_qty = 0;
            quantities.forEach(function(qty){

                total_item += 1;
                total_qty += parseFloat(qty.value);
            });

            $('#total_item').val(parseFloat(total_item));
            $('#total_qty').val(parseFloat(total_qty));

            // Update Net total Amount
            var netTotalAmount = 0;
            subtotals.forEach(function(subtotal){

                netTotalAmount += parseFloat(subtotal.value);
            });

            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

            if ($('#return_discount_type').val() == 2) {

                var returnDisAmount = parseFloat(netTotalAmount) /100 * parseFloat($('#return_discount').val() ? $('#return_discount').val() : 0);
                $('#return_discount_amount').val(parseFloat(returnDisAmount).toFixed(2));
            } else {

                var returnDiscount = $('#return_discount').val() ? $('#return_discount').val() : 0;
                $('#return_discount_amount').val(parseFloat(returnDiscount).toFixed(2));
            }

            var returnDiscountAmount = $('#return_discount_amount').val() ? $('#return_discount_amount').val() : 0;

            // Calc order tax amount
            var returnTax = $('#return_tax').val() ? $('#return_tax').val() : 0;
            var calReturnTaxAmount = (parseFloat(netTotalAmount) - parseFloat(returnDiscountAmount)) / 100 * parseFloat(returnTax) ;

            $('#return_tax_amount').val(parseFloat(calReturnTaxAmount).toFixed(2));

            var previousDue = 0;
            if ($('#sale_id').val() == '') {

                previousDue = $('#customer_previous_due').val() ? $('#customer_previous_due').val() : 0;
            }else{

                previousDue = $('#invoice_due').val() ? $('#invoice_due').val() : 0;
            }

            var calcTotalAmount = parseFloat(netTotalAmount)
                                        - parseFloat(returnDiscountAmount)
                                        + parseFloat(calReturnTaxAmount);

            $('#total_return_amount').val(parseFloat(calcTotalAmount).toFixed(2));

            var calcTotalRefundableAmount = parseFloat(calcTotalAmount) - parseFloat(previousDue);

            $('#total_refundable_amount').val(parseFloat(calcTotalRefundableAmount).toFixed(2));
        }

        $(document).on('input', '#return_discount', function(){

            calculateTotalAmount();
        });

        $(document).on('input', '#return_tax', function(){

            calculateTotalAmount();
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

            var parentTableRow = $(this).closest('tr');
            tableRowIndex = parentTableRow.index();
            var quantity = parentTableRow.find('#return_quantity').val();
            var product_name = parentTableRow.find('.product_name').html();
            var product_code = parentTableRow.find('.product_code').html();
            var unit_price_exc_tax = parentTableRow.find('#unit_price_exc_tax').val();
            var unit_tax_percent = parentTableRow.find('#unit_tax_percent').val();
            var unit_tax_amount = parentTableRow.find('#unit_tax_amount').val();
            var unit_tax_type = parentTableRow.find('#tax_type').val();
            var unit_discount_type = parentTableRow.find('#unit_discount_type').val();
            var unit_discount = parentTableRow.find('#unit_discount').val();
            var unit_discount_amount = parentTableRow.find('#unit_discount_amount').val();

            // Set modal heading
            $('#product_info').html(product_name);
            $('#e_return_quantity').val(parseFloat(quantity).toFixed(2));
            $('#e_unit_price_exc_tax').val(parseFloat(unit_price_exc_tax).toFixed(2));
            $('#e_unit_discount_type').val(unit_discount_type);
            $('#e_unit_discount').val(unit_discount);
            $('#e_discount_amount').val(unit_discount_amount);
            $('#e_tax_type').val(unit_tax_type);
            $('#e_unit_tax').val(parseFloat(unit_tax_percent).toFixed(2));
            $('#editProductModal').modal('show');
        });

        //Update Selling producdt
        $('#update_return_product').on('submit', function (e) {
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

            var e_quantity = $('#e_return_quantity').val();
            var e_unit_price_exc_tax = $('#e_unit_price_exc_tax').val();
            var e_unit_discount_type = $('#e_unit_discount_type').val() ? $('#e_unit_discount_type').val() : 1;
            var e_unit_discount = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
            var e_unit_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0.00;
            var e_unit_tax_type = $('#e_tax_type').val() ? $('#e_tax_type').val() : 1;
            var e_unit_tax_percent = $('#e_unit_tax').val() ? $('#e_unit_tax').val() : 0.00;

            var productTableRow = $('#return_item_list tr:nth-child(' + (tableRowIndex + 1) + ')');
            // calculate unit tax

            productTableRow.find('#return_quantity').val(parseFloat(e_quantity).toFixed(2));
            productTableRow.find('#unit_price_exc_tax').val(parseFloat(e_unit_price_exc_tax).toFixed(2));
            productTableRow.find('#unit_discount_type').val(e_unit_discount_type);
            productTableRow.find('#unit_discount').val(parseFloat(e_unit_discount).toFixed(2));
            productTableRow.find('#unit_discount_amount').val(parseFloat(e_unit_discount_amount).toFixed(2));

            var calcUnitPriceWithDiscount = parseFloat(e_unit_price_exc_tax) - parseFloat(e_unit_discount_amount);
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
            productTableRow.find('#span_unit_price').html(parseFloat(calcUnitPriceIncTax).toFixed(2));
            var calcSubtotal = parseFloat(calcUnitPriceIncTax) * parseFloat(e_quantity);
            productTableRow.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            productTableRow.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
            $('#editProductModal').modal('hide');
            calculateTotalAmount();
        });

        // change unit price
        $('#e_unit_price_exc_tax').on('input', function () {

            var unit_price = $(this).val() ? $(this).val() : 0.00;
            var discountValue = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;

            if ($('#e_unit_discount_type').val() == 1) {

                $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
            }else{

                var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
                $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
            }
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

        //Add purchase request by ajax
        $('#add_sale_return_form').on('submit', function(e){
            e.preventDefault();
            stockErrors = 0;
            var status = $('#status').val();

            var totalItem = $('#total_item').val();

            if (parseFloat(totalItem) == 0) {

                toastr.error('Return Product table is empty.','Some thing went wrong.');
                return;
            }

            var total_refundable_amount = $('#total_refundable_amount').val() ? $('#total_refundable_amount').val() : 0;
            var paying_amount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;

            if (parseFloat(paying_amount) > 0 && parseFloat(paying_amount) > parseFloat(total_refundable_amount)) {

                toastr.error('Refunding amount must not be greater then total refundable amount.');
                return;
            }

            $('.loading_button').show();
            $('.submit_button').prop('type', 'button');
            var url = $(this).attr('action');

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

                        toastr.error(data.errorMsg);
                        return;
                    }

                    if(!$.isEmptyObject(data.successMsg)){

                        toastr.success(data.successMsg);
                        afterCreateSaleReturn();
                    }else{

                        toastr.success('Successfully sale return is created.');
                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                            removeInline: false,
                            printDelay: 1000,
                            header: null,
                        });
                        afterCreateSaleReturn();
                    }
                },error: function(err) {

                    $('.submit_button').prop('type', 'sumbit');
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

        function afterCreateSaleReturn() {

            $('.loading_button').hide();
            $('#sale_id').val('');
            $('#add_sale_return_form')[0].reset();
            $('#return_item_list').empty();

            $('.invoice_due_field').addClass('d-none');
            $('.customer_pre_due_field').show();

            $('#sale_products').prop('disabled', true);
            $('#search_product').prop('disabled', false);

            document.getElementById('search_product').focus();
        }

        // Automatic remove searching product is found signal
        setInterval(function(){

            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function(){

            $('#search_product').removeClass('is-valid');
        }, 1000);

        $(document).keypress(".scanable",function(event){

            if (event.which == '10' || event.which == '13') {

                event.preventDefault();
            }
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
    <script src="/assets/plugins/custom/select_li/selectli.custom.js"></script>
@endpush
