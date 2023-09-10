<?php

namespace App\Utils\Manufacturing;

use App\Models\Product;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ProcessUtil
{
    public function processTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $process = DB::table('processes')
            ->leftJoin('products', 'processes.product_id', 'products.id')
            ->leftJoin('product_variants', 'processes.variant_id', 'product_variants.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as subCate', 'products.parent_category_id', 'subCate.id')
            ->leftJoin('units', 'processes.unit_id', 'units.id')
            ->select(
                'processes.*',
                'products.name as p_name',
                'product_variants.variant_name as v_name',
                'categories.name as cate_name',
                'subCate.name as sub_cate_name',
                'subCate.name as sub_cate_name',
                'units.name as u_name',
            )->orderBy('processes.id', 'desc')->get();

        return DataTables::of($process)
            ->addColumn('multiple_update', function ($row) {
                return '<input id="' . $row->id . '" class="data_id sorting_disabled" type="checkbox" name="process_ids[]" value="' . $row->id . '"/>';
            })
            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';


                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a id="view" class="dropdown-item" href="' . route('manufacturing.process.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> '.__("View").'</a>';

                    if (auth()->user()->permission->manufacturing['process_edit'] == '1') :
                        $html .= '<a class="dropdown-item" href="' . route('manufacturing.process.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i>'.__("Edit").' </a>';
                    endif;

                    if (auth()->user()->permission->manufacturing['process_delete'] == '1') :
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('manufacturing.process.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i>'.__("Delete").' </a>';
                    endif;




                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('product', function ($row) {
                return $row->p_name . ' ' . $row->v_name;
            })
            ->editColumn('wastage_percent', function ($row) {
                return $row->wastage_percent . '%';
            })
            ->editColumn('total_output_qty', function ($row) {
                $wastage = $row->total_output_qty / 100 * $row->wastage_percent;
                $qtyWithWastage = $row->total_output_qty - $wastage;
                return bcadd($qtyWithWastage, 0, 2) . ' ' . $row->u_name;
            })
            ->editColumn('total_ingredient_cost', function ($row) use ($generalSettings) {
                return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_ingredient_cost;
            })
            ->editColumn('production_cost', function ($row) use ($generalSettings) {
                return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->production_cost;
            })
            ->editColumn('total_cost', function ($row) use ($generalSettings) {
                return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_cost;
            })
            ->rawColumns(['multiple_update', 'action', 'product', 'wastage_percent', 'total_output_qty', 'total_ingredient_cost', 'production_cost', 'total_cost'])
            ->make(true);
    }

    public function getProcessableProductForCreate($request)
    {
        $product = [];
        $productAndVariantId = explode('-', $request->product_id);
        $product_id = $productAndVariantId[0];
        $variant_id = $productAndVariantId[1];
        if ($variant_id != 'NULL') {
            $v_product = DB::table('product_variants')->where('product_variants.id', $variant_id)
                ->leftJoin('products', 'product_variants.product_id', 'products.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->select(
                    'product_variants.id as v_id',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'products.id as p_id',
                    'products.name',
                    'products.id as unit_id',
                    'products.product_code',
                )->first();
            $product['p_id'] = $v_product->p_id;
            $product['unit_id'] = $v_product->p_id;
            $product['p_name'] = $v_product->name;
            $product['p_code'] = $v_product->product_code;
            $product['v_id'] = $v_product->v_id;
            $product['v_name'] = $v_product->variant_name;
            $product['v_code'] = $v_product->variant_code;
        } else {
            $s_product = Product::with('unit')->where('id', $product_id)
                ->select('id', 'unit_id','name', 'product_code')
                ->first();
            $product['p_id'] = $s_product->id;
            $product['unit_id'] = $s_product->unit->id;
            $product['p_name'] = $s_product->name;
            $product['p_code'] = $s_product->product_code;
            $product['v_id'] = NULL;
            $product['v_name'] = NULL;
            $product['v_code'] = NULL;
        }
        return $product;
    }
}
