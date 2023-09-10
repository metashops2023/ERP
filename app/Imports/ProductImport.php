<?php

namespace App\Imports;

use App\Models\Tax;
use App\Models\Unit;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Warranty;
use App\Models\ProductBranch;
use Illuminate\Support\Collection;
use App\Models\ProductOpeningStock;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        //dd($collection);
        $index = 0;
        foreach ($collection as $c) {
            // Generate product code
            $i = 6;
            $a = 0;
            $g_productCode = '';
            while ($a < $i) {
                $g_productCode .= rand(1, 8);
                $a++;
            }

            if ($index != 0) {
                $addProduct = new Product();
                $addProduct->type = 1;
                $addProduct->name = $c[0] ? $c[0] : 'Undefined-' . $index;
                $addProduct->product_code = $c[1] ? $c[1] : $g_productCode;
                $unit = Unit::where('name', $c[2])->first();

                if ($unit) {
                    $addProduct->unit_id = $unit->id;
                } else {
                    $addUnit = new Unit();
                    $addUnit->name = $c[2];
                    $addUnit->code_name =  $c[2];
                    $addUnit->save();
                    $addProduct->unit_id = $addUnit->id;
                }

                if (!empty($c[3])) {
                    $category = Category::where('name', $c[3])->first();
                    $childCategoryId = '';
                    if ($category) {
                        $addProduct->category_id = $category->id;
                        $childCategoryId = $category->id;
                    } else {
                        $addCategory = new Category();
                        $addCategory->name = $c[3];
                        $addCategory->save();
                        $addProduct->category_id = $addCategory->id;
                        $childCategoryId = $addCategory->id;
                        Cache::forget('all-categories');
                        Cache::forget('all-main_categories');
                    }
                }

                if (!empty($c[4])) {
                    $childCategory = Category::where('name', $c[4])->first();
                    if ($childCategory) {
                        $addProduct->parent_category_id = $childCategory->id;
                    } else {
                        $addChildCategory = new Category();
                        $addChildCategory->name = $c[4];
                        $addChildCategory->parent_category_id = $childCategoryId;
                        $addChildCategory->save();
                        $addProduct->parent_category_id = $addChildCategory->id;
                        Cache::forget('all-categories');
                        Cache::forget('all-main_categories');
                    }
                }


                $brand = Brand::where('name', $c[5])->first();
                if ($brand) {
                    $addProduct->brand_id = $brand->id;
                } else {
                    $addBrand = new Brand();
                    $addBrand->name = $c[5];
                    $addBrand->save();
                    $addProduct->brand_id = $addBrand->id;
                    Cache::forget('all-brands');
                }

                $addProduct->barcode_type = $c[6] ? $c[6] : 'CODE128';
                $addProduct->alert_quantity = $c[7] ? $c[7] : 0;
                $addProduct->expire_date = $c[8] ? $c[8] : NULL;

                $warranty = Warranty::where('name', $c[9])->first();
                if ($warranty) {
                    $addProduct->warranty_id = $warranty->id;
                }

                $addProduct->product_details = $c[10] ? $c[10] : NULL;

                if ($c[11]) {
                    $tax = Tax::where('tax_percent', $c[11])->first();
                    if ($tax) {
                        $addProduct->tax_id = $tax->id;
                    } else {
                        $addTax = new Tax();
                        $addTax->tax_name = 'Tax@' . $c[11] . '%';
                        $addTax->tax_percent = $c[11];
                        $addTax->save();
                        $addProduct->tax_id = $addTax->id;
                        Cache::forget('all-taxes');
                    }
                }

                $productCostExcTax = $c[12] ? $c[12] : 0;
                $addProduct->product_cost = $productCostExcTax;

                $taxPercent = $c[11] ? $c[11] : 0;
                $addProduct->product_cost_with_tax = $c[13] ? $c[13] : ($productCostExcTax / 100 * $taxPercent) + $productCostExcTax;
                $addProduct->profit = $c[14] ? $c[14] : 0;
                $addProduct->product_price = $c[15] ? $c[15] : $productCostExcTax;
                $productQty = $c[16] && $c[16] > 0 ? $c[16] : 0;
                $addProduct->quantity = $productQty;
                $addProduct->save();

                $branch = Branch::where('branch_code', $c[17])->first();
                $addOpeningStock = new ProductOpeningStock();
                if ($branch) {
                    $addOpeningStock->branch_id = $branch->id;
                    $addOpeningStock->product_id = $addProduct->id;
                    $addOpeningStock->unit_cost_exc_tax = $productCostExcTax;
                    $addOpeningStock->quantity = $productQty;
                    $addOpeningStock->subtotal = $productQty * $productCostExcTax;
                    $addOpeningStock->save();
                } else {
                    $firstBranch = Branch::orderBy('id', 'asc')->first();
                    $addOpeningStock->branch_id = $firstBranch->id;
                    $addOpeningStock->product_id = $addProduct->id;
                    $addOpeningStock->unit_cost_exc_tax = $productCostExcTax;
                    $addOpeningStock->quantity = $productQty;
                    $addOpeningStock->subtotal = $productQty * $productCostExcTax;
                    $addOpeningStock->save();
                }

                // Add Branch Stock
                if ($branch) {
                    $productBranch = new ProductBranch();
                    $productBranch->branch_id = $branch->id;
                    $productBranch->product_id = $addProduct->id;
                    $productBranch->product_quantity = $productQty;
                    $productBranch->save();
                } else {
                    $firstBranch = Branch::orderBy('id', 'asc')->first();
                    if ($firstBranch) {
                        $productBranch = new ProductBranch();
                        $productBranch->branch_id = $firstBranch->id;
                        $productBranch->product_id = $addProduct->id;
                        $productBranch->product_quantity = $productQty;
                        $productBranch->save();
                    }
                }
            }
            $index++;
        }
        Cache::forget('all-products');
    }
}
