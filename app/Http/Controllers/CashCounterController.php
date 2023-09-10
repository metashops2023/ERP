<?php

namespace App\Http\Controllers;

use App\Models\CashCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class CashCounterController extends Controller
{
    // Cash Counter main page/index page
    public function index(Request $request)
    {
        if (auth()->user()->permission->setup['cash_counters'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first(['business']);
            $cashCounters = '';
            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $cashCounters = DB::table('cash_counters')->orderBy('id', 'DESC')
                    ->leftJoin('branches', 'cash_counters.branch_id', 'branches.id')
                    ->select(
                        'branches.name as br_name',
                        'branches.branch_code as br_code',
                        'cash_counters.id',
                        'cash_counters.counter_name',
                        'cash_counters.status',
                        'cash_counters.short_name'
                    );
            } else {
                $cashCounters = DB::table('cash_counters')->orderBy('id', 'DESC')
                    ->leftJoin('branches', 'cash_counters.branch_id', 'branches.id')
                    ->select(
                        'branches.name as br_name',
                        'branches.branch_code as br_code',
                        'cash_counters.id',
                        'cash_counters.counter_name',
                        'cash_counters.status',
                        'cash_counters.short_name'
                    )
                    ->where('branch_id', auth()->user()->branch_id);
                }


            return DataTables::of($cashCounters)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';

                    $html .= '<a href="' . route('settings.cash.counter.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    if ($row->status == 1) {
                        $html .= '<a class="table-dropdown" title="'.__("Cancel").'" id="change_status" href="' . route('settings.cash.counter.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                    } else {
                        $html .= '<a class="table-dropdown" title="'.__("Undo").'" id="change_status" href="' . route('settings.cash.counter.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
                    }
                    // $html .= '<a href="' . route('settings.cash.counter.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('branch', function ($row) use ($generalSettings) {
                    if ($row->br_name) {
                        return $row->br_name . '/' . $row->br_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('status', function ($row) {

                    if ($row->status == 1) {

                        return '<span class="text-success">Active</span>';
                    } else {

                        return '<span class="text-danger">Inactive</span>';
                    }
                })
            //     ->filter(function($query) use($request){
            //      // dd($request->active);
            //      if($request->active=="false"){
            //          $query->where('status',1);
            //      }else{
            //      $query->where('status',0);
            //  }
            //     })
                ->rawColumns(['branch', 'action','status'])
                ->make(true);
        }
        return view('settings.cash_counter.index');
    }

    public function store(Request $request)
    {
        $addons = DB::table('addons')->select('cash_counter_limit')->first();

        $cash_counter_limit = $addons->cash_counter_limit;

        $cash_counters = DB::table('cash_counters')
            ->where('branch_id', auth()->user()->branch_id)
            ->count();

        if ($cash_counter_limit <= $cash_counters) {

            return response()->json(["errorMsg" => "Cash counter limit is ${cash_counter_limit}"]);
        }

        $this->validate($request, [
            // 'counter_name' => 'required|unique:cash_counters,counter_name',
            'counter_name' => ['required', Rule::unique('cash_counters')->where(function ($query) {
                return $query->where('branch_id', auth()->user()->branch_id);
            })],
            // 'short_name' => 'required|unique:cash_counters,short_name',
            'short_name' => ['required', Rule::unique('cash_counters')->where(function ($query) {
                return $query->where('branch_id', auth()->user()->branch_id);
            })],
        ]);

        CashCounter::insert([
            'branch_id' => auth()->user()->branch_id,
            'counter_name' => $request->counter_name,
            'short_name' => $request->short_name,
        ]);

        return response()->json('Cash counter created Successfully.');
    }

    public function edit($id)
    {
        $cc = DB::table('cash_counters')->where('id', $id)->orderBy('id', 'DESC')->first(['id', 'counter_name', 'short_name']);
        return view('settings.cash_counter.ajax_view.edit_cash_counter', compact('cc'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'counter_name' => 'required|unique:cash_counters,counter_name,' . $id,
            'short_name' => 'required|unique:cash_counters,short_name,' . $id,
        ]);

        $updateCC = CashCounter::where('id', $id)->first();
        $updateCC->update([
            'counter_name' => $request->counter_name,
            'short_name' => $request->short_name,
        ]);

        return response()->json('Cash counter updated Successfully.');
    }

    public function changeStatus($id)
    {
        $statusChange = CashCounter::where('id', $id)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Cash Counter is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Cash Counter is activated Successfully'));

        }
    }

    public function delete(Request $request, $id)
    {
        $delete = CashCounter::find($id);
        if (!is_null($delete)) {
            $delete->delete();
        }

        return response()->json('Cash counter deleted Successfully.');
    }
}
