@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        .dataTables_filter {width: 50%!important;}
        .dataTables_filter input {width: 50%;}
    </style>
    <link href="/backend/asset/css/jquery.cleditor.css" rel="stylesheet" type="text/css">
    <link href="/backend/asset/css/select2.min.css" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_product_form" action="{{ route('products.add.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="col-md-12">
                                    <div class="form_element m-0 mt-4">
                                        <div class="py-2 px-2 form-header">
                                            <div class="row">
                                                <div class="col-8"><h6>@lang('Add Product')</h6></div>
                                            </div>
                                        </div>

                                        <div class="element-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('Product Name') :</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="text" name="name" class="form-control" id="name" placeholder="@lang('Product Name')" autofocus>
                                                            <span class="error error_name"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('Product Code')
                                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Also known as SKU. Product code(SKU) must be unique. If you leave this field empty, it will be generated automatically." class="fas fa-info-circle tp"></i> :</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" name="code" class="form-control scanable" autocomplete="off" id="code" value="" placeholder="@lang('Product Code')">
                                                            <input type="hidden" name="auto_generated_code" id="auto_generated_code">
                                                        </div>
                                                    </div>
                                                    <span class="error error_code"></span>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('Unit') :</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <select class="form-control product_unit" name="unit_id" id="unit_id">
                                                                    <option value="">@lang('Select Unit')</option>
                                                                    @php
                                                                        $defaultUnit = json_decode($generalSettings->product, true)['default_unit_id'];
                                                                    @endphp
                                                                    @foreach ($units as $unit)
                                                                        <option {{ $defaultUnit ==  $unit->id ? 'SELECTED' : '' }} value="{{ $unit->id }}">{{ $unit->name.' ('.$unit->code_name.')' }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" data-bs-toggle="modal"
                                                                        data-bs-target="#addUnitModal"><i class="fas fa-plus-square input_i"></i></span>
                                                                </div>
                                                            </div>
                                                            <span class="error error_unit_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"> <b>@lang('Barcode Type')  :</b> </label>
                                                        <div class="col-8">
                                                            <select class="form-control" name="barcode_type" id="barcode_type">
                                                                <option value="">@lang('Select Barcode Type')</option>
                                                                <option value="CODE128">@lang('Code 128 (C128)')</option>
                                                                <option value="CODE39">@lang('Code 39 (C39)')</option>
                                                                <option value="EAN13">@lang('EAN')-13</option>
                                                                <option value="UPC">@lang('UPC')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <span class="error error_barcode_type"></span>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1')
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('Category') :</b> </label>
                                                            <div class="col-8">
                                                                <div class="input-group">
                                                                    <select class="form-control category" name="category_id"
                                                                        id="category_id">
                                                                        <option value="">@lang('Select Category')</option>
                                                                        @foreach ($categories as $category)
                                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text add_button" data-bs-toggle="modal"
                                                                            data-bs-target="#addCategoryModal"><i class="fas fa-plus-square input_i"></i></span>
                                                                    </div>
                                                                </div>
                                                                <span class="error error_category_id"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1' && json_decode($generalSettings->product, true)['is_enable_sub_categories'] == '1')
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"> <b>@lang('Sub-category') :</b> </label>
                                                            <div class="col-8">
                                                                <div class="input-group">
                                                                <select class="form-control" name="child_category_id"
                                                                    id="child_category_id">
                                                                    <option value="">@lang('Select Category First')</option>
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" data-bs-toggle="modal"
                                                                        data-bs-target="#addSubCategoryModal"><i class="fas fa-plus-square input_i"></i></span>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="row mt-1">
                                                @if (json_decode($generalSettings->product, true)['is_enable_brands'] == '1')
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4">
                                                                <b> @lang('Brand') </b>
                                                            </label>
                                                            <div class="col-8">
                                                                <div class="input-group">
                                                                    <select class="form-control" name="brand_id" id="brand_id">
                                                                        <option value="">@lang('Select Brand')</option>
                                                                        @foreach ($brands as $brand)
                                                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text add_button" data-bs-toggle="modal" data-bs-target="#addBrandModal"><i class="fas fa-plus-square input_i"></i></span>
                                                                    </div>
                                                                </div>
                                                                <span class="error error_brand_id"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"> <b>@lang('Alert quantity')  :</b> </label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="alert_quantity" class="form-control " autocomplete="off" id="alert_quantity" value="0">
                                                            <span class="error error_alert_quantity"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                @if (json_decode($generalSettings->product, true)['is_enable_warranty'] == '1')
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('Warranty') :</b> </label>
                                                            <div class="col-8">
                                                                <div class="input-group">
                                                                    <select class="form-control" name="warranty_id" id="warranty_id">
                                                                        <option value="">@lang('Select Warranty')</option>
                                                                        @foreach ($warranties as $warranty)
                                                                            <option value="{{ $warranty->id }}">{{ $warranty->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text add_button" data-bs-toggle="modal"
                                                                            data-bs-target="#addWarrantyModal"><i class="fas fa-plus-square input_i"></i><span>
                                                                    </div>
                                                                </div>
                                                                <span class="error error_warranty_id"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-6">
                                                            <div class="input-group">
                                                                <label class="col-4"><b>@lang('Business Location') :</b> </label>
                                                                <div class="col-8">
                                                                    <input type="hidden" name="branch_count" value="branch_count">
                                                                    <select class="form-control select2" name="branch_ids[]" id="branch_ids" multiple>
                                                                        <option value="">
                                                                            {{ json_decode($generalSettings->business, true)['shop_name'] . '(HO)' }}
                                                                        </option>
                                                                        @foreach ($branches as $branch)
                                                                            <option value="{{ $branch->id }}">
                                                                                {{ $branch->name.'/'.$branch->branch_code }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span class="error error_branch_ids"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"> <b>@lang('Condition')  :</b> </label>
                                                        <div class="col-8">
                                                            <select class="form-control" name="product_condition"
                                                                id="product_condition">
                                                                <option value="">@lang('Select Condition')</option>
                                                                <option value="New">@lang('New')</option>
                                                                <option value="Used">@lang('Used')</option>
                                                            </select>
                                                            <span class="error error_product_condition"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="input-group mt-1">
                                                                <div class="col-12">
                                                                    <div class="row">
                                                                        <p class="checkbox_input_wrap">
                                                                        <input checked type="checkbox" name="is_manage_stock" id="is_manage_stock"> &nbsp; <b>@lang('Manage Stock')</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Stock Management should be disable mostly for services/Digital Products. Example: Hair-Cutting, Repairing, PDF Books etc." class="fas fa-info-circle tp"></i></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="input-group mt-1">
                                                                <div class="col-12">
                                                                    <div class="row">
                                                                        <p class="checkbox_input_wrap">
                                                                        <input type="checkbox" name="digital_product" id="digital_product"> &nbsp; <b> @lang('Service/Degital Product')</b> </p>
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

                                <div class="col-md-12">
                                    <div class="form_element m-0 mt-2">
                                        <div class="element-body" style="margin-top:0 !important;">
                                            <div class="form_part">
                                                <div class="row mt-1">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('Unit Cost') :</b> <span class="text-danger">*</span></label>
                                                            <div class="col-8">
                                                                <input type="number" step="any" name="product_cost" class="form-control"
                                                                autocomplete="off" id="product_cost" placeholder="@lang('Unit cost')" value="0.00">
                                                                <span class="error error_product_cost"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('Price Exc. Tax') :</b> <span class="text-danger">*</span></label>
                                                            <div class="col-8">
                                                                <input type="number" step="any" name="product_price" class="form-control" autocomplete="off" id="product_price" placeholder="@lang('Selling Price Exc.Tax')">
                                                                <span class="error error_product_price"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                @if (json_decode($generalSettings->product, true)['is_enable_price_tax'] == '1')
                                                <div class="row mt-1">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('Tax') :</b> </label>
                                                            <div class="col-8">
                                                                <select class="form-control" name="tax_id" id="tax_id">
                                                                    <option value="">@lang('NoTax')</option>
                                                                    @foreach ($taxes as $tax)
                                                                    <option value="{{ $tax->id.'-'.$tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error error_tax_id"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('Tax Type') :</b> </label>
                                                            <div class="col-8">
                                                                <select name="tax_type" class="form-control" id="tax_type">
                                                                    <option value="">@lang('Select tax type')</option>
                                                                    <option value="1">@lang('Exclusive')</option>
                                                                    <option value="2">@lang('Inclusive')</option>
                                                                </select>
                                                                <span class="error error_tax_type"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="row mt-1">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('Unit Cost (Inc. Tax)') :</b> <span class="text-danger">*</span></label>
                                                            <div class="col-8">
                                                                <input type="number" step="any" readonly name="product_cost_with_tax" class="form-control" autocomplete="off" id="product_cost_with_tax" placeholder="@lang('Unit cost Inc.Tax')" value="0.00">
                                                                <span class="error error_product_cost_with_tax"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('Profit Margin')(%) :</b> <span class="text-danger">*</span></label>
                                                            <div class="col-8">
                                                                <input type="number" step="any" name="profit" class="form-control" autocomplete="off" id="profit" value="{{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }}">
                                                                <span class="error error_profit"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-1">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('Thumbnail Photo') :</b> </label>
                                                            <div class="col-8">
                                                                <input type="file" name="photo" class="form-control" id="photo">
                                                                <span class="error error_photo"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    <p class="checkbox_input_wrap">
                                                                    <input type="checkbox" name="is_variant" id="is_variant"> &nbsp; <b>@lang('This product has varient').</b> </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-1">
                                                    <div class="dynamic_variant_create_area d-none">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="add_more_btn">
                                                                    <a id="add_more_variant_btn" class="btn btn-sm btn-primary float-end" href="">@lang('Add More')</a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="table-responsive mt-1">
                                                                    <table class="table modal-table table-sm">
                                                                        <thead>
                                                                            <tr class="text-center bg-primary variant_header">
                                                                                <th class="text-white text-start">@lang('Select Variant')</th>
                                                                                <th class="text-white text-start">@lang('Varient code') <i data-bs-toggle="tooltip" data-bs-placement="top" title="Also known as SKU. Variant code(SKU) must be unique." class="fas fa-info-circle tp"></i>
                                                                                </th>
                                                                                <th colspan="2" class="text-white text-start">@lang('Default Cost')</th>
                                                                                <th class="text-white text-start">@lang('Profit')(%)</th>
                                                                                <th class="text-white text-start">@lang('Default Price (Exc. Tax)')</th>
                                                                                <th class="text-white text-start">@lang('Variant Image')</th>
                                                                                <th><i class="fas fa-trash-alt text-white"></i></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="dynamic_variant_body">
                                                                            <tr>
                                                                                <td class="text-start">
                                                                                    <select class="form-control form-control" name=""
                                                                                        id="variants"></select>
                                                                                    <input type="text" name="variant_combinations[]"
                                                                                        id="variant_combination" class="form-control reqireable"
                                                                                        placeholder="@lang('Variant Combination')">
                                                                                </td>

                                                                                <td class="text-start">
                                                                                    <input type="text" name="variant_codes[]" id="variant_code" class="form-control reqireable"
                                                                                        placeholder="@lang('Variant Code')">
                                                                                </td>

                                                                                <td class="text-start">
                                                                                    <input type="number" name="variant_costings[]" step="any" class="form-control requireable" placeholder="@lang('Cost')" id="variant_costing">
                                                                                </td>

                                                                                <td class="text-start">
                                                                                    <input type="number" step="any" name="variant_costings_with_tax[]"class="form-control requireable" placeholder="@lang('Cost inc.tax')" id="variant_costing_with_tax"

                                                                                    >
                                                                                </td>

                                                                                <td class="text-start">
                                                                                    <input type="number" step="any" name="variant_profits[]" class="form-control requireable" placeholder="@lang('Profit')" value="0.00" id="variant_profit">
                                                                                </td>

                                                                                <td class="text-start">
                                                                                    <input type="number" step="any" name="variant_prices_exc_tax[]" class="form-control requireable" placeholder="@lang('Price inc.tax')" id="variant_price_exc_tax">
                                                                                </td>

                                                                                <td class="text-start">
                                                                                    <input type="file" name="variant_image[]" class="form-control" id="variant_image">
                                                                                </td>

                                                                                <td class="text-start">
                                                                                    <a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
                                                                                </td>
                                                                            </tr>
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

                                <div class="col-md-12">
                                    <div class="form_element m-0 mt-2">
                                        <div class="element-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('Type') :</b> </label>
                                                        <div class="col-8">
                                                            <select name="type" class="form-control" id="type">
                                                                <option value="1">@lang('General')</option>
                                                                <option value="2">@lang('Combo')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"> <b>@lang('Weight') :</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" name="weight" class="form-control" id="weight" placeholder="@lang('Weight')">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('Custom Field')1 :</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" name="custom_field_1" class="form-control" placeholder="@lang('Custom field1')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('Custom Field')2 :</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" name="custom_field_2" class="form-control" placeholder="@lang('Custom field2')">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('Custom Field')3 :</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" name="custom_field_3" class="form-control" placeholder="@lang('Custom field3')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <div class="col-12">
                                                            <div class="row">
                                                                <p class="checkbox_input_wrap">
                                                                <input type="checkbox" name="is_show_in_ecom"> &nbsp; <b>@lang('Product wil be displayed in E-Commerce').</b></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <div class="col-12">
                                                            <div class="row">
                                                                <p class="checkbox_input_wrap">
                                                                <input type="checkbox" name="is_show_emi_on_pos"> &nbsp; <b>@lang('Enable Product IMEI or Serial Number')</b> </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <div class="col-12">
                                                            <div class="row">
                                                                <p class="checkbox_input_wrap">
                                                                <input type="checkbox" name="is_not_for_sale"> &nbsp; <b>@lang('Show Not For Sale')</b> </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form_element m-0 mt-1">
                                        <div class="element-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-2"> <b>@lang('Description') :</b> </label>
                                                        <div class="col-10" style="margin-top:-300px !important;">
                                                            <textarea name="product_details" id="myEditor" class="myEditor form-control" cols="50" rows="5" tabindex="4" style="display: none; width: 653px; height: 160px;"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-2"> <b>@lang('Photos') <i data-bs-toggle="tooltip" data-bs-placement="top" title="This photo will be shown in e-commerce. You can upload multiple file. Per photo max size 2MB." class="fas fa-info-circle tp"></i> :</b> </label>
                                                        <div class="col-10">
                                                            <input type="file" name="image[]" class="form-control" id="image" accept="image" multiple>
                                                            <span class="error error_image"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 text-end mt-1">
                                    <button type="button" class="btn loading_button btn-sm d-none"><i class="fas fa-spinner text-primary"></i> <strong>@lang('Loading')</strong> </button>
                                    <button type="submit" name="action" value="save_and_new"
                                        class="btn btn-success submit_button btn-sm" id="save_and_new">@lang('Save And Add Another')</button>
                                    <button type="submit" name="action" value="save"
                                        class="btn btn-success submit_button btn-sm" id="save">@lang('Save')</button>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card mt-4">
                                    <div class="section-header">
                                        <div class="col-md-6">
                                            <h6>@lang('All Product')</h6>
                                        </div>
                                    </div>

                                    <div class="widget_content">
                                        <div class="table-responsive" id="data_list">
                                            <table class="display table-hover data_tbl data__table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('Product')</th>
                                                        <th>@lang('Unit Cost')</th>
                                                        <th>@lang('Unit Price')</th>
                                                        <th>@lang('Actions')</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
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
    @include('product.products.partials.all-modals')
@endsection
@push('scripts')
<script src="/backend/asset/js/jquery.cleditor.js"></script>
<script src="/backend/asset/js/select2.min.js"></script>
<script>
 
    $('.select2').select2();

    var table = $('.data_tbl').DataTable({
        processing: true,
        serverSide: true,
        searchable: true,
        language: {
                search: "@lang('Search')",
                emptyTable: "@lang('EmptyTable')",
                infoEmpty: "@lang('EmptyTable')",
                sInfo: "@lang('Showing _START_ to _END_ of _TOTAL_ entries')",
                sInfoEmpty: "@lang('Showing 0 to 0 of 0 entries')",
                sLengthMenu: "@lang('Show _MENU_ entries')",
                paginate: {
                    next: "@lang('Next')",
                    previous: "@lang('Previous')"

                },
            },
        aSorting: [[0, 'desc']],
        "lengthMenu" : [25, 100, 500, 1000, 2000],
        ajax: "{{ route('products.add.view') }}",
        columns: [
            {data: 'name',name: 'products.name'},
            {data: 'product_cost',name: 'products.product_cost'},
            {data: 'product_price',name: 'products.product_price'},
            {data: 'action',name: 'action'},
        ],
    });

    var myEditorObj = $('#myEditor').cleditor();
    function clearEditor() {
        $("#myEditor").cleditor({width:800, height:300, updateTextArea:function (){}})[0].clear();
    }

    // Set parent category in parent category form field
    $('.combo_price').hide();
    $('.combo_pro_table_field').hide();

    var tax_percent = 0;
    $('#tax_id').on('change', function() {
        var tax = $(this).val();
        if (tax) {
            var split = tax.split('-');
            tax_percent = split[1];
        }else{
            tax_percent = 0;
        }
    });

    function costCalculate() {

        var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
        var tax_type = $('#tax_type').val();
        var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent);

        if (tax_type == 2) {

            var __tax_percent = 100 + parseFloat(tax_percent);
            var calc_tax = parseFloat(product_cost) / parseFloat(__tax_percent) * 100;
            calc_product_cost_tax = parseFloat(product_cost) - parseFloat(calc_tax);
        }

        var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
        $('#product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        var profit = $('#profit').val() ? $('#profit').val() : 0;

        if (parseFloat(profit) > 0) {

            var calculate_profit = parseFloat(product_cost_with_tax) / 100 * parseFloat(profit);
            var product_price = parseFloat(product_cost_with_tax) + parseFloat(calculate_profit);
            $('#product_price').val(parseFloat(product_price).toFixed(2));
        }

        // calc package product profit
        var netTotalComboPrice = $('#total_combo_price').val() ? $('#total_combo_price').val() : 0;
        var calcTotalComboPrice = parseFloat(netTotalComboPrice) / 100 * parseFloat(profit) + parseFloat(netTotalComboPrice);
        $('#combo_price').val(parseFloat(calcTotalComboPrice).toFixed(2));
    }

    $(document).on('input', '#product_cost',function() {

        costCalculate();
    });

    $(document).on('input', '#product_price',function() {

        var selling_price = $(this).val() ? $(this).val() : 0;
        var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
        var profitAmount = parseFloat(selling_price) - parseFloat(product_cost);
        var __cost = parseFloat(product_cost) > 0 ? parseFloat(product_cost) : parseFloat(profitAmount);
        var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __calcProfit = calcProfit ? calcProfit : 0;
        $('#profit').val(parseFloat(__calcProfit).toFixed(2));
    });

    $('#tax_id').on('change', function() {
        costCalculate();
    });

    $('#tax_type').on('change', function() {
        costCalculate();
    });

    $(document).on('input', '#profit',function() {
        costCalculate();
    });

    // Variant all functionality
    var variantsWithChild = '';
    function getAllVariant() {
        $.ajax({
            url: "{{ route('products.add.get.all.from.variant') }}",
            async: true,
            type: 'get',
            dataType: 'json',
            success: function(variants) {
                variantsWithChild = variants;
                $('#variants').append('<option value="">@lang('Create Combination')</option>');
                $.each(variants, function(key, val) {
                    $('#variants').append('<option value="' + val.id + '">' + val
                        .bulk_variant_name + '</option>');
                });
            }
        });
    }
    getAllVariant();

    var variant_row_index = 0;
    $(document).on('change', '#variants', function() {
        var id = $(this).val();
        var parentTableRow = $(this).closest('tr');
        variant_row_index = parentTableRow.index();

        $('.modal_variant_child').empty();

        var html = '';

        var variant = variantsWithChild.filter(function(variant) {
            return variant.id == id;
        });

        $.each(variant[0].bulk_variant_child, function(key, child) {
            html += '<li class="modal_variant_child_list">';
            html += '<a class="select_variant_child" data-child="' + child.child_name + '" href="#">' +
                child.child_name + '</a>';
            html += '</li>';
        });

        $('.modal_variant_child').html(html);
        $('#VairantChildModal').modal('show');
        $(this).val('');
    });

    $(document).on('click', '.select_variant_child', function(e) {

        e.preventDefault();
        var child = $(this).data('child');
        var parent_tr = $('.dynamic_variant_body tr:nth-child(' + (variant_row_index + 1) + ')');
        var child_value = parent_tr.find('#variant_combination').val();
        var filter = child_value == '' ? '' : ',';
        var variant_combination = parent_tr.find('#variant_combination').val(child_value + filter + child);
        $('#VairantChildModal').modal('hide');
    });

    $(document).on('input', '#variant_costing', function() {

        var parentTableRow = $(this).closest('tr');
        variant_row_index = parentTableRow.index();
        calculateVariantAmount(variant_row_index);
    });

    $(document).on('input', '#variant_profit', function() {

        var parentTableRow = $(this).closest('tr');
        variant_row_index = parentTableRow.index();
        calculateVariantAmount(variant_row_index);
    });

    function calculateVariantAmount(variant_row_index) {

        var parent_tr = $('.dynamic_variant_body tr:nth-child(' + (variant_row_index + 1) + ')');
        var tax = tax_percent;
        var variant_costing = parent_tr.find('#variant_costing');
        var variant_costing_with_tax = parent_tr.find('#variant_costing_with_tax');
        var variant_profit = parent_tr.find('#variant_profit').val() ? parent_tr.find('#variant_profit').val() : 0.00;
        var variant_price_exc_tax = parent_tr.find('#variant_price_exc_tax');

        var tax_rate = parseFloat(variant_costing.val()) / 100 * tax;
        var cost_with_tax = parseFloat(variant_costing.val()) + tax_rate;
        variant_costing_with_tax.val(parseFloat(cost_with_tax).toFixed(2));

        var profit = parseFloat(variant_costing.val()) / 100 * parseFloat(variant_profit) + parseFloat(variant_costing
            .val());
        variant_price_exc_tax.val(parseFloat(profit).toFixed(2));
    }

    var variant_code_sequel = 0;
    // Select Variant and show variant creation area
    $(document).on('change', '#is_variant', function() {

        var product_cost = $('#product_cost').val();
        var product_cost_with_tax = $('#product_cost_with_tax').val();
        var profit = $('#profit').val();
        var product_price = $('#product_price').val();

        if (product_cost == '' || product_price == '') {

            alert('After creating the variant, product cost and product price field must not be empty.');
            $(this).prop('checked', false);
            return;
        }

        var code = $('#code').val();

        var auto_generated_code = $('#auto_generated_code').val();

        var variant_code = code ? code+'-'+(++variant_code_sequel) : auto_generated_code+'-'+(++variant_code_sequel);

        $('#variant_code').val(variant_code);
        $('#variant_costing').val(parseFloat(product_cost).toFixed(2));
        $('#variant_costing_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        $('#variant_price_exc_tax').val(parseFloat(product_price).toFixed(2));
        $('#variant_profit').val(parseFloat(profit).toFixed(2));
        if ($(this).is(':CHECKED', true)) {

            $('.dynamic_variant_create_area').show(500);
            $('#variant_combination').prop('required', true);
            $('#variant_costing').prop('required', true);
            $('#variant_costing_with_tax').prop('required', true);
            $('#variant_profit').prop('required', true);
            $('#variant_price_exc_tax').prop('required', true);
        } else {

            $('.dynamic_variant_create_area').hide(500);
            $('#variant_combination').prop('required', false);
            $('#variant_costing').prop('required', false);
            $('#variant_costing_with_tax').prop('required', false);
            $('#variant_profit').prop('required', false);
            $('#variant_price_exc_tax').prop('required', false);
        }
    });

    // Get default profit
    var defaultProfit = {{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }};

    $(document).on('click', '#add_more_variant_btn',function(e) {
        e.preventDefault();

        var code = $('#code').val();

        var auto_generated_code = $('#auto_generated_code').val();

        var variant_code = code ? code+'-'+(++variant_code_sequel) : auto_generated_code+'-'+(++variant_code_sequel);

        var product_cost = $('#product_cost').val();
        var product_cost_with_tax = $('#product_cost_with_tax').val();
        var profit = $('#profit').val();
        var product_price = $('#product_price').val();
        var html = '';
        html += '<tr id="more_new_variant">';
        html += '<td>';
        html += '<select class="form-control" name="" id="variants">';
        html += '<option value="">@lang('Create Combination')</option>';

        $.each(variantsWithChild, function(key, val) {

            html += '<option value="' + val.id + '">' + val.bulk_variant_name + '</option>';
        });

        html += '</select>';
        html += '<input type="text" name="variant_combinations[]" id="variant_combination" class="form-control" placeholder="@lang('Variant Combination')" required>';
        html += '</td>';
        html += '<td><input type="text" name="variant_codes[]" id="variant_code" class="form-control" placeholder="@lang('Variant Code')" value="'+variant_code+'">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_costings[]" class="form-control" placeholder="@lang('Cost')" id="variant_costing" value="' +
            parseFloat(product_cost).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_costings_with_tax[]" class="form-control" placeholder="@lang('Cost inc.tax')" id="variant_costing_with_tax" value="' +
            parseFloat(product_cost_with_tax).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_profits[]" class="form-control" placeholder="@lang('Profit')" value="' +
            parseFloat(profit).toFixed(2) + '" id="variant_profit">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_prices_exc_tax[]" class="form-control" placeholder="@lang('Price inc.tax')" id="variant_price_exc_tax" value="' +
            parseFloat(product_price).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input type="file" name="variant_image[]" class="form-control" id="variant_image">';
        html += '</td>';
        html += '<td><a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a></td>';
        html += '</tr>';
        $('.dynamic_variant_body').prepend(html);
    });
    // Variant all functionality end

    // This functionality of Count prackage product all prices
    function CountTotalComboProductPrice(allQuantities, allUnitPrices) {

        var allUnitPriceContainer = [];
        allUnitPrices.forEach(function(price) {

            allUnitPriceContainer.push(price.value);
        });

        var countedPrice = [];
        var i = 0;
        allQuantities.forEach(function(quantity) {

            countedPrice.push(parseFloat(parseFloat(quantity.value) * parseFloat(allUnitPriceContainer[i])));
            i++;
        });

        var totalPrice = 0;
        countedPrice.forEach(function(price) {

            totalPrice += parseFloat(price);
        });

        return parseFloat(parseFloat(totalPrice));
    }

    function get_form_part(type) {
        $.ajax({
            url: "{{ url('product/get/form/part/') }}"+"/"+type,
            async: true,
            type: 'get',
            success: function(html) {

               $('.form_part').html(html);
            }
        });
    }

    // call jquery method
    var action_direction = '';
    $(document).ready(function() {

        $(document).on('click', '.submit_button', function() {

            action_direction = $(this).val();
        });

        // Select product and show spacific product creation fields or area
        $('#type').on('change', function() {

            var value = $(this).val();
            get_form_part(value);
        });

        // Automatic generate product code
        function autoGeneratedCode() {

            var code = '';
            var x = 9; // can be any number
            var rand = Math.floor(Math.random() * x) + 1;
            var range = 7;
            var length = 0;

            while (length < range) {

                var x = 9; // can be any number
                var rand = Math.floor(Math.random() * x) + 1;
                code += rand.toString();
                length++;
            }

            $('#auto_generated_code').val("{{ json_decode($generalSettings->product, true)['product_code_prefix'] }}" + code);
        }
        autoGeneratedCode();

        // Search product for creating combo
        $(document).on('input', '#search_product',function(e) {

            $('.variant_list_area').empty();
            $('.select_area').hide();
            var productCode = $(this).val();

            if ((productCode === "")) {

                $('.variant_list_area').empty();
                $('.select_area').hide();
                return;
            }

            $.ajax({
                url: "{{ url('product/search/product') }}" + "/" + productCode,
                dataType: 'json',
                success: function(product) {

                    if (!$.isEmptyObject(product)) {

                        $('#search_product').addClass('is-valid');
                    }

                    if(!$.isEmptyObject(product.product) || !$.isEmptyObject(product.variant_product)){

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
                                        // update same product qty
                                        var presentQty = closestTr.find('#combo_quantity').val();
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#combo_quantity').val(updateQty);

                                        // update unit cost with discount
                                        var unitPriceIncTax = closestTr.find('#unit_price_inc_tax').val();
                                        // update subtotal
                                        var calcSubTotal = parseFloat(unitPriceIncTax) * parseFloat(updateQty);
                                        var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));
                                        calculateTotalAmount();
                                        return;
                                    }
                                });

                                if(sameProduct == 0){

                                    var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0;
                                    var tax_amount = parseFloat(product.tax != null ? product.product_price/100 * product.tax.tax_percent : 0);
                                    var tr = '';
                                    tr += '<tr class="text-center">';
                                    tr += '<td>';
                                    tr += '<span class="product_name">'+product.name+'</span><br>';
                                    tr += '<span class="product_code">('+product.product_code+')</span><br>';
                                    tr += '<span class="product_variant"></span>';
                                    tr += '<input value="'+product.id+'" type="hidden" class="productId-'+product.id+'" id="product_id" name="product_ids[]">';
                                    tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input type="number" step="any" value="1" required name="combo_quantities[]" class="form-control form-control-sm" id="combo_quantity">';
                                    tr += '</td>';

                                    var unitPriceIncTax = product.product_price + tax_amount;
                                    tr += '<td>';
                                    tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" required name="unit_prices_inc_tax[]" type="text" class="form-control form-control-sm" id="unit_price_inc_tax">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" required name="subtotals[]" type="text" class="form-control form-control-sm" id="subtotal">';
                                    tr += '</td>';

                                    tr += '<td class="text-right">';
                                    tr += '<a href="" id="remove_combo_product_btn" class="btn btn-sm btn-danger">-</a>';
                                    tr += '</td>';

                                    tr += '</tr>';
                                    $('#combo_products').append(tr);
                                    calculateTotalAmount();
                                }
                            }else{

                                var li = "";
                                var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0.00;

                                $.each(product.product_variants, function(key, variant){

                                    var tax_amount = parseFloat(product.tax != null ? variant.variant_price/100 * product.tax.tax_percent : 0.00);
                                    var variantPriceIncTax = variant.variant_price + tax_amount;
                                    li += '<li>';
                                    li += '<a class="select_variant_product" data-p_id="' +
                                    product.id + '" data-v_id="' + variant.id +
                                        '" data-p_name="' + product.name +
                                        '" data-v_code="' + variant.product_code +
                                        '" data-v_price="' + parseFloat(variantPriceIncTax).toFixed(2) +
                                        '" data-v_name="' + variant.variant_name +
                                        '" href="#">' + product.name + ' [' + variant
                                        .variant_name + ']' + '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }

                        }else if(!$.isEmptyObject(product.variant_product)){

                            $('.select_area').hide();
                            $('#search_product').val('');
                            var variant_product = product.variant_product;
                            var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.percent : 0;
                            var tax_rate = parseFloat(variant_product.product.tax != null ? variant_product.variant_cost/100 * tax_percent : 0);
                            var variant_ids = document.querySelectorAll('#variant_id');
                            var sameVariant = 0;

                            variant_ids.forEach(function(input){

                                if(input.value != 'noid'){

                                    if(input.value == variant_product.id){

                                        sameVariant += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.'+className).closest('tr');
                                        // update same product qty
                                        var presentQty = closestTr.find('#combo_quantity').val();
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#combo_quantity').val(updateQty);

                                        // update unit cost with discount
                                        var unitPriceIncTax = closestTr.find('#unit_price_inc_tax').val();
                                        // update subtotal
                                        var calcSubTotal = parseFloat(unitPriceIncTax) * parseFloat(updateQty);
                                        var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));
                                        calculateTotalAmount();
                                        return;
                                    }
                                }
                            });

                            if(sameVariant == 0){

                                var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.tax_percent : 0;
                                var tax_amount = parseFloat(variant_product.product.tax != null ? variant_product.variant_price/100 * variant_product.product.tax.tax_percent : 0);
                                var tr = '';
                                tr += '<tr class="text-center">';
                                tr += '<td>';
                                tr += '<span class="product_name">'+variant_product.product.name+'</span><br>';
                                tr += '<span class="product_code">('+variant_product.variant_code+')</span><br>';
                                tr += '<span class="product_variant">('+variant_product.variant_name+')</span>';
                                tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                                tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="1.00" required name="combo_quantities[]" type="text" class="form-control form-control-sm" id="combo_quantity">';
                                tr += '</td>';

                                var unitPriceIncTax = variant_product.variant_price + tax_amount;
                                tr += '<td>';
                                tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" required name="unit_prices_inc_tax[]" type="text" class="form-control form-control-sm" id="unit_price_inc_tax">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input readonly value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" type="text" name="subtotal[]" id="subtotal" class="form-control form-control-sm">';
                                tr += '</td>';

                                tr += '<td class="text-right">';
                                tr += '<a href="" id="remove_combo_product_btn" class="btn btn-sm btn-danger">-</a>';
                                tr += '</td>';

                                tr += '</tr>';
                                $('#combo_products').append(tr);
                                calculateTotalAmount();
                            }
                        }
                    }else{

                        $('#search_product').addClass('is-invalid');
                    }
                }
            });
        });

        // Select variant product for creating combo
        $(document).on('click', '.select_variant_product', function(e) {
            e.preventDefault();
            $('#selectVairantModal').modal('hide');
            var product_id = $(this).data('p_id');
            var product_name = $(this).data('p_name');
            var variant_id = $(this).data('v_id');
            var variant_name = $(this).data('v_name');
            var variant_code = $(this).data('v_code');
            var variant_price_inc_tax  = $(this).data('v_price');
            var variant_ids = document.querySelectorAll('#variant_id');
            var sameVariant = 0;

            variant_ids.forEach(function(input){

                if(input.value != 'noid'){

                    if(input.value == variant_id){

                        sameVariant += 1;
                        var className = input.getAttribute('class');
                        var className = input.getAttribute('class');
                        // get closest table row for increasing qty and re calculate product amount
                        var closestTr = $('.'+className).closest('tr');
                        // update same product qty
                        var presentQty = closestTr.find('#combo_quantity').val();
                        var updateQty = parseFloat(presentQty) + 1;
                        closestTr.find('#combo_quantity').val(updateQty);

                        // update unit cost with discount
                        var unitPriceIncTax = closestTr.find('#unit_price_inc_tax').val();
                        // update subtotal
                        var calcSubTotal = parseFloat(unitPriceIncTax) * parseFloat(updateQty);
                        var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));
                        calculateTotalAmount();
                        return;
                    }
                }
            });

            if(sameVariant == 0){

                var tr = '';
                tr += '<tr class="text-center">';
                tr += '<td>';
                tr += '<span class="product_name">'+product_name+'</span><br>';
                tr += '<span class="product_code">('+variant_code+')</span><br>';
                tr += '<span class="product_variant">('+variant_name+')</span>';
                tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                tr += '<input value="'+variant_id+'" type="hidden" class="variantId-'+variant_id+'" id="variant_id" name="variant_ids[]">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input type="number" step="any" value="1.00" required name="combo_quantities[]" class="form-control form-control-sm" id="combo_quantity">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly value="'+variant_price_inc_tax+'" required name="unit_prices_inc_tax[]" type="number" step="any" class="form-control form-control-sm" id="unit_price_inc_tax">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly type="number" step="any" value="'+variant_price_inc_tax+'" required name="subtotals[]" class="form-control form-control-sm" id="subtotal">';
                tr += '</td>';

                tr += '<td class="text-right">';
                tr += '<a href="" id="remove_combo_product_btn" class="btn btn-sm btn-danger mt-1">-</a>';
                tr += '</td>';

                tr += '</tr>';
                $('#combo_products').append(tr);
                calculateTotalAmount();
            }
        });

        function calculateTotalAmount() {

            var subtotals = document.querySelectorAll('#subtotal');
            var netTotalAmount = 0;

            subtotals.forEach(function(subtotal){

                netTotalAmount += parseFloat(subtotal.value);
            });

            $('.span_total_combo_price').html(parseFloat(netTotalAmount).toFixed(2));
            $('#total_combo_price').val(parseFloat(netTotalAmount).toFixed(2));
            var profit = $('#profit').val();
            var combo_price_exc_tax = parseFloat(netTotalAmount) / 100 * parseFloat(profit) + parseFloat(netTotalAmount);
            $('#combo_price').val(parseFloat(combo_price_exc_tax).toFixed(2));
        }

        // Combo product total price increase or dicrease by quantity
        $(document).on('input', '#combo_quantity', function() {

            var qty = $(this).val() ? $(this).val() : 0;

            var tr = $(this).closest('tr');

            //Update subtotal
            var unitPriceIncTax = $(this).closest('tr').find('#unit_price_inc_tax').val();
            var calcSubtotal = parseFloat(unitPriceIncTax) * parseFloat(qty);
            var subtotal = tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            calculateTotalAmount();
        });

        $(document).on('click', '#remove_combo_product_btn', function(e) {

            e.preventDefault();
            $(this).closest('tr').remove();
            calculateTotalAmount();
        });

        // Dispose Select area
        $(document).on('click', '.remove_select_area_btn', function(e) {

            e.preventDefault();
            $('.select_area').hide();
        });

        // Romove variant table row
        $(document).on('click', '#variant_remove_btn', function(e) {

            e.preventDefault();
            $(this).closest('tr').remove();
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        // set sub category in form field
        $('#category_id').on('change', function() {

            var category_id = $(this).val();

            $.get("{{ url('common/ajax/call/category/subcategories/') }}"+"/"+category_id, function(subCategories) {

                $('#child_category_id').empty();
                $('#child_category_id').append('<option value="">@lang('Select Sub-Category')</option>');

                $.each(subCategories, function(key, val) {

                    $('#child_category_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                });
            });
        });

        // Add product by ajax
        $('#add_product_form').on('submit', function(e) {

            e.preventDefault();
            $('.loading_button').removeClass('d-none');
            $('.submit_button').prop('type', 'button');
            var url = $(this).attr('action');

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    $('.loading_button').addClass('d-none');
                    $('.submit_button').prop('type', 'submit');
                    $('.error').html('');

                    if ($.isEmptyObject(data.errorMsg)) {

                        toastr.success(data);
                        variant_code_sequel = 0;

                        if (action_direction == 'save') {

                            window.location = "{{ route('products.all.product') }}";
                        } else {

                            @if ($addons->e_commerce == 1)

                                clearEditor();
                            @endif

                            $('#add_product_form')[0].reset();
                            get_form_part(1);
                            $('#profit').val(parseFloat(defaultProfit).toFixed(2));
                            document.getElementById('name').focus();
                            autoGeneratedCode();
                            table.ajax.reload();
                        }
                    } else {

                        toastr.error(data.errorMsg);
                    }
                },error: function(err) {

                    $('.loading_button').addClass('d-none');
                    $('.submit_button').prop('type', 'submit');
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
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

        // Automatic remove searching product not found signal
        setInterval(function() {

            $('#search_product').removeClass('is-invalid');
        }, 350);

        // Automatic remove searching product is found signal
        setInterval(function() {

            $('#search_product').removeClass('is-valid');
        }, 1000);
    });

    // Add category from create product by ajax
    $(document).on('submit', '#add_category_form', function(e) {

        e.preventDefault();
        $('.loading_button').removeClass('d-none');
        var url = $(this).attr('action');
        var request = $(this).serialize();

        var inputs = $('.add_cate_input');
        $('.error').html('');
        var countErrorField = 0;

        $.each(inputs, function(key, val) {

            var inputId = $(val).attr('id');
            var idValue = $('#' + inputId).val();

            if (idValue == '') {

                countErrorField += 1;
                var fieldName = $('#' + inputId).data('name');
                $('.error_' + inputId).html(fieldName + ' is required.');
            }
        });

        if (countErrorField > 0) {

            $('.loading_button').addClass('d-none');
            return;
        }

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.loading_button').addClass('d-none');
                $('#addCategoryModal').modal('hide');
                $('#add_category_form')[0].reset();
                $('#category_id').append('<option value="' + data.id + '">' + data.name +
                    '</option>');
                $('#category_id').val(data.id);
                toastr.success(data);
            }
        });
    });


    $(document).on('submit', '#add_sub_category_form', function(e) {

e.preventDefault();
$('.loading_button').removeClass('d-none');
var url = $(this).attr('action');
var request = $(this).serialize();

var inputs = $('.add_sub_input');
$('.error').html('');
var countErrorField = 0;

$.each(inputs, function(key, val) {

    var inputId = $(val).attr('id');
    var idValue = $('#' + inputId).val();

    if (idValue == '') {

        countErrorField += 1;
        var fieldName = $('#' + inputId).data('name');
        $('.error_' + inputId).html(fieldName + ' is required.');
    }
});

if (countErrorField > 0) {

    $('.loading_button').addClass('d-none');
    return;
}

$.ajax({
    url: url,
    type: 'post',
    data: request,
    success: function(data) {
        $('.loading_button').addClass('d-none');
        $('#addSubCategoryModal').modal('hide');
        $('#add_sub_category_form')[0].reset();
        $('#child_category_id').append('<option value="' + data.id + '">' + data.name +
            '</option>');
        $('#child_category_id').val(data.id);
        toastr.success(data);
    }
});
});



    // Add brand from create product by ajax
    $(document).on('submit', '#add_brand_form', function(e) {

        e.preventDefault();
        $('.loading_button').removeClass('d-none');
        var url = $(this).attr('action');
        var request = $(this).serialize();

        var inputs = $('.add_brand_input');
        $('.error').html('');
        var countErrorField = 0;

        $.each(inputs, function(key, val) {

            var inputId = $(val).attr('id');
            var idValue = $('#' + inputId).val();

            if (idValue == '') {

                countErrorField += 1;
                var fieldName = $('#' + inputId).data('name');
                $('.error_' + inputId).html(fieldName + ' is required.');
            }
        });

        if (countErrorField > 0) {

            $('.loading_button').hide();
            return;
        }

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.loading_button').addClass('d-none');
                $('#brand_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                $('#brand_id').val(data.id);
                $('#addBrandModal').modal('hide');
                $('#add_brand_form')[0].reset();
                toastr.success(data, 'Successfully brand is added.');
            }
        });
    });

    // Add unit from create product by ajax
    $(document).on('submit', '#add_unit_form', function(e) {

        e.preventDefault();
         $('.loading_button').removeClass('d-none');
        var url = $(this).attr('action');
        var request = $(this).serialize();

        var inputs = $('.add_unit_input');
        $('.error').html('');
        var countErrorField = 0;

        $.each(inputs, function(key, val) {

            var inputId = $(val).attr('id');
            var idValue = $('#' + inputId).val();

            if (idValue == '') {

                countErrorField += 1;
                var fieldName = $('#' + inputId).data('name');
                $('.error_' + inputId).html(fieldName + ' is required.');
            }
        });

        if (countErrorField > 0) {

             $('.loading_button').addClass('d-none');
            return;
        }

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.loading_button').addClass('d-none');
                toastr.success('Successfully brand is added.');
                $('#unit_id').append('<option value="' + data.id + '">' + data.name + ' (' + data
                    .code_name + ')' + '</option>');
                $('#unit_id').val(data.id);
                $('#addUnitModal').modal('hide');
                $('#add_unit_form')[0].reset();
            }
        });
    });

    // Add warranty from create product by ajax
    $(document).on('submit', '#add_warranty_form', function(e) {
        e.preventDefault();
        $('.loading_button').removeClass('d-none');
        var url = $(this).attr('action');
        var request = $(this).serialize();

        var inputs = $('.add_warranty_input');
        $('.error').html('');
        var countErrorField = 0;
        $.each(inputs, function(key, val) {

            var inputId = $(val).attr('id');
            var idValue = $('#' + inputId).val();

            if (idValue == '') {

                countErrorField += 1;
                var fieldName = $('#' + inputId).data('name');
                $('.error_' + inputId).html(fieldName + ' is required.');
            }
        });

        if (countErrorField > 0) {

             $('.loading_button').addClass('d-none');
            return;
        }

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.loading_button').addClass('d-none');
                toastr.success('Successfully warranty is added.');
                $('#warranty_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                $('#warranty_id').val(data.id);
                $('#addWarrantyModal').modal('hide');
                $('#add_warranty_form')[0].reset();
            }
        });
    });

    $(document).keypress(".scanable",function(event){

        if (event.which == '10' || event.which == '13') {

            event.preventDefault();
        }
    });

    $(document).on('click', '#digital_product',function () {

        if ($(this).is(':CHECKED')) {

            $('#is_manage_stock').prop('checked', false);
        }else{

            $('#is_manage_stock').prop('checked', true);
        }
    });

    $(document).on('click', '#is_manage_stock',function () {

        if ($(this).is(':CHECKED')) {

            $('#digital_product').prop('checked', false);
        }else{

            $('#digital_product').prop('checked', true);
        }
    });

    document.onkeyup = function () {
        var e = e || window.event; // for IE to cover IEs window event-object

        if(e.ctrlKey && e.which == 13) {

            $('#save_and_new').click();
            return false;
        }else if (e.shiftKey && e.which == 13) {

            $('#save').click();
            return false;
        }
    }
</script>
@endpush
