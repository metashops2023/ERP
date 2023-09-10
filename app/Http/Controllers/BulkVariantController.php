<?php

namespace App\Http\Controllers;

use App\Models\BulkVariant;
use Illuminate\Http\Request;
use App\Models\BulkVariantChild;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BulkVariantController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    // public function index()
    // {
    //     if (auth()->user()->permission->product['variant'] == '0') {

    //         abort(403, 'Access Forbidden.');
    //     }

    //     return view('product.bulk_variants.index_v2');
    // }

    // public function getAllVariant()
    // {
    //     $variants = BulkVariant::with(['bulk_variant_child'])->get();
    //     return view('product.bulk_variants.ajax_view.variant_list', compact('variants'));
    // }

    public function index(Request $request)
    {
        if (auth()->user()->permission->product['variant'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            if (auth()->user()->role_type == 1) {
                if (isset($request->branch_id) && $request->branch_id != null) {
                    $variants = DB::table('bulk_variants')
                    ->join('bulk_variant_children','bulk_variants.id','=','bulk_variant_id')
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('bulk_variants.id', 'DESC')
                        ->selectRaw('bu bulk_variant_children.child_nam');
                } else {
                    $variants = DB::table('bulk_variants')
                    ->join('bulk_variant_children','bulk_variants.id','=','bulk_variant_id')
                        ->orderBy('bulk_variants.id', 'DESC')
                        ->selectRaw('bulk_variants.*,bulk_variant_children.child_name');
                }
            } else if (auth()->user()->role_type == 2) {
                if (isset($request->branch_id) && $request->branch_id != null) {
                    $variants = DB::table('bulk_variants')
                    ->join('bulk_variant_children','bulk_variants.id','=','bulk_variant_id')
                        ->where('admin_user_id', auth()->user()->id)
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('bulk_variants.id', 'DESC')
                        ->selectRaw('bulk_variants.*,bulk_variant_children.child_name');
                } else {
                    $variants = DB::table('bulk_variants')
                    ->join('bulk_variant_children','bulk_variants.id','=','bulk_variant_id')
                        ->where('admin_user_id', auth()->user()->id)
                        ->orderBy('bulk_variants.id', 'DESC')
                        ->selectRaw('bulk_variants.*,bulk_variant_children.child_name');
                }
            } else {
                $variants = DB::table('bulk_variants')
                ->join('bulk_variant_children','bulk_variants.id','=','bulk_variant_id')
                    ->where('branch_id', auth()->user()->branch_id)
                    ->orderBy('bulk_variants.id', 'DESC')
                    ->selectRaw('bulk_variants.*,bulk_variant_children.child_name');
            }

            // $variants = DB::table('brands')->orderBy('id', 'DESC')->get();
            // $variants = $variants->where('status',1)->get();
            return DataTables::of($variants)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('product.variants.edit', [$row->id]) . '" class="action-btn c-edit edit"><span class="fas fa-edit"></span></a>';
                    // $html .= '<a href="' . route('product.brands.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    if ($row->status == 1) {
                        $html .= '<a class="table-dropdown" title="'.__("Cancel").'" id="change_status" href="' . route('product.variants.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                    } else {
                        $html .= '<a class="table-dropdown" title="'.__("Undo").'" id="change_status" href="' . route('product.variants.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('status', function ($row) {

               if ($row->status == 1) {

                   return '<span class="text-success">Active</span>';
               } else {

                   return '<span class="text-danger">Inactive</span>';
               }
           })
           ->filter(function($query) use($request){
            // dd($request->active);
            if($request->active=="false"){
                $query->where('status',1);
            }else{
            $query->where('status',0);
        }
           })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }


        return view('product.bulk_variants.index_v2');
    }

    public function store(Request $request)
    {
        if (auth()->user()->permission->product['variant'] == '0') {
            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'variant_name' => 'required',
        ]);

        $addVariant = new BulkVariant();
        $addVariant->bulk_variant_name = $request->variant_name;
        $addVariant->save();

        foreach ($request->variant_child as $variant_child) {
            $addVariantChild = new BulkVariantChild();
            $addVariantChild->bulk_variant_id = $addVariant->id;
            $addVariantChild->child_name = $variant_child;
            $addVariantChild->save();
        }

        if ($addVariant) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 24, data_obj: $addVariant);
        }

            return response()->json(__('Variant created Successfully'));


    }

    public function edit($id)
    {
        $data = DB::table('bulk_variants')->where('id', $id)->first();


        return view('product.bulk_variants.ajax_view.edit', compact('data'));
    }

    public function update(Request $request)
    {
        if (auth()->user()->permission->product['variant'] == '0') {
            return response()->json('Access Denied');
        }

        $updateVariant =  BulkVariant::with(['bulk_variant_child'])->where('id', $request->id)->first();
        $updateVariant->bulk_variant_name = $request->variant_name;
        $updateVariant->save();

        $variant_child_ids = $request->variant_child_ids;
        $variant_child = $request->variant_child;

        foreach ($updateVariant->bulk_variant_child as $variantChild) {

            $variantChild->delete_in_update = 1;
            $variantChild->save();
        }

        $index = 0;
        foreach ($variant_child_ids as $variant_child_id) {

            $variant_child_id = $variant_child_id == 'noid' ? NULL : $variant_child_id;
            $updateBulkVariantChild = BulkVariantChild::where('id', $variant_child_id)->where('bulk_variant_id', $updateVariant->id)->first();
            if ($updateBulkVariantChild) {

                $updateBulkVariantChild->child_name = $variant_child[$index];
                $updateBulkVariantChild->delete_in_update = 0;
                $updateBulkVariantChild->save();
            }else {

                $addVariantChild = new BulkVariantChild();
                $addVariantChild->bulk_variant_id = $updateVariant->id;
                $addVariantChild->child_name = $variant_child[$index];
                $addVariantChild->save();
            }
            $index++;
        }

        $deleteBulkVariantChild = BulkVariantChild::where('bulk_variant_id', $updateVariant->id)->where('delete_in_update', 1)->get();
        if ($deleteBulkVariantChild->count() > 0) {

            foreach ($deleteBulkVariantChild as $deleteBulkVariantChild) {

                $deleteBulkVariantChild->delete();
            }
        }

        if ($updateVariant) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 24, data_obj: $updateVariant);
        }

        return response()->json(__('Variant updated Successfully'));

    }

    public function changeStatus($id)
    {
        $statusChange = BulkVariant::where('id', $id)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Variant is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Variant is activated Successfully'));

        }
    }

    public function delete(Request $request, $variantId)
    {
        if (auth()->user()->permission->product['variant'] == '0') {

            return response()->json('Access Denied');
        }

        $deleteVariant = BulkVariant::where('id', $variantId)->first();
        if (!is_null($deleteVariant)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 24, data_obj: $deleteVariant);

            $deleteVariant->delete();

            return response()->json(__('Variant deleted Successfully'));

        }
    }
}
