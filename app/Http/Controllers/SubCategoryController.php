<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB as FacadesDB;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;


class SubCategoryController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Get all sub-categories by index page
    public function index(Request $request)
    {
        if (auth()->user()->permission->product['categories'] == '0') {

            return response()->json('Access Denied');
        }

        $img_url = asset('uploads/category/');

        if ($request->ajax()) {
            $subCategories = DB::table('categories')
                ->join('categories as parentcat', 'parentcat.id', 'categories.parent_category_id')
                ->select('parentcat.name as parentname', 'categories.*')
                ->whereNotNull('categories.parent_category_id')->orderBy('id', 'DESC');

            return DataTables::of($subCategories)
                ->addIndexColumn()
                ->editColumn('photo', function ($row) use ($img_url) {

                    return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $img_url . '/' . $row->photo . '">';
                })
                ->addColumn('action', function ($row) {

                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';

                        $html .= '<a href="javascript:;" class="action-btn c-edit edit_sub_cate" data-id="' . $row->id . '"><span class="fas fa-edit" title="'.__("Edit").'"></span></a>';
                        // $html .= '<a href="' . route('product.subcategories.delete', [$row->id]) . '" class="action-btn c-delete" id="delete_sub_cate" title="'.__("Delete").'"><span class="fas fa-trash "></span></a>';
                        if ($row->status == 1) {
                            $html .= '<a class="table-dropdown" title="'.__("Cancel").'" id="change_status_sub" href="' . route('product.subcategories.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                        } else {
                            $html .= '<a class="table-dropdown" title="'.__("Undo").'" id="change_status_sub" href="' . route('product.subcategories.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
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
                     $query->where('categories.status',1);
                 }else{
                 $query->where('categories.status',0);
             }
                })
                ->rawColumns(['photo', 'action','status'])
                ->make(true);
        }
    }

    //edit
    public function edit($id)
    {
        if (auth()->user()->permission->product['categories'] == '0') {

            return response()->json('Access Denied');
        }

        $data = DB::table('categories')->where('id', $id)->first();
        $category = DB::table('categories')->where('parent_category_id', NULL)->get();
        return view('product.categories.ajax_view.edit_sub_category', compact('category', 'data'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->permission->product['categories'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            // 'name' => ['required', Rule::unique('categories')->where(function ($query) {
            //     return $query->where('parent_category_id', '!=', NULL);
            // })],
            'name' => 'required',
            'parent_category_id' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ], ['parent_category_id.required' => 'Parent category field is required']);

        $addSubCategory = '';

        if ($request->file('photo')) {

            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('uploads/category/' . $categoryPhotoName);

            $addSubCategory = Category::insert([
                'name' => $request->name,
                'description' => $request->description,
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : NULL,
                'photo' => $categoryPhotoName
            ]);
        } else {

            $addSubCategory = Category::insert([
                'name' => $request->name,
                'description' => $request->description,
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : NULL,
            ]);
        }

        if ($addSubCategory) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 21, data_obj: $addSubCategory);
        }


            return response()->json(__('Subcategory created successfully'));


    }

    public function update(Request $request)
    {
        if (auth()->user()->permission->product['categories'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            // 'name' => ['required', Rule::unique('categories')->where(function ($query) {
            //     return $query->where('parent_category_id', '!=', NULL);
            // })],
            'name' => 'required',
            'parent_category_id' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ], ['parent_category_id.required' => 'Parent category field is required']);

        $updateCategory = Category::where('id', $request->id)->first();

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
                'description' => $request->description,
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : NULL,
                'photo' => $categoryPhotoName
            ]);
        } else {

            $updateCategory->update([
                'name' => $request->name,
                'description' => $request->description,
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : NULL,
            ]);
        }

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 21, data_obj: $updateCategory);

        return response()->json(__('Subcategory updated successfully'));
    }

    public function changeStatus($categoryId)
    {
        $statusChange = Category::where('id', $categoryId)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Sub-Category is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Sub-Category is activated Successfully'));

        }
    }

    public function delete(Request $request, $categoryId)
    {
        return response()->json('Feature is disabled in this demo');

        if (auth()->user()->permission->product['categories'] == '0') {

            return response()->json('Access Denied');
        }

        $deleteCategory = Category::find($categoryId);

        if ($deleteCategory->photo !== 'default.png') {

            if (file_exists(public_path('uploads/category/' . $deleteCategory->photo))) {

                unlink(public_path('uploads/category/' . $deleteCategory->photo));
            }
        }

        if (!is_null($deleteCategory)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 21, data_obj: $deleteCategory);

            $deleteCategory->delete();
        }

        return response()->json(__('Subcategory deleted successfully'));
    }
}
