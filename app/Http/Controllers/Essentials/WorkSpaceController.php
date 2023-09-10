<?php

namespace App\Http\Controllers\Essentials;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Essential\Workspace;
use App\Models\Essential\WorkspaceAttachment;
use App\Models\Essential\WorkspaceUsers;
use Yajra\DataTables\Facades\DataTables;

class WorkSpaceController extends Controller
{
    public function index(Request $request)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();

            $workspaces = '';
            $query = DB::table('workspaces')->leftJoin('branches', 'workspaces.branch_id', 'branches.id')
                ->leftJoin('admin_and_users', 'workspaces.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('workspaces.branch_id', NULL);
                } else {
                    $query->where('workspaces.branch_id', $request->branch_id);
                }
            }

            if ($request->priority) {
                $query->where('workspaces.priority', $request->priority);
            }

            if ($request->status) {
                $query->where('workspaces.status', $request->status);
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                $query->whereBetween('workspaces.created_at', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $workspaces = $query->select(
                    'workspaces.*',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'admin_and_users.prefix',
                    'admin_and_users.name as a_name',
                    'admin_and_users.last_name',
                )->orderBy('id', 'desc');
            } else {
                $workspaces = $query->select(
                    'workspaces.*',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'admin_and_users.prefix',
                    'admin_and_users.name as a_name',
                    'admin_and_users.last_name',
                )->where('workspaces.branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'desc');
            }

            return DataTables::of($workspaces)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';

                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  ' . __("Action") . '</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="#"><i class="far fa-eye mr-1 text-primary"></i> ' . __('View') . '</a>';
                    $html .= '<a class="dropdown-item" href="' . route('workspace.task.index', [$row->id]) . '"><i class="fas fa-tasks text-primary"></i>  ' . __("Manage Tasks") . '</a>';
                    $html .= '<a class="dropdown-item" id="edit" href="' . route('workspace.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i>  ' . __("Edit") . '</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="' . route('workspace.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> ' . __("Delete") . ' </a>';


                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->editColumn('name', function ($row) {
                    return $row->name . ' <a class="btn btn-sm btn-info text-white" id="docs" href="' . route('workspace.view.docs', [$row->id]) . '">Docs</a>';
                })
                ->editColumn('from',  function ($row) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return '<b>Head Office</b>';
                    }
                })
                ->editColumn('start_date', function ($row) {
                    return date('d/m/Y', strtotime($row->start_date));
                })
                ->editColumn('end_date', function ($row) {
                    return date('d/m/Y', strtotime($row->end_date));
                })
                ->editColumn('assigned_by', function ($row) {
                    return $row->prefix . ' ' . $row->a_name . ' ' . $row->last_name;
                })
                ->rawColumns(['action', 'date', 'from', 'name', 'assigned_by'])
                ->make(true);
        }
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        $users = DB::table('admin_and_users')
            ->where('branch_id', auth()->user()->branch_id)
            ->get(['id', 'prefix', 'name', 'last_name']);
        return view('essentials.work_space.index', compact('branches', 'users'));
    }

    public function store(Request $request)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        // generate invoice ID
        $i = 4;
        $a = 0;
        $IdNo = '';
        while ($a < $i) {
            $IdNo .= rand(1, 9);
            $a++;
        }

        $addWorkspace = Workspace::insertGetId([
            'ws_id' => date('Y/') . $IdNo,
            'branch_id' => auth()->user()->branch_id,
            'name' => $request->name,
            'priority' => $request->priority,
            'status' => $request->status,
            'start_date' => date('Y-m-d', strtotime($request->start_date)),
            'end_date' => date('Y-m-d', strtotime($request->end_date)),
            'description' => $request->description,
            'estimated_hours' => $request->estimated_hours,
            'admin_id' => auth()->user()->id,
            'created_at' => date('Y-m-d'),
        ]);

        if (count($request->user_ids) > 0) {
            foreach ($request->user_ids as $user_id) {
                WorkspaceUsers::insert([
                    'workspace_id' => $addWorkspace,
                    'user_id' => $user_id
                ]);
            }
        }

        if ($request->file('documents')) {
            if (count($request->file('documents')) > 0) {
                foreach ($request->file('documents') as $document) {
                    $wpDocument = $document;
                    $wpDocumentName = uniqid() . '.' . $wpDocument->getClientOriginalExtension();
                    $wpDocument->move(public_path('uploads/workspace_docs/'), $wpDocumentName);
                    WorkspaceAttachment::insert([
                        'workspace_id' => $addWorkspace,
                        'attachment' => $wpDocumentName,
                        'extension' => $wpDocument->getClientOriginalExtension(),
                    ]);
                }
            }
        }

        return response()->json(__('Workspace created successfully.'));
    }

    public function edit($id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $ws = Workspace::with(['ws_users'])->where('id', $id)->first();
        $users = DB::table('admin_and_users')
            ->where('branch_id', auth()->user()->branch_id)
            ->get(['id', 'prefix', 'name', 'last_name']);
        return view('essentials.work_space.ajax_view.edit', compact('ws', 'users'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'start_date' => 'required',
            'start_date' => 'required',
        ]);

        $updateWorkspace = Workspace::with(['ws_users'])->where('id', $id)->first();
        $updateWorkspace->update([
            'branch_id' => auth()->user()->branch_id,
            'name' => $request->name,
            'priority' => $request->priority,
            'status' => $request->status,
            'start_date' => date('Y-m-d', strtotime($request->start_date)),
            'end_date' => date('Y-m-d', strtotime($request->end_date)),
            'description' => $request->description,
            'estimated_hours' => $request->estimated_hours,
        ]);

        foreach ($updateWorkspace->ws_users as $user) {
            $user->is_delete_in_update = 1;
            $user->save();
        }

        if (count($request->user_ids) > 0) {
            foreach ($request->user_ids as $user_id) {
                $existsUser = WorkspaceUsers::where('workspace_id', $id)
                    ->where('user_id', $user_id)->first();
                if ($existsUser) {
                    $existsUser->is_delete_in_update = 0;
                    $existsUser->save();
                } else {
                    WorkspaceUsers::insert([
                        'workspace_id' => $id,
                        'user_id' => $user_id
                    ]);
                }
            }
        }

        $deleteUsers = WorkspaceUsers::where('workspace_id', $id)->where('is_delete_in_update', 1)->get();
        foreach ($deleteUsers as $deleteUser) {
            $deleteUser->delete();
        }

        if ($request->file('documents')) {
            if (count($request->file('documents')) > 0) {
                foreach ($request->file('documents') as $document) {
                    $wpDocument = $document;
                    $wpDocumentName = uniqid() . '.' . $wpDocument->getClientOriginalExtension();
                    $wpDocument->move(public_path('uploads/workspace_docs/'), $wpDocumentName);
                    WorkspaceAttachment::insert([
                        'workspace_id' => $id,
                        'attachment' => $wpDocumentName,
                        'extension' => $wpDocument->getClientOriginalExtension(),
                    ]);
                }
            }
        }

        return response()->json(__('Workspace updated successfully.'));
    }

    public function delete(Request $request, $id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $deleteWorkspace = Workspace::where('id', $id)->first();
        if (!is_null($deleteWorkspace)) {
            $deleteWorkspace->delete();
        }

        return response()->json(__('Workspace deleted successfully.'));
    }

    public function viewDocs($id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $docs = DB::table('workspace_attachments')->where('workspace_id', $id)->get(['id', 'attachment', 'extension']);
        return view('essentials.work_space.ajax_view.view_documents', compact('docs'));
    }

    public function deleteDoc($docId)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $deleteDoc = WorkspaceAttachment::where('id', $docId)->first();
        if (!is_null($deleteDoc)) {
            if (file_exists(public_path('uploads/workspace_docs/' . $deleteDoc->attachment))) {
                unlink(public_path('uploads/workspace_docs/' . $deleteDoc->attachment));
            }
            $deleteDoc->delete();
        }

        return response()->json(__('Document deleted successfully.'));
    }
}
