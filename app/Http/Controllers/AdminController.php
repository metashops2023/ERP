<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::get();
        return view('central.users', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('central.adduser');
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        return redirect("/")->with('message', 'Saved...');
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('central.edituser', [
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = $request->password != null ? bcrypt($request->password) : $user->password;
        $user->save();
        return redirect("/")->with('message', 'Saved...');
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->first();
        $user->delete();
        return redirect("/")->with('message', 'Saved...');
    }
}
