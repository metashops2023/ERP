<?php

namespace App\Http\Controllers\Essentials;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Essential\Memo;
use App\Models\Essential\MemoUser;
use Yajra\DataTables\Facades\DataTables;

class MemoController extends Controller
{
    public function index(Request $request)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        if (auth()->user()->permission->essential['memo'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $memos = DB::table('memo_users')
                ->join('memos', 'memo_users.memo_id', 'memos.id')
                ->join('admin_and_users as shared_by', 'memos.admin_id', 'shared_by.id')
                ->where('memo_users.user_id', auth()->user()->id)
                ->select(
                    'memo_users.is_author',
                    'memos.id',
                    'memos.heading',
                    'memos.description',
                    'memos.created_at',
                    'shared_by.prefix',
                    'shared_by.name',
                    'shared_by.last_name',
                )
                ->orderBy('id', 'desc');
            return DataTables::of($memos)
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';

                        $html .= '<a href="'.route('memos.show', [$row->id]).'" class="action-btn c-edit" id="view" title="'.__("view").'"><span class="fas fa-eye text-info"></span></a>';
                        if ($row->is_author == 1) {
                            $html .= '<a href="' . route('memos.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="'.__("Edit").'"><span class="fas fa-edit"></span></a>';
                            $html .= '<a href="' . route('memos.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="'.__("Delete").'"><span class="fas fa-trash"></span></a>';
                            $html .= '<a href="'.route('memos.add.user.view', [$row->id]).'" class="bg-primary text-white rounded p-1" id="add_user_btn">
                           '.__("Share").'
                            </a>';

                    }


                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('heading', function ($row) {
                    if ($row->is_author == 1) {
                        return $row->heading;
                    }else {
                        return $row->heading.'<br><b>Shared By : </b><span class="text-muted">'.$row->prefix.' '.$row->name.' '.$row->last_name.'</span>';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->rawColumns(['action', 'created_at', 'heading'])
                ->make(true);
        }
        return view('essentials.memos.index');
    }

    public function store(Request $request)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'heading' => 'required',
            'description' => 'required',
        ]);

        $addMemo = Memo::insertGetId([
            'heading' => $request->heading,
            'description' => $request->description,
            'admin_id' => auth()->user()->id,
            'created_at' => date('Y-m-d'),
        ]);

        MemoUser::insert([
            'memo_id' => $addMemo,
            'user_id' => auth()->user()->id,
            'is_author' => 1
        ]);

          return response()->json(__('Memo created successfully.'));


    }

    public function delete(Request $request, $id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $deleteMemo = Memo::where('id', $id)->first();
        if (!is_null($deleteMemo)) {
            $deleteMemo->delete();
        }

            return response()->json(__('Memo deleted successfully.'));


    }

    public function edit($id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        return $memo = Memo::where('id', $id)->first(['id', 'heading', 'description']);
    }

    public function update(Request $request)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'heading' => 'required',
            'description' => 'required',
        ]);

        $updateMemo = Memo::where('id', $request->id)->first();
        $updateMemo->update([
            'heading' => $request->heading,
            'description' => $request->description,
        ]);

            return response()->json(__('Memo updated successfully.'));


    }

    public function addUserView($id)
    {
        $memo = Memo::with(['memo_users'])->where('id', $id)->first('id', 'admin_id');
        $users = DB::table('admin_and_users')->where('branch_id', auth()->user()->branch_id)->get();
        return view('essentials.memos.ajax_view.add_user_form', compact('memo', 'users'));
    }

    public function addUsers(Request $request, $id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $memo = Memo::with(['memo_users'])->where('id', $id)->first();
        foreach ($memo->memo_users as $user) {
            $user->is_delete_in_update = 1;
            $user->save();
        }

        if (count($request->user_ids) > 0) {
            foreach ($request->user_ids as $user_id) {
                $existsUser = MemoUser::where('memo_id', $id)
                    ->where('user_id', $user_id)->first();
                if ($existsUser) {
                    $existsUser->is_delete_in_update = 0;
                    $existsUser->save();
                }else {
                    MemoUser::insert([
                        'memo_id' => $id,
                        'user_id' => $user_id
                    ]);
                }
            }
        }

        $deleteUsers = MemoUser::where('memo_id', $id)->where('is_author', 0)->where('is_delete_in_update', 1)->get();
        foreach ($deleteUsers as $deleteUser) {
            $deleteUser->delete();
        }

            return response()->json(__('Memo shared successfully.'));


    }

    public function show($id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $memo = Memo::where('id', $id)->first();
        return view('essentials.memos.ajax_view.show', compact('memo'));
    }
}
