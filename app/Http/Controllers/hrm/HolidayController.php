<?php

namespace App\Http\Controllers\hrm;

use App\Models\Branch;
use App\Models\Hrm\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class HolidayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //holiday page show methods
    public function index()
    {
        $branches = DB::table('branches')->orderBy('name', 'ASC')->get(['id', 'name', 'branch_code']);
        return view('hrm.holiday.index', compact('branches'));
    }

    //all holidays data get for holiday pages
    public function allHolidays()
    {
        $holidays = '';
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $holidays = Holiday::with('branch')->orderBy('id', 'DESC')->get();
        } else {
            $holidays = Holiday::with('branch')
                ->where('branch_id', auth()->user()->branch_id)
                ->orWhere('is_all', 1)
                ->orderBy('id', 'DESC')->get();
        }

        return view('hrm.holiday.ajax.list', compact('holidays'));
    }

    //store holidays methods
    public function storeHolidays(Request $request)
    {
        $this->validate($request, [
            'holiday_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $addHoliday = new Holiday();
        $addHoliday->holiday_name = $request->holiday_name;
        $addHoliday->start_date = $request->start_date;
        $addHoliday->end_date = $request->end_date;

        if (auth()->user()->role_type == 1) {
            if ($request->branch_id == 'All') {
                $addHoliday->is_all = 1;
                $addHoliday->branch_id = NULL;
            } elseif ($request->branch_id == '') {
                $addHoliday->branch_id = NULL;
            } else {
                $addHoliday->branch_id = $request->branch_id;
            }
        } else {
            $addHoliday->branch_id = auth()->user()->branch_id;
        }

        $addHoliday->notes = $request->notes;
        $addHoliday->save();

        return response()->json('Successfully Holiday Added!');
    }

    //Edit holid
    public function edit($id)
    {
        $holiday = Holiday::with('branch')->where('id', $id)->first();
        $branches = DB::table('branches')->orderBy('name', 'ASC')->get(['id', 'name', 'branch_code']);
        return view('hrm.holiday.ajax.edit', compact('holiday', 'branches'));
    }

    //update holiday
    public function updateHoliday(Request $request)
    {
        $this->validate($request, [
            'holiday_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $updateHoliday = Holiday::where('id', $request->id)->first();
        $updateHoliday->holiday_name = $request->holiday_name;
        $updateHoliday->start_date = $request->start_date;
        $updateHoliday->end_date = $request->end_date;

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $updateHoliday->is_all = 0;
            $updateHoliday->branch_id = NULL;
            if ($request->branch_id == 'All') {
                $updateHoliday->is_all = 1;
                $updateHoliday->branch_id = NULL;
            } elseif (!$request->branch_id) {
                $updateHoliday->branch_id = NULL;
            } elseif ($request->branch_id) {
                $updateHoliday->branch_id = $request->branch_id;
            }
        }

        $updateHoliday->notes = $request->notes;
        $updateHoliday->save();

        return response()->json('Successfully Holidays Updated!');
    }

    //destroy holidays
    public function deleteHolidays(Request $request, $id)
    {
        $holiday = Holiday::find($id);
        if (!is_null($holiday)) {
            $holiday->delete();
        }

        return response()->json('Successfully Holiday Deleted');
    }
}
