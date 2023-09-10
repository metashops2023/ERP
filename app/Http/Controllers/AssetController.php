<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $assetTypes = DB::table('asset_types')->orderBy('id', 'desc')->get();
            return DataTables::of($assetTypes)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $html = '<div class="dropdown table-dropdown">';

                    $html .= '<a href="' . route('accounting.assets.asset.type.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="'.__("Edit").'"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('accounting.assets.asset.type.delete', [$row->id]) . '" class="action-btn c-delete" id="delete_type" title="'.__("Delete").'"><span class="fas fa-trash"></span></a>';


                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
       $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
       return view('accounting.assets.index', compact('branches'));
    }

    public function assetTypeStore(Request $request)
    {
        $this->validate($request, [
            'asset_type_name' => 'required|unique:asset_types,asset_type_name',
        ]);

        AssetType::insert([
            'asset_type_name' => $request->asset_type_name,
            'asset_type_code' => $request->asset_type_code,
        ]);

            return response()->json(__('Asset type created successfully.'));


    }

    public function assetTypeEdit($typeId)
    {
        $type = DB::table('asset_types')->where('id', $typeId)->first();
        return view('accounting.assets.ajax_view.edit_asset_type', compact('type'));
    }

    public function assetTypeUpdate(Request $request, $typeId)
    {
        $this->validate($request, [
            'asset_type_name' => 'required|unique:asset_types,asset_type_name,'.$typeId,
        ]);

        $updateType = AssetType::where('id', $typeId)->first();
        $updateType->update([
            'asset_type_name' => $request->asset_type_name,
            'asset_type_code' => $request->asset_type_code,
        ]);

        return response()->json(__('Asset type updated successfully.'));
    }

    public function assetTypeDelete(Request $request, $typeId)
    {
        $deleteType = AssetType::find($typeId);
        if (!is_null($deleteType)) {
            $deleteType->delete();
        }
        return response()->json(__('Asset type deleted successfully.'));
    }

    public function formAssetTypes()
    {
        $types = DB::table('asset_types')->orderBy('id', 'desc')->get();
        return response()->json($types);
    }

    public function assetStore(Request $request)
    {
        $this->validate($request, [
            'asset_name' => 'required',
            'type_id' => 'required',
            'quantity' => 'required',
            'per_unit_value' => 'required',
            'total_value' => 'required',
        ],['type_id.required' => 'The asset type field is required.']);

        Asset::insert([
            'asset_name' => $request->asset_name,
            'type_id' => $request->type_id,
            'branch_id' => $request->branch_id,
            'quantity' => $request->quantity,
            'per_unit_value' => $request->per_unit_value,
            'total_value' => $request->total_value,
        ]);

        return response()->json(__('Asset created successfully.'));

    }

    public function allAsset(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $assets = '';
            $assetsQ = DB::table('assets')
            ->leftJoin('asset_types', 'assets.type_id', 'asset_types.id')
            ->leftJoin('branches', 'assets.branch_id', 'branches.id');

            if ($request->type_id) {
                $assetsQ->where('assets.type_id', $request->type_id);
            }

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $assetsQ->where('assets.branch_id', NULL);
                } else {
                    $assetsQ->where('assets.branch_id', $request->branch_id);
                }
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $assets = $assetsQ->select(
                    'assets.*',
                    'asset_types.asset_type_name',
                    'asset_types.asset_type_code',
                    'branches.name as branch_name',
                    'branches.branch_code',
                )
                ->orderBy('assets.id', 'desc')->get();
            }else {
                $assets = $assetsQ->select(
                    'assets.*',
                    'asset_types.asset_type_name',
                    'asset_types.asset_type_code',
                    'branches.name as branch_name',
                    'branches.branch_code',
                )
                ->where('assets.branch_id', auth()->user()->branch_id)->orderBy('assets.id', 'desc')->get();
            }


            return DataTables::of($assets)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $html = '<div class="dropdown table-dropdown">';

                    $html .= '<a href="' . route('accounting.assets.edit', [$row->id]) . '" class="action-btn c-edit" id="edit_asset" title="'.__("Edit").'"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('accounting.assets.delete', [$row->id]) . '" class="action-btn c-delete" id="delete_asset" title="'.__("Delete").'"><span class="fas fa-trash"></span></a>';


                $html .= '</div>';
                return $html;
            })
            ->editColumn('asset_type', function($row) {
                return $row->asset_type_name . '/' . $row->asset_type_code;
            })
            ->editColumn('branch', function($row) use ($generalSettings){
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name']. '(<b>HO</b>)';
                }
            })
            ->editColumn('per_unit_value', function($row) use ($generalSettings){
                return json_decode($generalSettings->business, true)['currency']. $row->per_unit_value;
            })
            ->editColumn('total_value', function($row) use ($generalSettings){
                return json_decode($generalSettings->business, true)['currency']. $row->total_value;
            })
            ->rawColumns(['action', 'branch', 'per_unit_value', 'total_value'])
            ->make(true);
        }
    }

    public function assetEdit($assetId)
    {
        $asset = DB::table('assets')->where('id', $assetId)->first();
        $types = DB::table('asset_types')->get();
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('accounting.assets.ajax_view.edit_asset', compact('asset', 'types', 'branches'));
    }


    public function assetUpdate(Request $request, $assetId)
    {
        $this->validate($request, [
            'asset_name' => 'required',
            'type_id' => 'required',
            'quantity' => 'required',
            'per_unit_value' => 'required',
            'total_value' => 'required',
        ],['type_id.required' => 'The asset type field is required.']);

        $updateAsset = Asset::where('id', $assetId)->first();
        $updateAsset->update([
            'asset_name' => $request->asset_name,
            'type_id' => $request->type_id,
            'branch_id' => $request->branch_id,
            'quantity' => $request->quantity,
            'per_unit_value' => $request->per_unit_value,
            'total_value' => $request->total_value,
        ]);

        return response()->json(__('Asset updated successfully.'));
    }

    public function assetDelete(Request $request, $assetId)
    {
        $delete = Asset::find($assetId);
        if (!is_null($delete)) {
            $delete->delete();
        }
        return response()->json(__('Asset deleted successfully.'));
    }
}
