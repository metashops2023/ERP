@extends('layout.master')
@push('stylesheets')
    <style>
        table.display td input {height: 26px!important; padding: 3px;}
        span.input-group-text-custom {font-size: 11px;padding: 4px;}
        .sale-content {margin-top: -14px;}
        .last_section {margin-top: -14px;}
        p.is_final {margin-top: -11px;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="update_production_form" action="{{ route('manufacturing.productions.update', $production->id) }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6"><h5>@lang('Edit Production')</h5></div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label><b>@lang('Production A/C') : <span class="text-danger">*</span></b></label>
                                            <select name="production_account_id" class="form-control add_input"
                                                id="production_account_id" data-name="Production A/C">
                                                @foreach ($productionAccounts as $productionAccount)
                                                    <option {{ $productionAccount->id == $production->production_account_id ? 'SELECTED' : '' }} value="{{ $productionAccount->id }}">
                                                        {{ $productionAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_production_account_id"></span>
                                        </div>

                                        <div class="col-md-2">
                                            @if ($production->warehouse_id)
                                                <input type="hidden" value="YES" name="store_warehouse_count">
                                                <label> <b>@lang('Store Location') : </b> <span
                                                    class="text-danger">*</span></label>
                                                <select class="form-control changeable add_input"
                                                    name="store_warehouse_id" data-name="Warehouse" id="store_warehouse_id">
                                                    <option value="">@lang('Select Warehouse')</option>
                                                    @foreach ($warehouses as $w)
                                                        <option {{ $production->warehouse_id == $w->id ? 'SELECTED' : '' }}  value="{{ $w->id }}">{{ $w->warehouse_name.'/'.$w->warehouse_code }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_warehouse_id"></span>
                                            @else
                                                <label><b>@lang('Store Location') :</b> </label>
                                                <input readonly type="text" name="store_branch_id" class="form-control changeable" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}" tabindex="-1"/>
                                            @endif
                                        </div>

                                        <div class="col-md-2">
                                            <label > <b>@lang('Voucher No') :</b></label>
                                            <input type="text" name="reference_no" class="form-control changeable" placeholder="@lang('Voucher No')" value="{{ $production->reference_no }}"/>
                                        </div>

                                        <div class="col-md-2">
                                            <label><b>@lang('Date') :</b></label>
                                            <input required type="text" name="date" class="form-control changeable" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($production->date)) }}" id="datepicker">
                                        </div>

                                        <div class="col-md-2">
                                            @if ($production->stock_warehouse_id)
                                                <label > <b>@lang('Ingredials Stock Location') : </b> <span class="text-danger">*</span></label>
                                                <input readonly type="text" class="form-control" value="{{ $production->stock_warehouse->warehouse_name.'/'.$production->stock_warehouse->warehouse_code }}" tabindex="-1">
                                            @else
                                                <label><b>@lang('Ingredials Stock Location') :</b> </label>
                                                <input readonly type="text" name="stock_branch_id" class="form-control" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}" tabindex="-1"/>
                                            @endif
                                        </div>

                                        <div class="col-md-2">
                                            <label><b>@lang('Product') :</b> <span class="text-danger">*</span></label>
                                            <input readonly type="text" value="{{ $production->product->name }} {{ $production->variant ? $production->variant->variant_name : '' }}" class="form-control" tabindex="-1">
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
                                                                    <th>@lang('Ingredient')</th>
                                                                    <th>@lang('Input Quantity')</th>
                                                                    <th>@lang('Unit Cost')</th>
                                                                    <th>@lang('SubTotal')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="ingredient_list">
                                                                @foreach ($production->ingredients as $ingredient)
                                                                    @php
                                                                        $stock = 0;
                                                                        if ($ingredient->variant_id) {
                                                                            if ($production->stock_warehouse_id) {
                                                                                $productWarehouse = DB::table('product_warehouses')->where('warehouse_id', $warehouseId)
                                                                                ->where('product_id', $ingredient->product_id)->first();
                                                                                if ($productWarehouse) {
                                                                                    $productWarehouseVariant = DB::table('product_warehouse_variants')->where('product_warehouse_id', $productWarehouse->id)
                                                                                    ->where('product_variant_id', $ingredient->variant_id)->first();
                                                                                    $stock = $productWarehouseVariant ? $productWarehouseVariant->variant_quantity : 0;
                                                                                }
                                                                            }else {
                                                                                $productBranch = DB::table('product_branches')->where('branch_id', auth()->user()->branch_id)
                                                                                ->where('product_id', $ingredient->product_id)->first();
                                                                                if ($productBranch) {
                                                                                    $productBranchVariant = DB::table('product_branch_variants')->where('product_branch_id', $productBranch->id)
                                                                                    ->where('product_variant_id', $ingredient->variant_id)->first();
                                                                                    $stock = $productBranchVariant ? $productBranchVariant->variant_quantity : 0;
                                                                                }
                                                                            }
                                                                        }else {
                                                                            if ($production->stock_warehouse_id) {
                                                                                $productWarehouse = DB::table('product_warehouses')->where('warehouse_id', $production->stock_warehouse_id)
                                                                                ->where('product_id', $ingredient->product_id)->first();
                                                                                $stock = $productWarehouse ? $productWarehouse->product_quantity : 0;
                                                                            } else {
                                                                                $productBranch = DB::table('product_branches')->where('branch_id', auth()->user()->branch_id)
                                                                                ->where('product_id', $ingredient->product_id)->first();
                                                                                $stock = $productBranch ? $productBranch->product_quantity : 0;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <tr class="text-start">
                                                                        <td>
                                                                            <span class="product_name">{{ $ingredient->product->name }}</span><br>
                                                                            <span class="product_variant">{{ $ingredient->variant_id ? $ingredient->variant->variant_name : '' }}</span>
                                                                            <input value="{{ $ingredient->product_id }}" type="hidden" class="productId-{{ $ingredient->product_id }}" id="product_id" name="product_ids[]">
                                                                            <input value="{{ $ingredient->variant_id ? $ingredient->variant_id : 'noid' }}" type="hidden" id="variant_id" name="variant_ids[]">
                                                                            <input value="{{ $ingredient->unit->id }}" name="unit_ids[]" type="hidden" step="any" id="unit_id">
                                                                            <input value="{{ bcadd($ingredient->input_qty, 0 ,2) }}" type="hidden" step="any" id="previous_qty">
                                                                            <input value="{{ bcadd($stock, 0 ,2) }}" type="hidden" step="any" data-unit="{{ $ingredient->unit->name }}" id="qty_limit">
                                                                        </td>

                                                                        <td>
                                                                            <div class="input-group p-2">
                                                                                <input value="{{ $ingredient->input_qty }}" required name="input_quantities[]" type="number" class="form-control text-center" id="input_quantity">
                                                                                <input value="{{ $ingredient->parameter_quantity }}" name="parameter_input_quantities[]" type="hidden" id="parameter_input_quantity">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text input-group-text-custom">{{ $ingredient->unit->name }}</span>
                                                                                </div>
                                                                                &nbsp;<strong><p class="text-danger m-0 p-0" id="input_qty_error"></p></strong>
                                                                            </div>
                                                                        </td>

                                                                        <td>
                                                                            <input value="{{ $ingredient->unit_cost_inc_tax }}" required name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">
                                                                            <span id="span_unit_cost_inc_tax">{{ $ingredient->unit_cost_inc_tax }}</span>
                                                                        </td>

                                                                        <td>
                                                                            <input value="{{ $ingredient->subtotal }}" type="hidden" step="any" name="subtotals[]" id="subtotal">
                                                                            <span id="span_subtotal">{{ $ingredient->subtotal }}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
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

                <div class="row">
                    <div class="col-md-12">
                        <input type="text" class="d-none" name="total_ingredient_cost" id="total_ingredient_cost" value="{{ $production->total_ingredient_cost }}">
                        <p class="mt-1 float-end clearfix"><strong>@lang('Total Ingrediant Cost') : </strong> <span id="span_total_ingredient_cost">{{ $production->total_ingredient_cost }}</span></p>
                    </div>
                </div>

                <section class="last_section">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form_element">
                                <div class="element-body">
                                    <p><strong>@lang('Total Production Costing') </strong></p>
                                    <hr class="p-0 m-0 mb-1">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Output Qty') :</b></label>
                                                <div class="col-md-8">
                                                    <input required type="number" step="any" data-name="Quantity" class="form-control add_input" name="output_quantity" id="output_quantity" value="{{ $production->quantity }}">
                                                    <input type="text" name="parameter_quantity" class="d-none" id="parameter_quantity" value="{{ $production->parameter_quantity }}">
                                                    <span class="error error_quantity"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Wasted Qty') :</b></label>
                                                <div class="col-md-8">
                                                    <input type="number" step="any" name="wasted_quantity" class="form-control" id="wasted_quantity" value="{{ $production->wasted_quantity }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Final Output Qty') :</b></label>
                                                <div class="col-md-8">
                                                    <input readonly type="text" step="any" class="form-control" name="final_output_quantity" id="final_output_quantity" value="{{ $production->total_final_quantity }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Additional Cost') :</b></label>
                                                <div class="col-md-8">
                                                    <input name="production_cost" type="number" class="form-control" id="production_cost" value="{{ $production->production_cost }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Total Production Cost') :</b></label>
                                                <div class="col-md-8">
                                                    <input readonly type="number" step="any" name="total_cost" class="form-control" id="total_cost" value="{{ $production->total_cost }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="form_element">
                                <div class="element-body">
                                    <p><strong>@lang('Pricing')</strong></p>
                                    <hr class="p-0 m-0 mb-1">
                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"><b>@lang('Tax') :</b> </label>
                                                <div class="col-8">
                                                    <select class="form-control" name="tax_id" id="tax_id">
                                                        <option value="">@lang('NoTax')</option>
                                                        @foreach ($taxes as $tax)
                                                            <option {{ $tax->id == $production->tax_id ? 'SELECTED' : '' }} value="{{ $tax->id.'-'.$tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"><b>@lang('Tax Type') :</b> </label>
                                                <div class="col-8">
                                                    <select name="tax_type" class="form-control" id="tax_type">
                                                        <option {{ $production->tax_type == 1 ? 'SELECTED' : '' }} value="1">@lang('Exclusive')</option>
                                                        <option {{ $production->tax_type == 2 ? 'SELECTED' : '' }}  value="2">@lang('Inclusive')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Par Unit Cost') :</b></label>
                                                <div class="col-md-8">
                                                    <input required type="text" name="per_unit_cost_exc_tax" id="per_unit_cost_exc_tax" class="form-control" placeholder="@lang('Par Unit Cost Exc.Tax')" autocomplete="off" value="{{ $production->unit_cost_exc_tax }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Cost(Inc.Tax)')' :</b></label>
                                                <div class="col-md-8">
                                                    <input readonly type="text" name="per_unit_cost_inc_tax" id="per_unit_cost_inc_tax" class="form-control" placeholder="@lang('Par Unit Cost Inc.Tax')" autocomplete="off" value="{{ $production->unit_cost_inc_tax }}" tabindex="-1" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('xMargin')(%) :</b></label>
                                                <div class="col-md-8">
                                                    <input type="text" name="xMargin" id="xMargin" class="form-control" placeholder="@lang('xMargin')" autocomplete="off" value="{{ $production->x_margin }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Selling Price') :</b></label>
                                                <div class="col-md-8">
                                                    <input type="text" name="selling_price" id="selling_price" class="form-control" placeholder="@lang('Selling Price')" autocomplete="off" value="{{ $production->price_exc_tax }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-danger"><b>@lang('If this production is the last entry, so the product cost and price will be updated where production status is final').</b></small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="float-end is_final">
                                        <input type="checkbox" {{ $production->is_final == 1 ? 'CHECKED' : ''}} name="is_final" id="is_final"> &nbsp; <b> @lang('Finalize')</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Once finalized all ingredient stock will be deducted & production item stock will be increased and production item unit cost, price will be updated as well as editing of production will not be allowed." class="fas fa-info-circle tp"></i></p>
                                </div>
                            </div>

                            <div class="submit_button_area">
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <button type="button" class="btn loading_button d-none"><i
                                            class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                        <button class="btn btn-sm btn-success submit_button float-end">@lang('Save Changes')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var tax_percent = 0;
        $('#tax_id').on('change', function() {
            var tax = $(this).val();
            if (tax) {
                var split = tax.split('-');
                tax_percent = split[1];
            }else{
                tax_percent = 0;
            }
            __productPricingCalculate();
        });

        $('#tax_type').on('change', function() {
            __productPricingCalculate();
        });

        //Get process data
        $(document).on('change', '#product_id', function (e) {
            e.preventDefault();
            var processId = $(this).val();
            var stockWarehouseId = $('#stock_warehouse_id').val() ? $('#stock_warehouse_id').val() : null;
            @if (count($warehouses) > 0)
                if (stockWarehouseId == null) {
                    toastr.error('Ingredials Stock Location must not be empty.');
                    var processId = $(this).val('');
                    return;
                }
            @endif
            var url = "{{ url('manufacturing/productions/get/process/') }}"+"/"+processId;

            $.get(url, function(data) {

                $('#product_id').val(data.product_id);
                $('#variant_id').val(data.variant_id);
                $('#output_quantity').val(data.total_output_qty);
                $('#final_output_quantity').val(data.total_output_qty);
                $('#parameter_quantity').val(data.total_output_qty);
                $('#unit_id').val(data.unit_id);
                $('#production_cost').val(data.production_cost);
                $('#total_ingredient_cost').val(data.total_ingredient_cost);
                $('#span_total_ingredient_cost').html(data.total_ingredient_cost);
                $('#total_cost').val(data.total_cost);
                var tax = data.tax_id ? data.tax_id+'-'+data.tax_percent : '';
                tax_percent = data.tax_percent  ? data.tax_percent : 0;
                $('#tax_id').val(tax);
                var product_id = data.product_id;
                var variantId = data.variant_id ? data.variant_id : null;
                var url = "{{ url('manufacturing/productions/get/ingredients') }}"+"/"+processId+"/"+stockWarehouseId;

                $.get(url, function(data) {$('#ingredient_list').html(data);__calculateTotalAmount();});
            });
        });

        $(document).on('input', '#output_quantity', function () {

            var presentQty = $(this).val() ? $(this).val() : 0;
            var parameterQty = $('#parameter_quantity').val() ? $('#parameter_quantity').val() : 0;
            var meltipilerQty = parseFloat(presentQty) / parseFloat(parameterQty);
            var allTr = $('#ingredient_list').find('tr');

            allTr.each(function () {

                var parameterInputQty = $(this).find('#parameter_input_quantity').val();
                var updateInputQty = parseFloat(meltipilerQty) * parseFloat(parameterInputQty);
                $(this).find('#input_quantity').val(parseFloat(updateInputQty).toFixed(2));
                __calculateIngredientsTableAmount($(this));
            });

            __calculateTotalAmount();
        });

        $(document).on('input', '#wasted_quantity', function () {

            __calculateTotalAmount();
        });

        $(document).on('input', '#production_cost', function () {

            __calculateTotalAmount();
        });

        $(document).on('input', '#input_quantity', function () {

            var value = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            tr.find('#parameter_input_quantity').val(parseFloat(value).toFixed(2));

            __calculateIngredientsTableAmount(tr);
        });

        var errorCount = 0;
        function __calculateIngredientsTableAmount(tr) {

            var inputQty = tr.find('#input_quantity').val() ? tr.find('#input_quantity').val() : 0;
            var unitCostIncTax = tr.find('#unit_cost_inc_tax').val();
            var stock_limit = tr.find('#qty_limit').val();
            var previous_qty = tr.find('#previous_qty').val();
            var limitQty = parseFloat(stock_limit) + parseFloat(previous_qty);
            var unitName = tr.find('#qty_limit').data('unit');
            var regexp = /^\d+\.\d{0,2}$/;
            tr.find('#input_qty_error').html('');

            if (regexp.test(parseFloat(inputQty)) == true) {

                tr.find('#input_qty_error').html('Deciaml value is not allowed.');
                errorCount++;
            } else if(parseFloat(inputQty) > parseFloat(limitQty)) {

                tr.find('#input_qty_error').html('Quantity exceeds stock quantity!');
                errorCount++;
            }

            var subtotal = parseFloat(inputQty) * parseFloat(unitCostIncTax);
            tr.find('#subtotal').val(parseFloat(subtotal).toFixed(2));
            tr.find('#span_subtotal').html(parseFloat(subtotal).toFixed(2));
            __calculateTotalAmount();
        }

        function __calculateTotalAmount(){

            var subtotals = document.querySelectorAll('#subtotal');
            var totalIngredientCost = 0;

            subtotals.forEach(function(subtotal){

                totalIngredientCost += parseFloat(subtotal.value);
            });

            $('#total_ingredient_cost').val(parseFloat(totalIngredientCost));
            $('#span_total_ingredient_cost').html(parseFloat(totalIngredientCost).toFixed(2));
            var output_total_qty = $('#output_quantity').val() ? $('#output_quantity').val() : 0;
            var wast_qty = $('#wasted_quantity').val() ? $('#wasted_quantity').val() : 0;
            var calsQtyWithWastedQty = parseFloat(output_total_qty) - parseFloat(wast_qty);
            $('#final_output_quantity').val(calsQtyWithWastedQty);
            var productionCost = $('#production_cost').val() ? $('#production_cost').val() : 0;
            var totalCost = parseFloat(totalIngredientCost) + parseFloat(productionCost);
            $('#total_cost').val(parseFloat(totalCost).toFixed(2));
            __productPricingCalculate();
        }

        function __productPricingCalculate() {

            var total_cost = $('#total_cost').val() ? $('#total_cost').val() : 0;
            var final_output_qty = $('#final_output_quantity').val() ? $('#final_output_quantity').val() : 0;
            var par_unit_cost = parseFloat(total_cost) / parseFloat(final_output_qty);
            var tax_type = $('#tax_type').val();
            var calc_product_cost_tax = parseFloat(par_unit_cost) / 100 * parseFloat(tax_percent);

            if (tax_type == 2) {

                var inclusive_tax_percent = 100 + parseFloat(tax_percent);
                var calc_tax = parseFloat(par_unit_cost) / parseFloat(inclusive_tax_percent) * 100;
                calc_product_cost_tax = parseFloat(par_unit_cost) - parseFloat(calc_tax);
            }

            var per_unit_cost_inc_tax = parseFloat(par_unit_cost) + parseFloat(calc_product_cost_tax);
            $('#per_unit_cost_exc_tax').val(parseFloat(par_unit_cost).toFixed(2));
            $('#per_unit_cost_inc_tax').val(parseFloat(per_unit_cost_inc_tax).toFixed(2));

            var xMargin = $('#xMargin').val() ? $('#xMargin').val() : 0;

            if (xMargin > 0) {

                var calculate_margin = parseFloat(par_unit_cost) / 100 * parseFloat(xMargin);
                var selling_price = parseFloat(par_unit_cost) + parseFloat(calculate_margin);
                $('#selling_price').val(parseFloat(selling_price).toFixed(2));
            }
        }

        $('#xMargin').on('input', function() {

            __productPricingCalculate();
        });

        $(document).on('input', '#selling_price',function() {

            var selling_price = $(this).val() ? $(this).val() : 0;
            var par_unit_cost = $('#per_unit_cost_exc_tax').val() ? $('#per_unit_cost_exc_tax').val() : 0;
            var profitAmount = parseFloat(selling_price) - parseFloat(par_unit_cost);
            var __cost = parseFloat(par_unit_cost) > 0 ? parseFloat(par_unit_cost) : parseFloat(profitAmount);
            var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
            var __calcProfit = calcProfit ? calcProfit : 0;
            $('#xMargin').val(parseFloat(__calcProfit).toFixed(2));
        });

        $('.submit_button').on('click', function () {

            var value = $(this).val();
            $('#action_type').val(value);
        });

        //Add process request by ajax
        $('#update_production_form').on('submit', function(e) {
            e.preventDefault();

            errorCount = 0;
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            var allTr = $('#ingredient_list').find('tr');

            allTr.each(function () {

                __calculateIngredientsTableAmount($(this));
            });

            if (errorCount > 0) {

                $('.loading_button').hide();
                toastr.error("@lang('Please check again all form fields.')",
                    "@lang('Something went wrong.')");
                return;
            }

            $('.submit_button').prop('type', 'button');
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){

                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();

                    if(!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                    } else if(!$.isEmptyObject(data.successMsg)) {

                        toastr.success(data.successMsg);
                        window.location = "{{ url()->previous() }}";
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

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '' ;
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
    </script>
@endpush
