@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 94%;z-index: 9999999;padding: 0;left: 3%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 13px;padding: 4px 3px;display: block;}
        .select_area ul li a:hover {background-color: #999396;color: #fff;}
        .selectProduct{background-color: #746e70; color: #fff!important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="edit_purchase_form" action="{{ route('purchases.update', $editType) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $purchaseId }}">
                <input type="hidden" name="paid" id="paid" value="">
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h6>@lang('Edit Purchase')</h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('Supplier') :</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" id="supplier_name" class="form-control">
                                                </div>
                                            </div>

                                            @if ($purchase->warehouse_id)
                                                <input name="warehouse_count" value="YES" type="hidden"/>
                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('Warehouse') :</b><span
                                                        class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select class="form-control changeable add_input"
                                                            name="warehouse_id" data-name="Warehouse" id="warehouse_id">
                                                            <option value="">@lang('Select Warehouse')</option>
                                                            @foreach ($warehouses as $warehouse)
                                                                <option {{ $purchase->warehouse_id == $warehouse->id ? 'SELECTED' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name.'/'.$warehouse->warehouse_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('B.Location'):</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Invoice ID') :</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control">
                                                </div>
                                            </div>

                                            @if ($purchase->purchase_status == 3)
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('Status') :</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" value="Ordered" class="form-control">
                                                        <input type="hidden" name="purchase_status" id="purchase_status" value="3">
                                                    </div>
                                                </div>
                                            @else
                                                @if (json_decode($generalSettings->purchase, true)['is_enable_status'] == '1')
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('Status') :</b></label>
                                                        <div class="col-8">
                                                            <select class="form-control changeable" name="purchase_status" id="purchase_status">
                                                                <option value="1">@lang('Purchased')</option>
                                                                {{-- <option value="2">@lang('Pending')</option> --}}
                                                                <option value="3">@lang('Ordered')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                @else
                                                    <input type="hidden" name="purchase_status" id="purchase_status" value="1">
                                                @endif
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Date') :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control changeable"
                                                         id="date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) }}">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('Pay Term') :</b> </label>
                                                <div class="col-8">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <input type="text" name="pay_term_number" class="form-control"
                                                            id="pay_term_number" placeholder="@lang('Number')">
                                                        </div>

                                                        <div class="col-7">
                                                            <select name="pay_term" class="form-control changeable"
                                                            id="pay_term">
                                                                <option value="">@lang('Pay Term')</option>
                                                                <option value="1">@lang('Days')</option>
                                                                <option value="2">@lang('Months')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Delivery Date') :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="delivery_date" class="form-control changeable" id="delivery_date" placeholder="@lang('DD-MM-YYYY')" autocomplete="off" value="{{ $purchase->delivery_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->delivery_date)) : '' }}">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('Purchase A/C') : <span
                                                    class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <select name="purchase_account_id" class="form-control add_input"
                                                        id="purchase_account_id" data-name="Purchase A/C">
                                                        @foreach ($purchaseAccounts as $purchaseAccount)
                                                            <option value="{{ $purchaseAccount->id }}">
                                                                {{ $purchaseAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_purchase_account_id"></span>
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
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('Item Search')</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode text-dark"></i></span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" placeholder="@lang('Search Product by product code(SKU) / Scan bar code')">
                                                        @if (auth()->user()->permission->product['product_add'] == '1')
                                                            <div class="input-group-prepend">
                                                                <span id="add_product" class="input-group-text add_button"><i class="fas fa-plus-square text-dark"></i></span>
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
                                                                    <th>@lang('Quantity')</th>
                                                                    <th>@lang('Unit Cost(BD)') <i data-bs-toggle="tooltip" data-bs-placement="right" title="Before Discount" class="fas fa-info-circle tp"></i></th>
                                                                    <th>@lang('Discount')</th>
                                                                    <th>@lang('Unit Cost(BT)') <i data-bs-toggle="tooltip" data-bs-placement="right" title="Before Tax" class="fas fa-info-circle tp"></i></th>
                                                                    <th>@lang('SubTotal (BT)') <i data-bs-toggle="tooltip" data-bs-placement="right" title="Before Tax" class="fas fa-info-circle tp"></i></th>
                                                                    <th>@lang('Unit Tax')</th>
                                                                    <th>@lang('Net Unit Cost')</th>
                                                                    <th>@lang('Line Total')</th>
                                                                    @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                        <th>@lang('xMargin')(%)</th>
                                                                        <th>@lang('Selling Price Exc').Tax</th>
                                                                    @endif
                                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="purchase_list"></tbody>
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
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('Discount') :</b></label>
                                                <div class="col-8">
                                                    <div class="row">
                                                        <select name="order_discount_type" class="form-control w-25" id="order_discount_type">
                                                            <option value="1">@lang('Fixed')</option>
                                                            <option value="2">@lang('Percentage')</option>
                                                        </select>

                                                        <input name="order_discount" type="number" class="form-control w-75" id="order_discount" value="0.00">
                                                    </div>
                                                    <input name="order_discount_amount" type="number" step="any" class="d-none" id="order_discount_amount" value="0.00">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('Tax') :</b></label>
                                                <div class="col-8">
                                                    <select name="purchase_tax" class="form-control" id="purchase_tax">
                                                        <option value="0.00">@lang('NoTax')</option>
                                                    </select>
                                                    <input name="purchase_tax_amount" type="number" step="any" class="d-none" id="purchase_tax_amount" value="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Ship Cost') :</b> </label>
                                                <div class="col-8">
                                                    <input name="shipment_charge" type="number" class="form-control" id="shipment_charge" value="0.00">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('Ship Details') :</b></label>
                                                <div class="col-8">
                                                    <input name="shipment_details" type="text" class="form-control" id="shipment_details" placeholder="@lang('Shipment Details')">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <input readonly name="total_qty" type="number" step="any" class="d-none" id="total_qty" value="0.00">
                                            <div class="input-group">
                                                <label class="col-4">@lang('Total Item'):</label>
                                                <div class="col-8">
                                                    <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="0.00">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('Order Note') :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="purchase_note" id="purchase_note" class="form-control" value="" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Net Total') :</b>  {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control" value="0.00" >
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('payable') :</b>{{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="total_purchase_amount" id="total_purchase_amount" class="form-control" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button id="save" class="btn btn-sm btn-success submit_button float-end">@lang('Save Changes')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--Add Product Modal-->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Product')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_product_body">

                </div>
            </div>
        </div>
    </div>
    <!--Add Product Modal End-->

    <!--Add Product Modal-->
    <div class="modal fade" id="addDescriptionModal" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl description_modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Description') <span id="product_name"></span></h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label><strong>@lang('Description') :</strong></label>
                            <textarea name="product_description" id="product_description" class="form-control" cols="30" rows="10" placeholder="@lang('Description')"></textarea>
                        </div>
                    </div>

                    <div class="form-group text-end mt-3">
                        <button type="submit" id="add_description" class="c-btn button-success float-end me-0">@lang('Add')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Add Product Modal End-->
@endsection
@push('scripts')
    @include('purchases.partials.purchaseEditJsScript')
@endpush
