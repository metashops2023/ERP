<?php

namespace App\Http\Controllers;

use App\Models\AdminAndUser;
use App\Models\AdminUserBranch;
use App\Models\Branch;
use App\Models\Category;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Category main page/index page
    public function index(Request $request)
    {
        if (auth()->user()->permission->product['categories'] == '0') {

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
        //

        $img_url = asset('uploads/category/');

        if ($request->ajax()) {

            if (auth()->user()->role_type == 1) {
                if (isset($request->branch_id) && $request->branch_id != null) {
                    $categories = DB::table('categories')
                        ->where('parent_category_id', NULL)
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $categories = DB::table('categories')
                        ->where('parent_category_id', NULL)
                        ->orderBy('id', 'DESC');
                }
            } else if (auth()->user()->role_type == 2) {
                if (isset($request->branch_id) && $request->branch_id != null) {
                    $categories = DB::table('categories')->where('parent_category_id', NULL)
                        ->where('admin_user_id', auth()->user()->id)
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $categories = DB::table('categories')->where('parent_category_id', NULL)
                        ->where('admin_user_id', auth()->user()->id)
                        ->orderBy('id', 'DESC');
                }
            } else {
                $categories = DB::table('categories')
                    ->where('parent_category_id', NULL)
                    ->where('branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'DESC');
            }

            // $categories = DB::table('categories')
            //     ->where('parent_category_id', NULL)
            //     ->orderBy('id', 'DESC')->get();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->editColumn('photo', function ($row) use ($img_url) {

                    return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $img_url . '/' . $row->photo . '">';
                })
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="javascript:;" class="action-btn c-edit" id="edit" title="'.__("Edit").'"><span class="fas fa-edit"></span></a>';
                        // $html .= '<a href="' . route('product.categories.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="'.__("Delete").'"><span class="fas fa-trash "></span></a>';
                        if ($row->status == 1) {
                            $html .= '<a class="table-dropdown" title="'.__("Cancel").'" id="change_status" href="' . route('product.categories.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                        } else {
                            $html .= '<a class="table-dropdown" title="'.__("Undo").'" id="change_status" href="' . route('product.categories.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
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
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('product.categories.edit', $row->id);
                    }
                ])
                ->rawColumns(['photo', 'action','status'])->smart(true)->make(true);
        }

        $categories = DB::table('categories')->where('parent_category_id', NULL)->where('status', 1)->get();
        return view('product.categories.index', compact('categories', 'branches'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->permission->product['categories'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => [
                'required', Rule::unique('categories')->where(function ($query) {
                    return $query->where('parent_category_id', NULL);
                }),
                'add_branch_id' => 'required',
            ],
            'photo' => 'sometimes|image|max:2048',
        ]);

        $addCategory = '';

        $branchUser = getBranchUser($request->add_branch_id);

        if ($request->file('photo')) {

            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('uploads/category/' . $categoryPhotoName);

            $addCategory = Category::create([
                'admin_user_id' => $branchUser->id,
                'branch_id' => $request->add_branch_id,
                'name' => $request->name,
                'description' => $request->description,
                'photo' => $categoryPhotoName
            ]);
        } else {

            $addCategory = Category::create([
                'admin_user_id' => $branchUser->id,
                'branch_id' => $request->add_branch_id,
                'name' => $request->name,
                'description' => $request->description,
            ]);
        }

        if ($addCategory) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 20, data_obj: $addCategory);
        }
            return response()->json(__('Category created Successfully'));

    }

    public function edit($categoryId)
    {
        if (auth()->user()->permission->product['categories'] == '0') {

            return response()->json('Access Denied');
        }

        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }


        $category = DB::table('categories')->where('id', $categoryId)->first();
        return view('product.categories.ajax_view.edit_modal_body', compact('category', 'branches'));
    }

    public function update(Request $request)
    {
        if (auth()->user()->permission->product['categories'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => ['required', Rule::unique('categories')->where(function ($query) use ($request) {
                return $query->where('parent_category_id', NULL)->where('id', '!=', $request->id);
            })],
            'photo' => 'sometimes|image|max:2048',
        ]);

        $updateCategory = Category::where('id', $request->id)->first();

        $branchUser = getBranchUser($request->add_branch_id);

        if ($request->file('photo')) {

            if ($updateCategory->photo !== 'default.png') {

                if (file_exists(public_path('uploads/category/' . $updateCategory->photo))) {

                    unlink(public_path('uploads/category/' . $updateCategory->photo));
                }
            }

            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('uploads/category/' . $categoryPhotoName);

            $updateCategory->update([
                'name' => $request->name,
                'admin_user_id' => $branchUser->id,
                'branch_id' => $request->add_branch_id,
                'description' => $request->description,
                'photo' => $categoryPhotoName,
            ]);
        } else {

            $updateCategory->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
        }

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 20, data_obj: $updateCategory);

        return response()->json(__('Category updated Successfully'));

    }

    public function changeStatus($categoryId)
    {
        $statusChange = Category::where('id', $categoryId)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Category is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Category is activated Successfully'));

        }
    }

    public function delete(Request $request, $categoryId)
    {
        if (auth()->user()->permission->product['categories'] == '0') {

            return response()->json('Access Denied');
        }

        $deleteCategory = Category::with(['subCategories'])->where('id', $categoryId)->first();

        if (count($deleteCategory->subCategories) > 0) {
            return response()->json(['errorMsg' => __('Category can not be deleted. One or more sub-categories is belonging under this category.')]);
        }

        if ($deleteCategory->photo !== 'default.png') {

            if (file_exists(public_path('uploads/category/' . $deleteCategory->photo))) {

                unlink(public_path('uploads/category/' . $deleteCategory->photo));
            }
        }

        if (!is_null($deleteCategory)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 20, data_obj: $deleteCategory);

            $deleteCategory->delete();
        }

        return response()->json(__('Category deleted Successfully'));

    }
}
