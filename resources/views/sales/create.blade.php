@extends('layout.master')
@push('stylesheets')
    <link href="/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 88.3%;z-index: 9999999;padding: 0;left: 6%;display: none;border: 1px solid #706a6d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 0px 2px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 11px;padding: 2px 2px;display: block;border: 1px solid lightgray; margin: 2px 0px;}
        .select_area ul li a:hover {background-color: #999396;color: #fff;}
        .selectProduct {background-color: #746e70!important;color: #fff !important;}
        .input-group-text-sale {font-size: 7px !important;}
        b{font-weight: 500; font-family: Arial, Helvetica, sans-serif;}
        .border_red { border: 1px solid red!important; }
        #display_pre_due{font-weight: 600;}
        input[type=number]#quantity::-webkit-inner-spin-button,
        input[type=number]#quantity::-webkit-outer-spin-button {opacity: 1;margin: 0;}

        .select2-container--default .select2-selection--single {margin-bottom: 1px;}
        .select2-container .select2-selection--single {overflow: hidden;}
        .select2-container .select2-selection--single .select2-selection__rendered {display: inline-block;width: 143px;}
        /*.select2-selection:focus {
             box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%);
        } */

        .select2-selection:focus {
            box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%);
            color: #212529;
            background-color: #fff;
            border-color: #86b7fe;
            outline: 0;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="/backend/asset/css/select2.min.css"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_sale_form" action="{{ route('sales.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-8">
                                            <h6>@lang('Add Sale')</h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('Customer') :</b> </label>
                                                <div class="col-8">
                                                    <div class="input-group">
                                                        <div style="display: inline-block; margin-bottom: 2px;">
                                                            <select style="margin-bottom: 2px;" name="customer_id" class="form-control select2" id="customer_id">
                                                            <option value="">@lang('Walk-In-Customer')</option>
                                                            @foreach ($customers as $customer)
                                                                <option data-customer_name="{{ $customer->name }}" data-customer_phone="{{ $customer->phone }}" value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')' }}</option>
                                                            @endforeach
                                                        </select></div>

                                                        <div style="display: inline-block">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button" id="addCustomer">
                                                                    <i class="fas fa-plus-square text-dark"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"> <b>@lang('Warehouse') :</b> </label>
                                                <div class="col-8">
                                                    <input type="hidden" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}" id="branch_name">
                                                    <input type="hidden" value="{{ auth()->user()->branch_id ? auth()->user()->branch_id : 'NULL' }}" id="branch_id">
                                                    <select name="warehouse_id" class="form-control" id="warehouse_id">
                                                        <option value="">@lang('Select Warehouse')</option>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option data-w_name="{{ $warehouse->name.'/'.$warehouse->code }}" value="{{ $warehouse->id }}">
                                                                {{ $warehouse->name.'/'.$warehouse->code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Invoice ID') :</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="If you keep this field empty, The invoice ID will be generated automatically." class="fas fa-info-circle tp"></i></label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="@lang('Invoice ID')" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('Attachment') : <i data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice related any file.Ex: Scanned cheque, payment prove file etc. Max Attachment Size 2MB." class="fas fa-info-circle tp"></i></b></label>
                                                <div class="col-8">
                                                    <input type="file" name="attachment" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label class="col-4"> <b>@lang('Status') : <span
                                                    class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <select name="status" class="form-control add_input" data-name="Status"
                                                        id="status">
                                                        <option value="">@lang('Select status')</option>
                                                        @foreach (App\Utils\SaleUtil::saleStatus() as $key => $status)
                                                            <option value="{{ $key }}">{{ $status }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_status"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class=" col-4"><b>@lang('Date') : <span
                                                    class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control add_input" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" autocomplete="off" id="date">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label class="col-6"><b>@lang('Inv'). Schema :</b></label>
                                                <div class="col-6">
                                                    <select name="invoice_schema" class="form-control"
                                                        id="invoice_schema">
                                                        <option value="">@lang('None')</option>
                                                        @foreach ($invoice_schemas as $inv_schema)
                                                            <option value="{{$inv_schema->format == 2 ? date('Y') . '/' . $inv_schema->start_from : $inv_schema->prefix . $inv_schema->start_from }}">
                                                                {{$inv_schema->format == 2 ? date('Y') . '/' . $inv_schema->start_from : $inv_schema->prefix . $inv_schema->start_from }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-6"><b>@lang('Previous Due') :</b></label>
                                                <div class="col-6">
                                                    <input readonly type="number" step="any" class="form-control text-danger" id="display_pre_due" value="0.00" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label class="col-5"><b>@lang('Price Group') :</b></label>
                                                <div class="col-7">
                                                    <select name="price_group_id" class="form-control"
                                                        id="price_group_id">
                                                        <option value="">@lang('Default Selling Price')</option>
                                                        @foreach ($price_groups as $pg)
                                                            <option {{ json_decode($generalSettings->sale, true)['default_price_group_id'] == $pg->id ? 'SELECTED' : '' }} value="{{ $pg->id }}">{{ $pg->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-5"><b>@lang('Sales A/C') : <span
                                                    class="text-danger">*</span></b></label>
                                                <div class="col-7">
                                                    <select name="sale_account_id" class="form-control add_input"
                                                        id="sale_account_id" data-name="Sale A/C">
                                                        @foreach ($saleAccounts as $saleAccount)
                                                            <option value="{{ $saleAccount->id }}">
                                                                {{ $saleAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_sale_account_id"></span>
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
                            <div class="col-md-9">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('Item Search')</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-barcode text-dark input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control scanable" id="search_product" placeholder="@lang('Search Product by product code(SKU) / Scan bar code')" autocomplete="off" autofocus>
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

                                            <div class="col-md-3">
                                                <label class="col-form-label"></label>
                                                <div class="input-group ">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text add_button p-1 m-0">@lang('Stock')</span>
                                                    </div>
                                                    <input type="text" readonly class="form-control text-success stock_quantity"
                                                        autocomplete="off" id="stock_quantity"
                                                        placeholder="@lang('Stock Quantity')" tabindex="-1">
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
                                                                    <th class="text-start">@lang('Stock Location')</th>
                                                                    <th class="text-center">@lang('Quantity')</th>
                                                                    <th>@lang('Unit')</th>
                                                                    <th class="text-center">@lang('Price Inc').Tax</th>
                                                                    <th>@lang('SubTotal')</th>
                                                                    <th><i class="fas fa-minus text-dark"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="sale_list"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="item-details-sec mt-2">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('Ship Details') :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_details" type="text" class="form-control" id="shipment_details" placeholder="@lang('Shipment Details')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('Ship Address') :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_address" type="text" class="form-control" id="shipment_address" placeholder="@lang('Shipment Address')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('Ship Status') :</b></label>
                                                    <div class="col-8">
                                                        <select name="shipment_status" class="form-control" id="shipment_status">
                                                            <option value="">@lang('Shipment Status')</option>
                                                            <option value="1">@lang('Ordered')</option>
                                                            <option value="2">@lang('Packed')</option>
                                                            <option value="3">@lang('Shipped')</option>
                                                            <option value="4">@lang('Delivered')</option>
                                                            <option value="5">@lang('Cancelled')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('Delivered To') :</b></label>
                                                    <div class="col-8">
                                                        <input name="delivered_to" type="text" class="form-control" id="delivered_to" placeholder="@lang('Delivered To')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('Sale Note'):</b></label>
                                                    <div class="col-8">
                                                        <input name="sale_note" type="text" class="form-control" id="sale_note" placeholder="@lang('Sale note')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('Payment Note') :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="payment_note" class="form-control" id="payment_note" placeholder="@lang('Payment note')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="item-details-sec mt-3">
                                    <div class="content-inner">
                                        <div class="row no-gutters">
                                            <ul class="list-unstyled add_sale_ex_btn">
                                                {{-- <li><button value="save_and_print" class="btn btn-sm btn-info text-white submit_button" data-status="4">@lang('Quotation')</button></li>--}}
                                                <li><button value="save_and_print" class="btn btn-sm btn-primary text-white submit_button" data-status="2">@lang('Draft')</button></li>
                                                <li><button type="button" class="btn btn-sm btn-secondary text-white resent-tn">@lang('Recent Transection')</button></li>
                                                <li><button type="button" class="btn btn-sm btn-success text-white show_stock">@lang('Show Stock')</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="item-details-sec mb-3 number-fields">
                                    <div class="content-inner">
                                        <div class="row">
                                            <label class="col-sm-5 col-form-label">@lang('Total Item') :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" step="any" name="total_item" id="total_item" class="form-control" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-5 col-form-label">@lang('Net Total') :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" step="any" class="form-control" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-5 col-form-label">@lang('Discount'):</label>
                                            <div class="col-sm-3">
                                                <select name="order_discount_type" class="form-control" id="order_discount_type">
                                                    <option value="1">@lang('Fixed')</option>
                                                    <option value="2">@lang('Percentage')</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <input name="order_discount" type="number" step="any" class="form-control" id="order_discount" value="0.00">
                                                <input name="order_discount_amount" step="any" type="number" class="d-none" id="order_discount_amount" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-5 col-form-label">@lang('Order Tax') :</label>
                                            <div class="col-sm-7">
                                                <select name="order_tax" class="form-control" id="order_tax"></select>
                                                <input type="number" step="any" class="d-none" name="order_tax_amount" id="order_tax_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-5 col-form-label">@lang('Shipment Cost'):</label>
                                            <div class="col-sm-7">
                                                <input name="shipment_charge" type="number" step="any" class="form-control" id="shipment_charge" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-5 col-form-label">@lang('Previous Due') :</label>
                                            <div class="col-sm-7">
                                                <input readonly class="form-control text-danger" type="number" step="any" name="previous_due" id="previous_due" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-5 col-form-label">@lang('Total Payable'):</label>
                                            <div class="col-sm-7">
                                                <input readonly class="form-control" type="number" step="any" name="total_payable_amount" id="total_payable_amount" value="0.00" tabindex="-1">
                                                <input class="d-none" type="number" step="any" name="total_invoice_payable" id="total_invoice_payable" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="payment_body">
                                            <div class="row">
                                                <label class="col-sm-5 col-form-label">@lang('Cash Receive'): >></label>
                                                <div class="col-sm-7">
                                                    <input type="number" step="any" name="paying_amount" class="form-control" id="paying_amount" value="0.00" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label class="col-sm-5 col-form-label">@lang('Change') :</label>
                                                <div class="col-sm-7">
                                                    <input readonly type="number" step="any" name="change_amount" class="form-control" id="change_amount" value="0.00" tabindex="-1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label class="col-sm-5 col-form-label">@lang('Paid By') :</label>
                                                <div class="col-sm-7">
                                                    <select name="payment_method_id" class="form-control" id="payment_method_id">
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

                                            <div class="row">
                                                <label class="col-sm-5 col-form-label">@lang('Debit A/C') : <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <select name="account_id" class="form-control" id="account_id" data-name="Debit A/C">
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}">
                                                                @php
                                                                    $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                                                    $bank = $account->bank ? ', BK : '.$account->bank : '';
                                                                    $ac_no = $account->account_number ? ', A/c No : '.$account->account_number : '';
                                                                    $balance = ', BL : '.$account->balance;
                                                                @endphp
                                                                {{ $account->name.$accountType.$bank.$ac_no.$balance }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_account_id"></span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label class="col-sm-5 col-form-label">@lang('Due') :</label>
                                                <div class="col-sm-7">
                                                    <input readonly type="number" step="any" class="form-control text-danger" name="total_due" id="total_due" value="0.00" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="submitBtn">
                                            <div class="row justify-content-center">
                                                <div class="col-12 text-end">
                                                    <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-danger"></i> </button>
                                                    <button type="submit" id="quotation" class="btn btn-sm btn-info text-white submit_button" data-status="4" value="save_and_print">@lang('Quotation')</button>
                                                    <button type="submit" id="order" class="btn btn-sm btn-secondary text-white submit_button" data-status="3" value="save_and_print">@lang('Order')</button>
                                                    <button type="submit" id="save_and_print" class="btn btn-sm btn-success submit_button" data-status="1" value="save_and_print">@lang('Final & Print')</button>
                                                    <button type="submit" id="save" class="btn btn-sm btn-success submit_button" data-status="1" value="save">@lang('Final')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <!--Add Customer Modal-->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Customer')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_customer_modal_body"></div>
            </div>
        </div>
    </div>
    <!--Add Customer Modal-->

    <!--Add Customer Opening Balance Modal-->
    <div class="modal fade" id="addCustomerOpeingBalanceModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
    aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Customer Opening Balance')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <form id="add_customer_opening_balance" action="{{ route('contacts.customer.add.opening.balance') }}" method="POST">
                        @csrf
                        <input type="hidden" id="op_branch_id" name="branch_id">
                        <input type="hidden" id="op_customer_id" name="customer_id">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <p><strong>@lang('Customer') : </strong> <span class="op_customer_name"></span></p>
                                <p><strong>@lang('Phone No'). : </strong> <span class="op_customer_phone"></span></p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>@lang('Business Location') : </strong> <span class="op_branch_name"></span></p>
                            </div>

                            <div class="col-md-12 mt-2">
                                <label><b>@lang('Opening Balance') :</b> </label>
                                <input type="number" step="any" name="opening_balance" class="form-control" placeholder="@lang('Opening Balance')">
                            </div>

                            <div class="col-12 mt-2">
                                <div class="row">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="never_show_again" id="never_show_again" class="is_show_again">&nbsp;<b>@lang('Never Show Again').</b>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn op_loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                <button name="action" value="save" type="submit" class="c-btn button-success float-end">@lang('Save')</button>
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Add Customer Opening Balance Modal End-->

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
                    <form id="update_selling_product" action="">
                        @if (auth()->user()->permission->sale['view_product_cost_is_sale_screed'] == '1')
                            <p>
                                <span class="btn btn-sm btn-primary d-none" id="show_cost_section">
                                    <span>{{ json_decode($generalSettings->business, true)['currency'] }}</span>
                                    <span id="unit_cost">1,200.00</span>
                                </span>
                                <span class="btn btn-sm btn-info text-white" id="show_cost_button">@lang('Cost')</span>
                            </p>
                        @endif

                        <div class="form-group">
                            <label> <strong>@lang('Quantity')</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" readonly class="form-control edit_input" data-name="Quantity" id="e_quantity" placeholder="@lang('Quantity')" tabindex="-1"/>
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>@lang('Unit Price Exc').Tax</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" {{ auth()->user()->permission->sale['edit_price_sale_screen'] == '1' ? '' : 'readonly' }} step="any" class="form-control edit_input" data-name="Unit price" id="e_unit_price" placeholder="@lang('Unit price')" />
                            <span class="error error_e_unit_price"></span>
                        </div>

                        @if (auth()->user()->permission->sale['edit_discount_sale_screen'] == '1')
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
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>@lang('Tax')</strong> :</label>
                                <select class="form-control" id="e_unit_tax"></select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('Tax Type')</strong> :</label>
                                <select class="form-control" id="e_tax_type">
                                    <option value="1">@lang('Exclusive')</option>
                                    <option value="2">@lang('Inclusive')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>@lang('Sale Unit')</strong> :</label>
                            <select class="form-control" id="e_unit"></select>
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

    <!--Add Product Modal-->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Product')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_product_body"></div>
            </div>
        </div>
    </div>
    <!--Add Product Modal End-->

    <!-- Recent transection list modal-->
    <div class="modal fade" id="recentTransModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Recent Transections')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="tab_list_area">
                        <ul class="list-unstyled">
                            <li>
                                <a id="tab_btn" class="tab_btn tab_active text-white" href="{{url('common/ajax/call/recent/sales/1')}}"><i class="fas fa-info-circle"></i> @lang('Final')</a>
                            </li>

                            <li>
                                <a id="tab_btn" class="tab_btn text-white" href="{{url('common/ajax/call/recent/quotations/1')}}"><i class="fas fa-scroll"></i>@lang('Quotation')</a>
                            </li>

                            <li>
                                <a id="tab_btn" class="tab_btn text-white" href="{{url('common/ajax/call/recent/drafts/1')}}"><i class="fas fa-shopping-bag"></i> @lang('Draft')</a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab_contant">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="data_preloader" id="recent_trans_preloader">
                                        <h6><i class="fas fa-spinner"></i> @lang('Processing')...</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table modal-table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">SL</th>
                                                    <th class="text-start">@lang('Invoice ID')</th>
                                                    <th class="text-start">@lang('Customer')</th>
                                                    <th class="text-start">@lang('Total')</th>
                                                    <th class="text-start">@lang('Actions')</th>
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
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end me-0">@lang('Close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Show stock modal-->
    <div class="modal fade" id="showStockModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content">
                <div class="data_preloader mt-5" id="stock_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                </div>
                <div class="modal-header">
                    <h6 class="modal-title">@lang('Item Stocks')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="stock_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Show stock modal end-->
@endsection
@push('scripts')
    @include('sales.partials.addSaleCreateJsScript')
@endpush


