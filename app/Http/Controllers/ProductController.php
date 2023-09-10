<?php

namespace App\Http\Controllers;

use App\Models\AdminUserBranch;
use App\Models\Branch;
use App\Models\Product;
use App\Utils\ProductUtil;
use App\Models\BulkVariant;
use Illuminate\Support\Str;
use App\Models\ComboProduct;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\General_setting;
use App\Models\PurchaseProduct;
use App\Models\SupplierProduct;
use App\Utils\ProductStockUtil;
use App\Models\PriceGroupProduct;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOpeningStock;
use App\Models\ProductBranchVariant;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected $productUtil;
    protected $productStockUtil;
    protected $userActivityLogUtil;
    public function __construct(
        ProductUtil $productUtil,
        ProductStockUtil $productStockUtil,
        UserActivityLogUtil $userActivityLogUtil
    ) {
        $this->productUtil = $productUtil;
        $this->productStockUtil = $productStockUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    // index view
    public function allProduct(Request $request)
    {
        if (auth()->user()->permission->product['product_all'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->productUtil->productListTable($request);
        }

        $categories = getUserCategories();
        $brands = getUserBrands();
        $units = getUserUnits();
        $taxes = getUserTaxes();
        $branches = getUserBranches();

        return view('product.products.index_v2', compact('categories', 'brands', 'units', 'taxes', 'branches'));
    }

    // Add product view
    public function create(Request $request)
    {
        if (auth()->user()->permission->product['product_add'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $products = DB::table('product_branches')
                ->leftJoin('products', 'product_branches.product_id', 'products.id')
                ->select('products.id', 'products.name', 'products.product_cost', 'products.product_price')
                ->where('product_branches.branch_id', auth()->user()->branch_id)
                ->orderBy('products.id', 'desc');

                    return DataTables::of($products)
                    ->addColumn('action', fn ($row) => '<a href="' . route('products.edit', [$row->id]) . '" class="action-btn c-edit" title="'.__("Edit").'"><span class="fas fa-edit"></span></a>')
                    ->editColumn('name', fn ($row) => Str::limit($row->name, 17))
                    ->rawColumns(['action'])->make(true);

        }

        $categories = getUserCategories();
        $brands = getUserBrands();
        $warranties = getUserWarranties();
        $units = getUserUnits();
        $taxes = getUserTaxes();
        $branches = getUserBranches();
        return view('product.products.create_v2', compact('units', 'categories', 'brands', 'warranties', 'taxes', 'branches'));
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                // 'code' => 'required|unique:products,product_code',
                'code' => 'unique:products,product_code',
                'unit_id' => 'required',
                'photo' => 'sometimes|image|max:2048',
                'category_id' => 'required',
                'barcode_type' => 'required',
                'brand_id' => 'required',
                'warranty_id' => 'required',
                'branch_ids' => 'required',
                // 'tax_id' => 'required',
                // 'tax_type' => 'required',
                'product_condition' => 'required',
                'image.*' => 'sometimes|image|max:2048',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
                'category_id.required' => 'Category field is required',
                'brand_id.required' => 'Brand field is required',
                'warranty_id.required' => 'Warranty field is required',
                // 'tax_id.required' => 'Tax field is required',
                // 'tax_type.required' => 'Tax type field is required',
                'branch_ids.required' => 'Business Location field is required.'
            ]
        );

        // if (isset($request->branch_count)) {

        //     $this->validate(
        //         $request,
        //         ['branch_ids' => 'required'],
        //         ['branch_ids.required' => 'Business Location field is required.']
        //     );
        // }

        $addProduct = new Product();

        $tax_id = NULL;

        if ($request->tax_id) {

            $tax_id = explode('-', $request->tax_id)[0];
        }

        $addProduct->admin_user_id = auth()->user()->id;
        $addProduct->type = $request->type;
        $addProduct->name = $request->name;
        $addProduct->product_code = $request->code ? $request->code : $request->auto_generated_code;
        $addProduct->category_id = $request->category_id;
        $addProduct->parent_category_id = $request->child_category_id;
        $addProduct->brand_id = $request->brand_id;
        $addProduct->unit_id = $request->unit_id;
        $addProduct->alert_quantity = $request->alert_quantity;
        $addProduct->tax_id = $tax_id;
        $addProduct->tax_type = isset($request->tax_type) ? $request->tax_type : 1;
        $addProduct->expire_date = $request->expired_date ? $request->expired_date : NULL;
        $addProduct->product_condition = $request->product_condition;
        $addProduct->is_show_in_ecom = isset($request->is_show_in_ecom) ? 1 : 0;
        $addProduct->is_for_sale = isset($request->is_not_for_sale) ? 0 : 1;
        $addProduct->is_show_emi_on_pos = isset($request->is_show_emi_on_pos) ? 1 : 0;
        $addProduct->is_manage_stock = isset($request->is_manage_stock) ? 1 : 0;
        $addProduct->product_details = $request->product_details;
        $addProduct->is_purchased = 0;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->weight = $request->weight;
        $addProduct->custom_field_1 = $request->custom_field_1;
        $addProduct->custom_field_2 = $request->custom_field_2;
        $addProduct->custom_field_3 = $request->custom_field_3;

        if ($request->file('image')) {

            if (count($request->file('image')) > 4) {

                return response()->json(['errorMsg' =>  __('You can upload only 2 product images.')]);
            }
        }

        if ($request->file('image')) {

            if (count($request->file('image')) > 0) {

                $addProduct->save();

                foreach ($request->file('image') as $image) {

                    $productImage = $image;
                    $productImageName = uniqid() . '.' . $productImage->getClientOriginalExtension();
                    Image::make($productImage)->resize(600, 600)->save('uploads/product/' . $productImageName);
                    $addProductImage = new ProductImage();
                    $addProductImage->product_id = $addProduct->id;
                    $addProductImage->image = $productImageName;
                    $addProductImage->save();
                }
            }
        }

        if ($request->type == 1) {

            $this->validate(
                $request,
                [
                    'profit' => 'required',
                    'product_price' => 'required',
                    'product_cost' => 'required',
                    'product_cost_with_tax' => 'required',
                ],
            );

            $addProduct->product_cost = $request->product_cost;
            $addProduct->profit = $request->profit ? $request->profit : 0.00;
            $addProduct->product_cost_with_tax = $request->product_cost_with_tax;
            $addProduct->product_price = $request->product_price;

            if ($request->file('photo')) {

                $productThumbnailPhoto = $request->file('photo');
                $productThumbnailName = uniqid() . '.' . $productThumbnailPhoto->getClientOriginalExtension();
                Image::make($productThumbnailPhoto)->resize(600, 600)->save('uploads/product/thumbnail/' . $productThumbnailName);
                $addProduct->thumbnail_photo = $productThumbnailName;
            }

            if (isset($request->is_variant)) {

                $addProduct->is_variant = 1;

                if ($request->variant_combinations == null) {

                    return response()->json(['errorMsg' => __('You have selected variant option but there is no variant at all.')]);
                }

                $this->validate($request, ['variant_image.*' => 'sometimes|image|max:2048',],);

                $addProduct->save();

                $index = 0;
                foreach ($request->variant_combinations as $value) {

                    $addVariant = new ProductVariant();
                    $addVariant->product_id = $addProduct->id;
                    $addVariant->variant_name = $value;
                    $addVariant->variant_code = $request->variant_codes[$index];
                    $addVariant->variant_cost = $request->variant_costings[$index];
                    $addVariant->variant_cost_with_tax = $request->variant_costings_with_tax[$index];
                    $addVariant->variant_profit = $request->variant_profits[$index];
                    $addVariant->variant_price = $request->variant_prices_exc_tax[$index];

                    if (isset($request->variant_image[$index])) {

                        $variantImage = $request->variant_image[$index];
                        $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();
                        Image::make($variantImage)->resize(250, 250)->save('uploads/product/variant_image/' . $variantImageName);
                        $addVariant->variant_image = $variantImageName;
                    }

                    $addVariant->save();

                    $index++;
                }
            } else {

                $addProduct->save();
            }
        }

        if ($request->type == 2) {

            if ($request->product_ids == null) {

                return response()->json(['errorMsg' => __('You have selected combo product but there is no product at all')]);
            }

            $addProduct->is_combo = 1;
            $addProduct->profit = $request->profit ? $request->profit : 0.00;
            $addProduct->combo_price = $request->combo_price;
            $addProduct->product_price = $request->combo_price;
            $addProduct->save();

            $productIds = $request->product_ids;
            $combo_quantities = $request->combo_quantities;
            $productVariantIds = $request->variant_ids;
            $index = 0;

            foreach ($productIds as $id) {

                $addComboProducts = new ComboProduct();
                $addComboProducts->product_id = $addProduct->id;
                $addComboProducts->combo_product_id = $id;
                $addComboProducts->quantity = $combo_quantities[$index];
                $addComboProducts->product_variant_id = $productVariantIds[$index] !== 'noid' ? $productVariantIds[$index] : NULL;
                $addComboProducts->save();
                $index++;
            }
        }

        if ($addProduct) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 26, data_obj: $addProduct);
        }

        $this->productUtil->addOrUpdateProductInBranchAndUpdateStatus($request, $addProduct->id);


            session()->flash('successMsg', __('Product create Successfully'));
            return response()->json( __('Product create Successfully'));


    }

    public function view($productId)
    {
        $product = Product::with([
            'category',
            'subCategory',
            'tax',
            'unit:id,name,code_name',
            'brand',
            'ComboProducts',
            'ComboProducts.parentProduct',
            'ComboProducts.parentProduct.tax',
            'ComboProducts.product_variant',
            'product_variants',
        ])->where('id', $productId)->first();

        $own_branch_stocks = DB::table('product_branches')
            ->where('product_branches.branch_id', auth()->user()->branch_id)
            ->where('product_branches.product_id', $productId)
            ->leftJoin('branches', 'product_branches.branch_id', 'branches.id')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->select(
                'branches.name as b_name',
                'branches.branch_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_branches.product_quantity',
                'product_branches.total_sale',
                'product_branches.total_purchased',
                'product_branches.total_opening_stock',
                'product_branches.total_adjusted',
                'product_branches.total_transferred',
                'product_branches.total_received',
                'product_branches.total_sale_return',
                'product_branches.total_purchase_return',
                'product_branch_variants.variant_quantity',
                'product_branch_variants.total_sale as v_total_sale',
                'product_branch_variants.total_purchased as v_total_purchased',
                'product_branch_variants.total_opening_stock as v_total_opening_stock',
                'product_branch_variants.total_adjusted as v_total_adjusted',
                'product_branch_variants.total_transferred as v_total_transferred',
                'product_branch_variants.total_received as v_total_received',
                'product_branch_variants.total_sale_return as v_total_sale_return',
                'product_branch_variants.total_purchase_return as v_total_purchase_return',
            )->get();

        $another_branch_stocks = DB::table('product_branches')
            ->where('product_branches.product_id', $productId)
            ->leftJoin('branches', 'product_branches.branch_id', 'branches.id')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            // ->where('product_branches.branch_id', '!=', auth()->user()->branch_id)
            ->select(
                'product_branches.branch_id',
                'branches.name as b_name',
                'branches.name as b_name',
                'branches.branch_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_branches.product_quantity',
                'product_branches.total_sale',
                'product_branch_variants.variant_quantity',
                'product_branch_variants.total_sale as v_total_sale',
            )->get();

        $own_warehouse_stocks = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->where('warehouse_branches.is_global', 0)
            ->leftJoin('product_warehouses', 'warehouse_branches.warehouse_id', 'product_warehouses.warehouse_id')
            ->leftJoin('warehouses', 'product_warehouses.warehouse_id', 'warehouses.id')
            ->leftJoin('product_warehouse_variants', 'product_warehouses.id', 'product_warehouse_variants.product_warehouse_id')
            ->leftJoin('product_variants', 'product_warehouse_variants.product_variant_id', 'product_variants.id')
            ->where('product_warehouses.product_id', $productId)
            ->select(
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_warehouses.product_quantity',
                'product_warehouses.total_purchased',
                'product_warehouses.total_adjusted',
                'product_warehouses.total_transferred',
                'product_warehouses.total_received',
                'product_warehouses.total_sale_return',
                'product_warehouses.total_purchase_return',
                'product_warehouse_variants.variant_quantity',
                'product_warehouse_variants.total_purchased as v_total_purchased',
                'product_warehouse_variants.total_adjusted as v_total_adjusted',
                'product_warehouse_variants.total_transferred as v_total_transferred',
                'product_warehouse_variants.total_received as v_total_received',
                'product_warehouse_variants.total_sale_return as v_',
                'product_warehouse_variants.total_purchase_return as v_total_purchase_return',
            )->get();

        $global_warehouse_stocks = DB::table('warehouse_branches')
            ->where('warehouse_branches.is_global', 1)
            ->leftJoin('product_warehouses', 'warehouse_branches.warehouse_id', 'product_warehouses.warehouse_id')
            ->where('product_warehouses.product_id', $productId)
            ->leftJoin('warehouses', 'product_warehouses.warehouse_id', 'warehouses.id')
            ->leftJoin('product_warehouse_variants', 'product_warehouses.id', 'product_warehouse_variants.product_warehouse_id')
            ->leftJoin('product_variants', 'product_warehouse_variants.product_variant_id', 'product_variants.id')
            ->select(
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_warehouses.product_quantity',
                'product_warehouses.total_purchased',
                'product_warehouses.total_adjusted',
                'product_warehouses.total_transferred',
                'product_warehouses.total_received',
                'product_warehouses.total_sale_return',
                'product_warehouses.total_purchase_return',
                'product_warehouse_variants.variant_quantity',
                'product_warehouse_variants.total_purchased as v_total_purchased',
                'product_warehouse_variants.total_adjusted as v_total_adjusted',
                'product_warehouse_variants.total_transferred as v_total_transferred',
                'product_warehouse_variants.total_received as v_total_received',
                'product_warehouse_variants.total_sale_return as v_',
                'product_warehouse_variants.total_purchase_return as v_total_purchase_return',
            )->get();

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);
        return view('product.products.ajax_view.product_details_view', compact(
            'product',
            'price_groups',
            'own_branch_stocks',
            'another_branch_stocks',
            'own_warehouse_stocks',
            'global_warehouse_stocks'
        ));
    }

    //update opening stock
    public function openingStockUpdate(Request $request)
    {
        $branch_id = auth()->user()->branch_id;

        // Add Opening Stock and update branch stock
        $index = 0;
        foreach ($request->product_ids as $product_id) {

            $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;
            $openingStock = ProductOpeningStock::where('branch_id', $branch_id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();

            if ($openingStock) {

                $this->productUtil->updateOpeningStock(
                    openingStock: $openingStock,
                    unit_cost_inc_tax: $request->unit_costs_inc_tax[$index],
                    quantity: $request->quantities[$index],
                    subtotal: $request->subtotals[$index]
                );
            } else {

                $this->productUtil->addOpeningStock(
                    branch_id: $branch_id,
                    product_id: $product_id,
                    variant_id: $variant_id,
                    unit_cost_inc_tax: $request->unit_costs_inc_tax[$index],
                    quantity: $request->quantities[$index],
                    subtotal: $request->subtotals[$index]
                );
            }

            $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);
            $this->productStockUtil->adjustBranchStock($product_id, $variant_id, auth()->user()->branch_id);

            $index++;
        }


            return response()->json(__('Successfully product opening stock is added'));


    }

    // Get opening stock
    public function openingStock($productId)
    {
        $products = DB::table('products')->where('products.id', $productId)
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->select(
                'products.id as p_id',
                'products.name as p_name',
                'products.product_cost as p_cost',
                'products.product_cost_with_tax as p_cost_inc_tax',
                'units.code_name as u_code',
                'product_variants.id as v_id',
                'product_variants.variant_name as v_name',
                'product_variants.variant_cost as v_cost',
                'product_variants.variant_cost_with_tax as v_cost_inc_tax',
            )->get();

        return view('product.products.ajax_view.opening_stock_modal_view', compact('products'));
    }

    public function addPriceGroup($productId, $type)
    {
        $priceGroups = DB::table('price_groups')->where('status', 'Active')->get();
        $product_name = DB::table('products')->where('id', $productId)->first(['name', 'product_code']);
        $products = DB::table('products')
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
            ->where('products.id', $productId)
            ->select(
                'products.id as p_id',
                'products.is_variant',
                'products.name',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_price',
                'product_variants.id as v_id',
                'taxes.tax_percent'
            )->get();

        return view('product.products.add_price_group', compact('products', 'type', 'priceGroups', 'product_name'));
    }

    public function savePriceGroup(Request $request)
    {
        $variant_ids = $request->variant_ids;
        $index = 0;
        foreach ($request->product_ids as $product_id) {

            foreach ($request->group_prices as $key => $group_price) {

                (float)$__group_price = $group_price[$product_id][$variant_ids[$index]];
                $__variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $updatePriceGroup = PriceGroupProduct::where('price_group_id', $key)->where('product_id', $product_id)->where('variant_id', $__variant_id)->first();

                if ($updatePriceGroup) {

                    $updatePriceGroup->price = $__group_price != null ? $__group_price : NULL;
                    $updatePriceGroup->save();
                } else {

                    $addPriceGroup = new PriceGroupProduct();
                    $addPriceGroup->price_group_id = $key;
                    $addPriceGroup->product_id = $product_id;
                    $addPriceGroup->variant_id = $__variant_id;
                    $addPriceGroup->price = $__group_price != null ? $__group_price : NULL;
                    $addPriceGroup->save();
                }
            }
            $index++;
        }

        if ($request->action_type == 'save') {



            return response()->json(['saveMessage' =>  __('Product price group updated Successfully')]);


        } else {

            return response()->json(['saveMessage' =>  __('Product price group updated Successfully')]);
        }
    }

    // edit view of product
    public function edit($productId)
    {
        $product = DB::table('products')->where('products.id', $productId)
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
            ->select('products.*', 'taxes.tax_percent')
            ->first();

        $categories = getUserCategories();
        $brands = getUserBrands();
        $warranties = getUserWarranties();
        $units = getUserUnits();
        $taxes = getUserTaxes();
        $branches = getUserBranches();

        $productBranches = '';

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $productBranches = DB::table('product_branches')->where('product_id', $productId)->where('status', 1)->get();
        }

        // $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);

        return view('product.products.edit_v2', compact('product', 'categories', 'units', 'brands', 'taxes', 'warranties', 'productBranches', 'branches'));
    }

    // Get product variants
    public function getProductVariants($productId)
    {
        $variants = DB::table('product_variants')->where('product_id', $productId)->get();
        return response()->json($variants);
    }

    public function getComboProducts($productId)
    {
        $comboProducts = ComboProduct::with(['parentProduct', 'parentProduct.tax', 'product_variant'])->where('product_id', $productId)->get();
        return response()->json($comboProducts);
    }

    // product update method
    public function update(Request $request, $productId)
    {
        $updateProduct = Product::with(['product_variants', 'ComboProducts'])->where('id', $productId)->first();
        $tax_id = NULL;

        if ($request->tax_id) {

            $tax_id = explode('-', $request->tax_id)[0];
        }

        $this->validate(
            $request,
            [
                'name' => 'required',
                'unit_id' => 'required',
                'photo' => 'sometimes|image|max:2048',
                'image.*' => 'sometimes|image|max:2048',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
            ]
        );

        if (isset($request->branch_count)) {

            $this->validate(
                $request,
                ['branch_ids' => 'required'],
                ['branch_ids.required' => 'Business Location field is required.']
            );
        }

        $updateProduct->name = $request->name;
        $updateProduct->product_code = $request->code ? $request->code : $request->auto_generated_code;
        $updateProduct->category_id = $request->category_id;
        $updateProduct->parent_category_id = $request->child_category_id;
        $updateProduct->brand_id = $request->brand_id;
        $updateProduct->unit_id = $request->unit_id;
        $updateProduct->alert_quantity = $request->alert_quantity;
        $updateProduct->tax_id = $tax_id;
        $updateProduct->tax_type = $request->tax_type;
        $updateProduct->expire_date = $request->expired_date ? $request->expired_date : NULL;
        $updateProduct->product_condition = $request->product_condition;
        $updateProduct->is_show_in_ecom = isset($request->is_show_in_ecom) ? 1 : 0;
        $updateProduct->is_for_sale = isset($request->is_not_for_sale) ? 0 : 1;
        $updateProduct->is_show_emi_on_pos = isset($request->is_show_emi_on_pos) ? 1 : 0;
        $updateProduct->is_manage_stock = isset($request->is_manage_stock) ? 1 : 0;
        $updateProduct->product_details = $request->product_details;
        $updateProduct->barcode_type = $request->barcode_type;
        $updateProduct->warranty_id = $request->warranty_id;
        $updateProduct->weight = $request->weight;
        $updateProduct->custom_field_1 = $request->custom_field_1;
        $updateProduct->custom_field_2 = $request->custom_field_2;
        $updateProduct->custom_field_3 = $request->custom_field_3;

        if ($request->file('image')) {

            if (count($request->file('image')) > 0) {

                foreach ($request->file('image') as $image) {

                    $productImage = $image;
                    $productImageName = uniqid() . '.' . $productImage->getClientOriginalExtension();
                    Image::make($productImage)->resize(250, 250)->save('uploads/product/' . $productImageName);
                    $addProductImage = new ProductImage();
                    $addProductImage->product_id = $updateProduct->id;
                    $addProductImage->image = $productImageName;
                    $addProductImage->save();
                }
            }
        }

        if ($updateProduct->type == 1) {

            $this->validate(
                $request,
                [
                    'profit' => 'required',
                    'product_price' => 'required',
                    'product_cost' => 'required',
                    'product_cost_with_tax' => 'required',
                ],
            );

            $updateProduct->product_cost = $request->product_cost;
            $updateProduct->profit = $request->profit ? $request->profit : 0.00;
            $updateProduct->product_cost_with_tax = $request->product_cost_with_tax;
            $updateProduct->product_price = $request->product_price;

            // Upload product thumbnail
            if ($request->file('photo')) {

                if ($updateProduct->thumbnail_photo != 'default.png') {

                    if (file_exists(public_path('uploads/product/thumbnail/' . $updateProduct->thumbnail_photo))) {

                        unlink(public_path('uploads/product/thumbnail/' . $updateProduct->thumbnail_photo));
                    }
                }

                $productThumbnailPhoto = $request->file('photo');
                $productThumbnailName = uniqid() . '.' . $productThumbnailPhoto->getClientOriginalExtension();
                Image::make($productThumbnailPhoto)->resize(250, 250)->save('uploads/product/thumbnail/' . $productThumbnailName);
                $updateProduct->thumbnail_photo = $productThumbnailName;
            }

            if ($updateProduct->is_variant == 1) {

                if ($request->variant_combinations == null) {

                    return response()->json(['errorMsg' => __('You have selected variant option but there is no variant at all.')]);
                }

                foreach ($updateProduct->product_variants as $product_variant) {

                    $product_variant->delete_in_update = 1;
                    $product_variant->save();
                }

                $this->validate(
                    $request,
                    [
                        'variant_image.*' => 'sometimes|image|max:2048',
                    ],
                );

                $updateProduct->save();

                $index = 0;
                foreach ($request->variant_combinations as $value) {

                    $updateVariant = ProductVariant::where('id', $request->variant_ids[$index])->first();

                    if ($updateVariant) {

                        $updateVariant->variant_name = $value;
                        $updateVariant->variant_code = $request->variant_codes[$index];
                        $updateVariant->variant_cost = $request->variant_costings[$index];
                        $updateVariant->variant_cost_with_tax = $request->variant_costings_with_tax[$index];
                        $updateVariant->variant_profit = $request->variant_profits[$index];
                        $updateVariant->variant_price = $request->variant_prices_exc_tax[$index];
                        $updateVariant->delete_in_update = 0;

                        if (isset($variant_images[$index])) {

                            if ($updateVariant->variant_image != null) {

                                if (file_exists(public_path('uploads/product/variant_image/' . $updateVariant->variant_image))) {

                                    unlink(public_path('uploads/product/thumbnail/' . $updateVariant->variant_image));
                                }
                            }

                            $variantImage = $request->variant_images[$index];
                            $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();
                            Image::make($variantImage)->resize(250, 250)->save('uploads/product/variant_image/' . $variantImageName);
                            $updateVariant->variant_image = $variantImageName;
                        }

                        $updateVariant->save();
                    } else {

                        $addVariant = new ProductVariant();
                        $addVariant->product_id = $updateProduct->id;
                        $addVariant->variant_name = $value;
                        $addVariant->variant_code = $request->variant_codes[$index];
                        $addVariant->variant_cost = $request->variant_costings[$index];
                        $addVariant->variant_cost_with_tax = $request->variant_costings_with_tax[$index];
                        $addVariant->variant_profit = $request->variant_profits[$index];
                        $addVariant->variant_price = $request->variant_prices_exc_tax[$index];

                        if (isset($request->variant_images[$index])) {

                            $variantImage = $request->variant_images[$index];
                            $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();
                            Image::make($variantImage)->resize(250, 250)->save('uploads/product/variant_image/' . $variantImageName);
                            $addVariant->variant_image = $variantImageName;
                        }

                        $addVariant->save();
                    }

                    $index++;
                }

                $deleteNotFoundVariants = ProductVariant::where('delete_in_update', 1)->get();

                foreach ($deleteNotFoundVariants as $deleteNotFoundVariant) {

                    if ($deleteNotFoundVariant->variant_image != null) {

                        if (file_exists(public_path('uploads/product/variant_image/' . $updateVariant->variant_image))) {

                            unlink(public_path('uploads/product/thumbnail/' . $updateVariant->variant_image));
                        }
                    }

                    $deleteNotFoundVariant->delete();
                }
            } else {

                $updateProduct->save();
            }
        }

        if ($updateProduct->type == 2) {

            if ($request->product_ids == null) {

                return response()->json(['errorMsg' => __('You have selected combo product but there is no product at all')]);
            }

            foreach ($updateProduct->ComboProducts as $ComboProduct) {

                $ComboProduct->delete_in_update = 1;
                $ComboProduct->save();
            }

            $updateProduct->profit = $request->profit ? $request->profit : 0.00;
            $updateProduct->product_price = $request->combo_price;
            $updateProduct->combo_price = $request->combo_price;
            $updateProduct->save();

            $combo_ids = $request->combo_ids;
            $productIds = $request->product_ids;
            $combo_quantities = $request->combo_quantities;
            $productVariantIds = $request->variant_ids;
            $index = 0;

            foreach ($productIds as $id) {

                $updateComboProduct = ComboProduct::where('id', $combo_ids[$index])->first();
                if ($updateComboProduct) {

                    $updateComboProduct->quantity = $combo_quantities[$index];
                    $updateComboProduct->delete_in_update = 0;
                    $updateComboProduct->save();
                } else {

                    $addComboProducts = new ComboProduct();
                    $addComboProducts->product_id = $updateProduct->id;
                    $addComboProducts->combo_product_id = $id;
                    $addComboProducts->quantity = $combo_quantities[$index];
                    $addComboProducts->product_variant_id = $productVariantIds[$index] !== 'noid' ? $productVariantIds[$index] : NULL;
                    $addComboProducts->save();
                }

                $index++;
            }
        }

        $deleteNotFoundComboProducts = ComboProduct::where('delete_in_update', 1)->get();

        foreach ($deleteNotFoundComboProducts as $deleteNotFoundComboProduct) {

            $deleteNotFoundComboProduct->delete();
        }

        if ($updateProduct) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 26, data_obj: $updateProduct);
        }

        $this->productUtil->addOrUpdateProductInBranchAndUpdateStatus($request, $updateProduct->id);

        session()->flash('successMsg', __('Product updated Successfully'));
        return response()->json( __('Product updated Successfully'));

    }

    // delete product
    public function delete(Request $request, $productId)
    {
        $deleteProduct = Product::with(
            [
                'product_images',
                'product_variants',
                'purchase_products',
                'sale_products',
                'order_products',
                'transfer_to_branch_products',
                'transfer_to_warehouse_products',
            ]
        )->where('id', $productId)->first();

        if (!is_null($deleteProduct)) {

            if ($deleteProduct->thumbnail_photo !== 'default.png') {

                if (file_exists(public_path('uploads/product/thumbnail/' . $deleteProduct->thumbnail_photo))) {

                    unlink(public_path('uploads/product/thumbnail/' . $deleteProduct->thumbnail_photo));
                }
            }

            if ($deleteProduct->product_images->count() > 0) {

                foreach ($deleteProduct->product_images as $product_image) {

                    if (file_exists(public_path('uploads/product/' . $product_image->image))) {

                        unlink(public_path('uploads/product/' . $product_image->image));
                    }
                }
            }

            if ($deleteProduct->product_variants->count() > 0) {

                foreach ($deleteProduct->product_variants as $product_variant) {

                    if ($product_variant->variant_image) {

                        if (file_exists(public_path('uploads/product/variant_image/' . $product_variant->variant_image))) {

                            unlink(public_path('uploads/product/variant_image/' . $product_variant->variant_image));
                        }
                    }
                }
            }

            if ($deleteProduct) {

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 26, data_obj: $deleteProduct);
            }

            $deleteProduct->delete();
        }

            return response()->json( __('Product deleted Successfully'));


    }

    // multiple delete method
    public function multipleDelete(Request $request)
    {
        // dd($request);
        if ($request->data_ids == null) {

                return response()->json(['errorMsg' => __('You did not select any product.')]);

        }

        if ($request->action == 'multiple_delete') {


    //         // foreach($request->data_ids as $data_id){
    //         //     $deleteProduct = Product::with(['product_images', 'product_variants'])->where('id', $data_id)->get();
    //         //     if (!is_null($deleteProduct)) {
    //         //         if (!is_null($deleteProduct->thumbnail_photo)){
    //         //         if ($deleteProduct->thumbnail_photo !== 'default.png') {
    //         //             if (file_exists(public_path('uploads/product/thumbnail/'.$deleteProduct->thumbnail_photo))) {
    //         //                 unlink(public_path('uploads/product/thumbnail/'.$deleteProduct->thumbnail_photo));
    //         //             }
    //         //         }}

    //         //         if($deleteProduct->product_images->count() > 0){
    //         //             foreach($deleteProduct->product_images as $product_image){
    //         //                 if (file_exists(public_path('uploads/product/'.$product_image->image))) {
    //         //                     unlink(public_path('uploads/product/'.$product_image->image));
    //         //                 }
    //         //             }
    //         //         }

    //         //         if($deleteProduct->product_variants->count() > 0){
    //         //             foreach($deleteProduct->product_variants as $product_variant){
    //         //                 if($product_variant->variant_image){
    //         //                     if (file_exists(public_path('uploads/product/variant_image/'.$product_variant->variant_image))) {
    //         //                         unlink(public_path('uploads/product/variant_image/'.$product_variant->variant_image));
    //         //                     }
    //         //                 }
    //         //             }
    //         //         }
    //         //         $deleteProduct->delete();
    //         //     }
    //         // }

    //         // return response()->json('Multiple delete feature is disabled in this demo');
            foreach ($request->data_ids as $data_id) {

                $productImage = ProductImage::where('product_id', $data_id)->get();
                if ($productImage->count() > 0) {
                    foreach ($productImage as $product_image) {
                        if (file_exists(public_path('uploads/product/' . $product_image->image))) {
                            unlink(public_path('uploads/product/' . $product_image->image));
                        }
                        $product_image->delete();
                    }
                }

                $productVariants = ProductVariant::where('product_id', $data_id)->get();
                if ($productVariants->count() > 0) {
                    foreach ($productVariants as $product_variant) {
                        if ($product_variant->variant_image) {
                            if (file_exists(public_path('uploads/product/variant_image/' . $product_variant->variant_image))) {
                                unlink(public_path('uploads/product/variant_image/' . $product_variant->variant_image));
                            }
                        }
                        $product_variant->delete();
                    }
                }
                $deleteProduct = Product::where('id', $data_id)->first();
                if (!is_null($deleteProduct)) {
                    if (!is_null($deleteProduct->thumbnail_photo)) {
                        if ($deleteProduct->thumbnail_photo !== 'default.png') {
                            if (file_exists(public_path('uploads/product/thumbnail/' . $deleteProduct->thumbnail_photo))) {
                                unlink(public_path('uploads/product/thumbnail/' . $deleteProduct->thumbnail_photo));
                            }
                        }
                    }

                    $deleteProduct->delete();
                }
            }


            return response()->json( __('deleted'));

        } elseif ($request->action == 'multipla_deactive') {

            foreach ($request->data_ids as $data_id) {

                $product = Product::where('id', $data_id)->first();
                $product->status = 0;
                $product->save();
            }

                return response()->json(__('Successfully all selected product status deactivated'));


        }
    }


    // public function multipleDelete(Request $request)
    // {

    //      $del=   DB::table('products')

    //         ->join('product_images', 'products.id', '=', 'product_images.product_id')
    //         ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
    //         ->select('products.*', 'product_images.*', 'product_variants.*')
    //         ->delete();


    //         // foreach ($del as $delete) {

    //         //     $delete->delete();
    //         //             };

    //           return response()->json([
    //             'message'=>"Posts Deleted successfully."
    //         ],200);

    // }

    // Change product status method
    public function changeStatus($productId)
    {
        $statusChange = Product::where('id', $productId)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Successfully Product is deactivated'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Successfully Product is activated'));

        }
    }

    //Get all form variant by ajax request
    public function getAllFormVariants()
    {
        $variants = BulkVariant::with(['bulk_variant_child'])->get();
        return response()->json($variants);
    }

    public function searchProduct($productCode)
    {
        $product = Product::with(['product_variants', 'tax', 'unit'])->where('product_code', $productCode)->first();
        if ($product) {

            return response()->json(['product' => $product]);
        } else {

            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')->where('variant_code', $productCode)->first();
            return response()->json(['variant_product' => $variant_product]);
        }
    }

    public function chackPurchaseAndGenerateBarcode($productId)
    {
        $supplierProducts = SupplierProduct::where('product_id', $productId)->get();

        if ($supplierProducts->count() > 0) {

            return response()->json(route('products.generate.product.barcode', $productId));
        } else {

            return response()->json(['errorMsg' => __('This product yet to be purchased.')]);

            //     return response()->json(['errorMsg' => __('This product yet to be purchased')]);



        }
    }

    // Add Category from add product
    public function addCategory(Request $request)
    {
        return $this->productUtil->addQuickCategory($request);
    }

    // Add brand from add product
    public function addBrand(Request $request)
    {
        return $this->productUtil->addQuickBrand($request);
    }

    // Add brand from add product
    public function addUnit(Request $request)
    {
        return $this->productUtil->addQuickUnit($request);
    }

    // Add warranty from add product
    public function addWarranty(Request $request)
    {
        return $this->productUtil->addQuickWarranty($request);
    }

    public function getFormPart($type)
    {
        $type = $type;
        $variants = BulkVariant::with(['bulk_variant_child'])->get();
        $taxes = DB::table('taxes')->get(['id', 'tax_name', 'tax_percent']);
        return view('product.products.ajax_view.form_part', compact('type', 'variants', 'taxes'));
    }

    public function settings()
    {
        $units = DB::table('units')->select('id', 'name', 'code_name')->get();
        return view('product.settings.index', compact('units'));
    }

    public function settingsStore(Request $request)
    {
        $updateProductSettings = General_setting::first();

        $productSettings = [
            'product_code_prefix' => $request->product_code_prefix,
            'default_unit_id' => $request->default_unit_id,
            'is_enable_brands' => isset($request->is_enable_brands) ? 1 : 0,
            'is_enable_categories' => isset($request->is_enable_categories) ? 1 : 0,
            'is_enable_sub_categories' => isset($request->is_enable_sub_categories) ? 1 : 0,
            'is_enable_price_tax' => isset($request->is_enable_price_tax) ? 1 : 0,
            'is_enable_warranty' => isset($request->is_enable_warranty) ? 1 : 0,
        ];

        $updateProductSettings->product = json_encode($productSettings);
        $updateProductSettings->save();

            return response()->json(__('Product settings updated successfully'));


    }
}
