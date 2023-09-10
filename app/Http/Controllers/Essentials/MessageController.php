<?php

namespace App\Http\Controllers\Essentials;

use App\Http\Controllers\Controller;
use App\Models\Essential\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        if (auth()->user()->permission->essential['msg'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        return view('essentials.messages.index');
    }

    public function store(Request $request)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [

            'description' => 'required',
        ]);

        Message::insertGetId([
            'description' => $request->description,
            'branch_id' => auth()->user()->branch_id,
            'user_id' => auth()->user()->id,
            'created_at' => Carbon::now(),
        ]);

            return response()->json(__('Message sent successfully.'));


    }

    public function delete($id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $deleteMsg = Message::where('id', $id)->first();
        if (!is_null($deleteMsg)) {
            $deleteMsg->delete();
        }

            return response()->json(__('Message deleted successfully.'));
        
    }

    public function allMessage()
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $messages = DB::table('messages')
        ->leftJoin('admin_and_users', 'messages.user_id', 'admin_and_users.id')
        ->where('messages.branch_id', auth()->user()->branch_id)
        ->select(
            'messages.id',
            'messages.user_id',
            'messages.description',
            'messages.created_at',
            'admin_and_users.prefix as u_prefix',
            'admin_and_users.name as u_name',
            'admin_and_users.last_name as u_last_name',
        )
        ->get();
        return view('essentials.messages.ajax_view.message_list', compact('messages'));
    }
}
