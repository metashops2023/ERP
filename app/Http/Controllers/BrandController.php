<?php

namespace App\Http\Controllers;

use App\Models\AdminUserBranch;
use App\Models\Branch;
// use DB;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Brand main page/index page
    public function index(Request $request)
    {
        if (auth()->user()->permission->product['brand'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }


        $img_url = asset('uploads/brand/');

        if ($request->ajax()) {

            if (auth()->user()->role_type == 1) {
                if (isset($request->branch_id) && $request->branch_id != null) {
                    $brands = DB::table('brands')
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $brands = DB::table('brands')
                        ->orderBy('id', 'DESC');
                }
            } else if (auth()->user()->role_type == 2) {
                if (isset($request->branch_id) && $request->branch_id != null) {
                    $brands = DB::table('brands')
                        ->where('admin_user_id', auth()->user()->id)
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $brands = DB::table('brands')
                        ->where('admin_user_id', auth()->user()->id)
                        ->orderBy('id', 'DESC');
                }
            } else {
                $brands = DB::table('brands')
                    ->where('branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'DESC');
            }

            // $brands = DB::table('brands')->orderBy('id', 'DESC')->get();
            // $brands = $brands->where('status',1)->get();
            return DataTables::of($brands)
                ->addIndexColumn()
                ->editColumn('photo', function ($row) use ($img_url) {
                    return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $img_url . '/' . $row->photo . '">';
                })
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('product.brands.edit', [$row->id]) . '" class="action-btn c-edit edit"><span class="fas fa-edit"></span></a>';
                    // $html .= '<a href="' . route('product.brands.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    if ($row->status == 1) {
                        $html .= '<a class="table-dropdown" title="'.__("Cancel").'" id="change_status" href="' . route('product.brands.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                    } else {
                        $html .= '<a class="table-dropdown" title="'.__("Undo").'" id="change_status" href="' . route('product.brands.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
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
                ->rawColumns(['photo', 'action', 'status'])
                ->make(true);
        }
        return view('product.brands.index', compact('branches'));
    }

    // Add Brand method
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'photo' => 'sometimes|image|max:2048',
            // 'add_branch_id' => 'required',
        ]);

        $branchUser = getBranchUser($request->add_branch_id);
        $addBrand = '';

        if ($request->file('photo')) {

            $brandPhoto = $request->file('photo');
            $brandPhotoName = uniqid() . '.' . $brandPhoto->getClientOriginalExtension();
            Image::make($brandPhoto)->resize(250, 250)->save('uploads/brand/' . $brandPhotoName);

            $addBrand = Brand::create([
                'name' => $request->name,
                'photo' => $brandPhotoName,
                'admin_user_id' => $branchUser->id,
                'branch_id' => $request->add_branch_id,
            ]);
        } else {

            $addBrand = Brand::create([
                'name' => $request->name,
                'admin_user_id' => $branchUser->id,
                'branch_id' => $request->add_branch_id,
            ]);
        }

        if ($addBrand) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 22, data_obj: $addBrand);
        }

        return response()->json(__('brand.add_success'));
    }

    //edit method
    public function edit($id)
    {
        $data = DB::table('brands')->where('id', $id)->first();

        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }

        return view('product.brands.ajax_view.edit', compact('data', 'branches'));
    }

    // Update Brand method
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'photo' => 'sometimes|image|max:2048',
            'add_branch_id' => 'required',
        ]);

        $updateBrand = Brand::where('id', $request->id)->first();

        if ($request->file('photo')) {

            if ($updateBrand->photo !== 'default.png') {

                if (file_exists(public_path('uploads/brand/' . $updateBrand->photo))) {

                    unlink(public_path('uploads/brand/' . $updateBrand->photo));
                }
            }

            $brandPhoto = $request->file('photo');
            $brandPhotoName = uniqid() . '.' . $brandPhoto->getClientOriginalExtension();
            Image::make($brandPhoto)->resize(250, 250)->save('uploads/brand/' . $brandPhotoName);

            $updateBrand->update([
                'name' => $request->name,
                'photo' => $brandPhotoName,
            ]);
        } else {
            $updateBrand->update([
                'name' => $request->name,
            ]);
        }

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 22, data_obj: $updateBrand);

        return response()->json(__('brand.update_success'));
    }

    public function changeStatus($brandId)
    {
        $statusChange = Brand::where('id', $brandId)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Brand is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Brand is activated Successfully'));

        }
    }

    // Delete Brand method//
    public function delete(Request $request, $brandId)
    {
        $deleteBrand = Brand::find($brandId);

        if ($deleteBrand->photo !== 'default.png') {

            if (file_exists(public_path('uploads/brand/' . $deleteBrand->photo))) {

                unlink(public_path('uploads/brand/' . $deleteBrand->photo));
            }
        }

        if (!is_null($deleteBrand)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 22, data_obj: $deleteBrand);
            $deleteBrand->delete();
        }

        return response()->json(__('brand.delete_success'));
    }
}
