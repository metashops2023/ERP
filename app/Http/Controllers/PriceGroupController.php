<?php

namespace App\Http\Controllers;

use App\Models\PriceGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PriceGroupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $price_groups = DB::table('price_groups')->get(['id', 'name', 'description', 'status']);
            return DataTables::of($price_groups)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown table-dropdown">';

                    $html .= '<a href="' . route('product.selling.price.groups.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="'.__("Edit").'"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('product.selling.price.groups.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="'.__("Delete").'"><span class="fas fa-trash"></span></a>';
                    if ($row->status == "Active") {
                        $html .= '<a href="'.route('product.selling.price.groups.change.status', [$row->id]).'" class="btn btn-sm btn-danger ms-1 deactivate-btn" id="change_status">'.__("Deactivate").'</a>';
                    }else {
                        $html .= '<a href="'.route('product.selling.price.groups.change.status', [$row->id]).'" class="btn btn-sm btn-info text-white ms-1 activee-btn" id="change_status">'.__("Active").'</a>';
                    }



                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == "Active") {
                        $html .= '<a href="" class="text-success" id="change_status">Active</a>';
                    }else {
                        $html .= '<span  class="text-danger" id="change_status">Deactivate</span>';
                    }

                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('product.price_group.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:price_groups,name',
        ]);

        PriceGroup::insert([
            'name' => $request->name,
            'description' => $request->description,
        ]);

            return response()->json(__('price group created Successfully'));


    }

    public function edit($id)
    {
        $pg = DB::table('price_groups')->where('id', $id)->first();
        return view('product.price_group.ajax_view.edit', compact('pg'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:price_groups,name,'.$id,
        ]);
        $updatePg = PriceGroup::where('id', $id)->first();
        $updatePg->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return response()->json(__('price group updated Successfully'));
    }

    public function delete(Request $request, $id)
    {
        $delete = PriceGroup::find($id);
        if (!is_null($delete)) {
            $delete->delete();
        }
        return response()->json(__('price group deleted Successfully'));
    }

    public function changeStatus($id)
    {
        $statusChange = PriceGroup::where('id', $id)->first();
        if ($statusChange->status == 'Active') {
            $statusChange->status = 'Deactivate';
            $statusChange->save();
            return response()->json(__('Successfully Price group is deactivated'));
        } else {
            $statusChange->status = 'Active';
            $statusChange->save();
            return response()->json(__('Successfully Price group is activated'));
        }
    }
}
