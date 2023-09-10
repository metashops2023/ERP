@foreach ($ingredients as $ingredient)
    @php
        $stock = 0;
        if ($ingredient->variant_id) {
            if ($warehouseId != 'null') {
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
            if ($warehouseId != 'null') {
                $productWarehouse = DB::table('product_warehouses')->where('warehouse_id', $warehouseId)
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
            <span class="product_name">{{ $ingredient->p_name }}</span><br>
            <span class="product_variant">{{ $ingredient->v_name }}</span>  
            <input value="{{ $ingredient->p_id }}" type="hidden" class="productId-{{ $ingredient->p_id }}" id="product_id" name="product_ids[]">
            <input value="{{ $ingredient->v_id ? $ingredient->v_id : 'noid' }}" type="hidden" id="variant_id" name="variant_ids[]">
            <input value="{{ $ingredient->u_id }}" name="unit_ids[]" type="hidden" step="any" id="unit_id">
            <input value="{{ bcadd($stock, 0 ,2) }}" type="hidden" step="any" data-unit="{{ $ingredient->u_name }}" id="qty_limit">
        </td>

        <td>
            <div class="input-group p-2">
                <input value="{{ $ingredient->final_qty }}" required name="input_quantities[]" type="number" class="form-control text-center" id="input_quantity">
                <input value="{{ $ingredient->final_qty }}" name="parameter_input_quantities[]" type="hidden" id="parameter_input_quantity">
                <div class="input-group-prepend">
                    <span class="input-group-text input-group-text-custom">{{ $ingredient->u_name }}</span>
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