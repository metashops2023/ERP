<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>MetaShops</title>
    <!-- Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="/backend/asset/css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/backend/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link href="/backend/css/typography.css" rel="stylesheet" type="text/css">
    <link href="/backend/css/body.css" rel="stylesheet" type="text/css">
    <link href="/backend/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/backend/css/gradient.css" rel="stylesheet" type="text/css">

    <!-- Calculator -->
    <link rel="stylesheet" href="{{ asset('backend/asset/css/calculator.css') }}">
    <link rel="stylesheet" href="/backend/asset/css/comon.css">
    <link rel="stylesheet" href="/backend/asset/css/pos.css">
    <link href="/assets/plugins/custom/toastrjs/toastr.min.css" rel="stylesheet"
    type="text/css"/>
    <link href="/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="/backend/asset/css/style.css">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/pos-theme.css') }}">
    <style> .btn-bg {padding: 2px!important;} </style>
    @stack('css')
    <script src="{{asset('backend/asset/cdn/js/jquery-3.6.0.js')}}"></script>
    <!--Toaster.js js link-->
    <script src="/assets/plugins/custom/toastrjs/toastr.min.js"></script>
    <!--Toaster.js js link end-->

    <script src="/backend/asset/js/bootstrap.bundle.min.js "></script>
    <script src="/assets/plugins/custom/print_this/printThis.min.js"></script>
    <script src="/assets/plugins/custom/Shortcuts-master/shortcuts.js"></script>
    <!--alert js link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="/assets/plugins/custom/digital_clock/digital_clock.js"></script>
    <script src="{{asset('backend/js/number-bdt-formater.js')}}"></script>
</head>

<body class="{{ isset(json_decode($generalSettings->system, true)['theme_color']) ?  json_decode($generalSettings->system, true)['theme_color'] : 'red-theme' }}">
    <form id="pos_submit_form" action="{{ route('sales.pos.store') }}" method="POST">
        @csrf
        <div class="pos-body">
            <div class="main-wraper">
                @yield('pos_content')
            </div>
        </div>

        <!--Add Payment modal-->
        <div class="modal fade in" id="otherPaymentMethod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog col-50-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="payment_heading">Choose Payment method</h6>
                        <a href="" class="close-btn" id="cancel_pay_mathod" tabindex="-1"><span
                            class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <!--begin::Form-->
                        <div class="form-group row single_payment">
                            <div class="col-md-4">
                                <label><strong>Payment Method :</strong> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark"></i></span>
                                    </div>
                                    <select name="payment_method_id" class="form-control"  id="payment_method_id">
                                        @foreach ($methods as $method)
                                            <option
                                                data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                                value="{{ $method->id }}">
                                                {{ $method->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label><strong>Debit Account :</strong> </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark"></i></span>
                                    </div>
                                    <select name="account_id" class="form-control" id="account_id">
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">
                                                @php
                                                    $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                                @endphp
                                                {{ $account->name.$accountType }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><strong> Payment Note :</strong></label>
                            <textarea name="payment_note" class="form-control form-control-sm" id="note" cols="30" rows="3" placeholder="Note"></textarea>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <a href="" class="c-btn button-success me-0 float-end" id="submit_btn" data-button_type="1" data-action_id="1" tabindex="-1">Confirm (F10)</a>
                                <button type="button" class="c-btn btn_orange float-end" id="cancel_pay_mathod">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Add Payment modal End-->

        @if (json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == '1')
        <!--Redeem Point modal-->
            <div class="modal fade" id="pointReedemModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog col-40-modal" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="exampleModalLabel">Reedem Point</h6>
                            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label><b>Available Point :</b> </label>
                                <input type="number" step="any" name="available_point" id="available_point" class="form-control" value="0" readonly>
                            </div>

                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><b>Redeemed :</b> </label>
                                    <input type="number" step="any" name="total_redeem_point" id="total_redeem_point" class="form-control">
                                    <input type="number" step="any" name="pre_redeemed" id="pre_redeemed" class="d-none" value="0">
                                    <input type="number" step="any" name="pre_redeemed_amount" id="pre_redeemed_amount" class="d-none" value="0">
                                </div>

                                <div class="col-md-6">
                                    <label><b>Redeem Amount :</b> </label>
                                    <input type="number" step="any" name="redeem_amount" id="redeem_amount" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                    <a href="#" class="c-btn button-success ms-1 float-end" id="redeem_btn" tabindex="-1">Redeem</a>
                                    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end me-0">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!--Redeem Point modal-->
    </form>

     <!-- Recent transection list modal-->
     <div class="modal fade" id="recentTransModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Recent Transections</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="tab_list_area">
                        <ul class="list-unstyled">
                            <li>
                                <a id="tab_btn" class="tab_btn tab_active text-white" href="{{ url('common/ajax/call/recent/sales/2') }}" tabindex="-1"><i class="fas fa-info-circle"></i> Final</a>
                            </li>

                            <li>
                                <a id="tab_btn" class="tab_btn text-white" href="{{url('common/ajax/call/recent/quotations/2')}}" tabindex="-1"><i class="fas fa-scroll"></i>Quotation</a>
                            </li>

                            <li>
                                <a id="tab_btn" class="tab_btn text-white" href="{{url('common/ajax/call/recent/drafts/2')}}" tabindex="-1"><i class="fas fa-shopping-bag"></i> Draft</a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab_contant">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="data_preloader" id="recent_trans_preloader">
                                        <h6><i class="fas fa-spinner"></i> Processing...</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table modal-table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">SL</th>
                                                    <th class="text-start">Reference/InvoiceId</th>
                                                    <th class="text-start">Customer</th>
                                                    <th class="text-start">Total</th>
                                                    <th class="text-start">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data-list" id="transection_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end me-0">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Recent transection list modal end-->

    <!-- Hold invoice list modal -->
    <div class="modal fade" id="holdInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Hold Invoices</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table_area">
                                <div class="data_preloader" id="hold_invoice_preloader">
                                    <h6><i class="fas fa-spinner"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive" id="hold_invoices"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end me-0">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hold invoice list modal End-->

    @if (auth()->user()->permission->product['product_add'] == '1')
        <!--Add Product Modal-->
        <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span
                            class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="add_product_body"></div>
                </div>
            </div>
        </div>
        <!--Add Product Modal End-->
    @endif

    <!--Add Customer Modal-->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Customer</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_customer_modal_body"></div>
            </div>
        </div>
    </div>
    <!--Add Customer Modal-->

    <!-- Edit selling product modal-->
    <div class="modal fade" id="suspendedSalesModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content">
                <div class="data_preloader" id="suspend_preloader">
                    <h6><i class="fas fa-spinner"></i> Processing...</h6>
                </div>
                <div class="modal-header">
                    <h6 class="modal-title">Suspended Sales</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="suspended_sale_list"></div>
            </div>
        </div>
    </div>
    <!-- Edit selling product modal end-->


    <!-- Edit selling product modal-->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="product_info">Samsung A30</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="update_selling_product">
                        @if (auth()->user()->permission->sale['view_product_cost_is_sale_screed'] == '1')
                            <p>
                                <span class="btn btn-sm btn-primary d-none" id="show_cost_section">
                                    <span>{{ json_decode($generalSettings->business, true)['currency'] }}</span>
                                    <span id="unit_cost">1,200.00</span>
                                </span>

                                <span class="btn btn-sm btn-info text-white" id="show_cost_button">Cost</span>
                            </p>
                        @endif

                        <div class="form-group mt-1">
                            <label> <strong>Quantity</strong>  : <span class="text-danger">*</span></label>
                            <input type="number" readonly class="form-control edit_input" data-name="Quantity" id="e_quantity" placeholder="Quantity" value=""/>
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>Unit Price Exc.Tax</strong>  : <span class="text-danger">*</span></label>
                            <input type="number" {{ auth()->user()->permission->sale['edit_price_pos_screen'] == '1' ? '' : 'readonly' }} step="any" class="form-control form-control-sm edit_input" data-name="Unit price" id="e_unit_price" placeholder="Unit price" value=""/>
                            <span class="error error_e_unit_price"></span>
                        </div>

                        @if (auth()->user()->permission->sale['edit_discount_pos_screen'] == '1')
                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><strong>Discount Type</strong>  :</label>
                                    <select class="form-control" id="e_unit_discount_type">
                                        <option value="2">Percentage</option>
                                        <option value="1">Fixed</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label><strong>Discount</strong>  :</label>
                                    <input type="number" class="form-control" id="e_unit_discount" value="0.00"/>
                                    <input type="hidden" id="e_discount_amount"/>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>Tax</strong> :</label>
                                <select class="form-control" id="e_unit_tax"></select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>Tax Type</strong> :</label>
                                <select class="form-control" id="e_tax_type">
                                    <option value="1">Exclusive</option>
                                    <option value="2">Inclusive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Sale Unit</strong> :</label>
                            <select class="form-control" id="e_unit"></select>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="c-btn button-success me-0 float-end">Update</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit selling product modal end-->

    <!-- Show stock modal-->
    <div class="modal fade" id="showStockModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content">
                <div class="data_preloader mt-5" id="stock_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                </div>
                <div class="modal-header">
                    <h6 class="modal-title">Item Stocks</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="stock_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Show stock modal end-->

    <!-- Close Register modal -->
    <div class="modal fade" id="closeRegisterModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="close_register_content"></div>
        </div>
    </div>
    <!-- Close Register modal End-->

    <!-- Cash Register Details modal -->
    <div class="modal fade" id="cashRegisterDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="cash_register_details_content"></div>
        </div>
    </div>
    <!-- Cash Register Details modal End-->

    <!--Quick Cash receive modal-->
    <div class="modal fade in" id="cashReceiveMethod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog col-45-modal" role="document">
            <div class="modal-content modal-middle">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">Quick Cash Receive</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="form-group row ">
                        <div class="col-md-6">
                            <div class="input-box-4 bg-dark">
                                <label class="text-white big_label"><strong>Total Payable :</strong> </label>
                                <input readonly type="text" class="form-control big_field" id="modal_total_payable" value="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-box-2 bg-info">
                                <label class="text-white big_label"><strong>Change :</strong></label>
                                <input type="text" class="form-control big_field text-info" id="modal_change_amount" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-6">
                            <div class="input-box bg-success">
                                <label class="text-white big_label"><strong>Cash Receive :</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="modal_paying_amount" class="form-control text-success big_field m-paying" id="modal_paying_amount" value="0" autofocus>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-box-3 bg-danger">
                                <label class="text-white big_label"><strong>Due :</strong> </label>
                                <input type="text" class="form-control text-danger big_field" id="modal_total_due" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <a href="#" class="c-btn button-success ms-1 float-end" id="submit_btn" data-button_type="1" data-action_id="1" tabindex="-1">Cash (F10)</a>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end me-0">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Quick Cash receive modal End-->

    <!-- Exchange modal -->
    <div class="modal fade" id="exchangeModal"tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content" id="exchange_body">
                <div class="modal-header">
                    <h6 class="modal-title">Exchange</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
                </div>

                <div class="modal-body">
                    <div class="form-area">
                        <form id="search_inv_form" action="{{ route('sales.pos.serc.ex.inv') }}" method="GET">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input required type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Search invoice">
                                </div>

                                <div class="col-md-3">
                                    <input required type="text" name="customer_id" id="customer_id" class="form-control" placeholder="Search By customer">
                                </div>

                                <div class="col-md-3">
                                    <input required type="text" name="customer_phone" id="customer_phone" class="form-control" placeholder="Search By phone number">
                                </div>

                                <div class="col-md-2">
                                    <div class="btn_30_blue m-0">
                                        <a id="submit_form_btn" href="#" tabindex="-1"><i class="fas fa-plus-square"></i> Search</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="preloader_area">
                            <div class="data_preloader" id="get_inv_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2" id="invoice_description"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Exchange modal End-->

    <!--Add shortcut menu modal-->
    <div class="modal fade" id="shortcutMenuModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">Add POS Shortcut Menus</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="modal-body_shortcuts"></div>
            </div>
        </div>
    </div>

    <!--Data delete form-->
    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
    <!--Data delete form end-->

    <script src="/assets/plugins/custom/select_li/selectli.js"></script>
    <script src="{{ asset('backend/asset/js/pos.js') }}"></script>
    <script src="{{ asset('backend/asset/js/pos-amount-calculation.js') }}"></script>
    <script src="/backend/asset/js/sale.exchange.js"></script>
    <script>
        // Get all pos shortcut menus by ajax
        function allPosShortcutMenus() {

            $.ajax({
                url: "{{ route('pos.short.menus.show') }}",
                type: 'get',
                success: function(data) {
                    $('#pos-shortcut-menus').html(data);
                }
            });
        }
        allPosShortcutMenus();

        $('#cash_register_details').on('click', function (e) {
            e.preventDefault();

            $.ajax({
                url:"{{route('sales.cash.register.details')}}",
                type:'get',
                success:function(data){

                    $('#cash_register_details_content').html(data);
                    $('#cashRegisterDetailsModal').modal('show');
                }
            });
        });

        $('#close_register').on('click', function (e) {
            e.preventDefault();

            $.ajax({
                url:"{{route('sales.cash.register.close.modal.view')}}",
                type:'get',
                success:function(data){

                    $('#close_register_content').html(data);
                    $('#closeRegisterModal').modal('show');
                }
            });
        });

        $(document).on('click', '#pos_exit_button',function(e){
            e.preventDefault();

            var url = $(this).attr('href');
            $('#payment_deleted_form').attr('action', url);

            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure, you went to exit?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {window.location = "{{ route('dashboard.dashboard') }}";}},
                    'No': {'class': 'no btn-danger','action': function() { console.log('Deleted canceled.')}}
                }
            });
        });

        //Key shortcut for to the settings
        shortcuts.add('ctrl+q',function() {

            window.location = "{{ route('settings.general.index') }}";
        });

        var scrollContainer = document.querySelector("#pos-shortcut-menus");
        scrollContainer.addEventListener("wheel", (evt) => {

            evt.preventDefault();
            scrollContainer.scrollLeft += evt.deltaY;
        });

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
    @stack('js')
</body>
</html>
