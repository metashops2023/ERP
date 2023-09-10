<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarcodeSetting;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BarcodeSettingController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->permission->setup['barcode_settings'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $barcodeSettings = DB::table('barcode_settings')->where('is_fixed', 0)->orderBy('id', 'DESC')->get(['id', 'name', 'description', 'is_default']);
            return DataTables::of($barcodeSettings)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->name . ' ' . ($row->is_default == 1 ? '<span class="badge bg-primary">Default</span>' : '');
                })
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('settings.barcode.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="'.__("Edit").'"><span class="fas fa-edit"></span></a>';
                    if ($row->is_default == 0) {
                        $html .= '<a href="' . route('settings.barcode.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="'.__("Delete").'"><span class="fas fa-trash"></span></a>';
                        $html .= '<a href="' . route('settings.barcode.set.default', [$row->id]) . '" class="bg-primary text-white rounded pe-1" id="set_default_btn">
                        Set Default
                        </a>';
                    }

                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action', 'name'])
                ->make(true);
        }
        return view('settings.barcode_settings.index');
    }

    public function create()
    {
        return view('settings.barcode_settings.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'top_margin' => 'required',
            'left_margin' => 'required',
            'sticker_width' => 'required',
            'sticker_height' => 'required',
            'paper_width' => 'required',
            'paper_height' => 'required',
            'row_distance' => 'required',
            'column_distance' => 'required',
            'stickers_in_a_row' => 'required',
            'stickers_in_one_sheet' => 'required',
        ]);

        if (isset($request->set_as_default)) {
            $defaultBarcodeSetting = BarcodeSetting::where('is_default', 1)->first();
            if ($defaultBarcodeSetting) {
                $defaultBarcodeSetting->is_default = 0;
                $defaultBarcodeSetting->save();
            }
        }

        BarcodeSetting::insert([
            'name' => $request->name,
            'description' => $request->description,
            'is_continuous' => isset($request->is_continuous) ? 1 : 0,
            'top_margin' => $request->top_margin,
            'left_margin' => $request->left_margin,
            'sticker_width' => $request->sticker_width,
            'sticker_height' => $request->sticker_height,
            'paper_width' => $request->paper_width,
            'paper_height' => $request->paper_height,
            'row_distance' => $request->row_distance,
            'column_distance' => $request->column_distance,
            'stickers_in_a_row' => $request->stickers_in_a_row,
            'stickers_in_one_sheet' => $request->stickers_in_one_sheet,
            'is_default' => isset($request->set_as_default) ? 1 : 0,
        ]);

        $barcodeSetting = BarcodeSetting::all();
        if (count($barcodeSetting) == 1) {
            $barcodeSetting = BarcodeSetting::first();
            $barcodeSetting->is_default = 1;
            $barcodeSetting->save();
        }

        return response()->json(__('Barcode sticker setting created Successfully.'));

    }

    public function edit($id)
    {
        $bs = DB::table('barcode_settings')->where('id', $id)->first();
        return view('settings.barcode_settings.edit', compact('bs'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'top_margin' => 'required',
            'left_margin' => 'required',
            'sticker_width' => 'required',
            'sticker_height' => 'required',
            'paper_width' => 'required',
            'paper_height' => 'required',
            'row_distance' => 'required',
            'column_distance' => 'required',
            'stickers_in_a_row' => 'required',
            'stickers_in_one_sheet' => 'required',
        ]);

        $updateBs = BarcodeSetting::where('id', $id)->first();

        $updateBs->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_continuous' => isset($request->is_continuous) ? 1 : 0,
            'top_margin' => $request->top_margin,
            'left_margin' => $request->left_margin,
            'sticker_width' => $request->sticker_width,
            'sticker_height' => $request->sticker_height,
            'paper_height' => $request->paper_height,
            'paper_width' => $request->paper_width,
            'row_distance' => $request->row_distance,
            'column_distance' => $request->column_distance,
            'stickers_in_a_row' => $request->stickers_in_a_row,
            'stickers_in_one_sheet' => $request->stickers_in_one_sheet,
        ]);

        return response()->json(__('Barcode sticker setting updated Successfully.'));
    }

    public function delete(Request $request, $id)
    {
        $delete = BarcodeSetting::find($id);
        if (!is_null($delete)) {
            $delete->delete();
        }

            return response()->json(__('Barcode sticker setting deleted Successfully.'));
    }

    public function setDefault($id)
    {
        $defaultBs = BarcodeSetting::where('is_default', 1)->first();
        if ($defaultBs) {
            $defaultBs->is_default = 0;
            $defaultBs->save();
        }

        $updateBs = BarcodeSetting::where('id', $id)->first();
        $updateBs->is_default = 1;
        $updateBs->save();

            return response()->json('Default set successfully');


    }
}
