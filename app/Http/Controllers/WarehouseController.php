<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if (auth()->user()->permission->setup['warehouse'] == '0') : abort(403, 'Access Forbidden.');
        endif;

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $warehouses = '';
            $query = DB::table('warehouse_branches')
                ->leftJoin('branches', 'warehouse_branches.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('warehouse_branches.branch_id', NULL)->orWhere('warehouse_branches.is_global', 1);
                } else {

                    $query->where('warehouse_branches.branch_id', $request->branch_id)->orWhere('warehouse_branches.is_global', 1);
                }
            }

            $query->select(
                'warehouse_branches.is_global',
                'warehouse_branches.warehouse_id',
                'warehouse_branches.branch_id',
                'warehouses.warehouse_name as name',
                'warehouses.phone',
                'warehouses.address',
                'warehouses.warehouse_code as code',
                'branches.name as b_name',
                'branches.branch_code as b_code',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $warehouses = $query->orderBy('warehouses.id', 'desc');
            } else {

                $warehouses = $query->where('warehouse_branches.branch_id', auth()->user()->branch_id)
                    ->orWhere('warehouse_branches.is_global', 1)
                    ->orderBy('warehouses.id', 'desc');
            }

            return DataTables::of($warehouses)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('settings.warehouses.edit', [$row->warehouse_id]) . '" class="action-btn c-edit edit" id="edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('settings.warehouses.delete', [$row->warehouse_id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('branch',  function ($row) use ($generalSettings) {

                    if ($row->is_global == 1) {

                        return 'Global Access';
                    } else {

                        if ($row->b_name) {

                            return $row->b_name . '/' . $row->b_code . '(<b>B.L.</b>)';
                        } else {

                            return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                        }
                    }
                })
                ->rawColumns(['branch', 'action'])
                ->make(true);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('settings.warehouses.index', compact('branches'));
    }

    public function store(Request $request)
    {
        //return count($request->branch_ids);
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
        ]);

        $addWarehouse = new Warehouse();
        // $addWarehouse->branch_id = auth()->user()->branch_id;
        $addWarehouse->warehouse_name = $request->name;
        $addWarehouse->warehouse_code = $request->code;
        $addWarehouse->phone = $request->phone;
        $addWarehouse->address = $request->address;
        $addWarehouse->save();


        if (isset($request->branch_ids)) {

            foreach ($request->branch_ids as $branch_id) {

                $__branch_id = $branch_id == 'NULL' ? NULL : $branch_id;
                $addWarehouseBranch = new WarehouseBranch();
                $addWarehouseBranch->warehouse_id = $addWarehouse->id;
                $addWarehouseBranch->branch_id = $__branch_id;
                $addWarehouseBranch->save();
            }
        } else {

            $addWarehouseBranch = new WarehouseBranch();
            $addWarehouseBranch->warehouse_id = $addWarehouse->id;
            $addWarehouseBranch->branch_id = NULL;
            $addWarehouseBranch->is_global = 1;
            $addWarehouseBranch->save();
        }

        return response()->json('Successfully warehouse is added');
    }

    public function edit($id)
    {
        $w = Warehouse::with(['warehouseBranches'])->where('id', $id)->first();

        $isExistsHeadOffice = DB::table('warehouse_branches')
            ->where('warehouse_id', $id)
            ->where('branch_id', NULL)
            ->where('is_global', 0)
            ->first();

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('settings.warehouses.ajax_view.edit', compact('w', 'branches', 'isExistsHeadOffice'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
        ]);

        $updateWarehouse = Warehouse::where('id', $id)->first();
        $updateWarehouse->warehouse_name = $request->name;
        $updateWarehouse->warehouse_code = $request->code;
        $updateWarehouse->phone = $request->phone;
        $updateWarehouse->address = $request->address;
        $updateWarehouse->save();

        WarehouseBranch::where('warehouse_id', $id)->delete();

        if (isset($request->branch_ids)) {

            foreach ($request->branch_ids as $branch_id) {

                $__branch_id = $branch_id == 'NULL' ? NULL : $branch_id;
                $addWarehouseBranch = new WarehouseBranch();
                $addWarehouseBranch->warehouse_id = $updateWarehouse->id;
                $addWarehouseBranch->branch_id = $__branch_id;
                $addWarehouseBranch->save();
            }
        } else {

            $addWarehouseBranch = new WarehouseBranch();
            $addWarehouseBranch->warehouse_id = $updateWarehouse->id;
            $addWarehouseBranch->branch_id = NULL;
            $addWarehouseBranch->is_global = 1;
            $addWarehouseBranch->save();
        }

        return response()->json('Successfully warehouse is updated');
    }

    public function delete(Request $request, $warehouseId)
    {
        $deleteWarehouse = Warehouse::where('id', $warehouseId)->first();
        if (!is_null($deleteWarehouse)) {

            $deleteWarehouse->delete();
        }
        return response()->json('Successfully warehouse is deleted');
    }
}
