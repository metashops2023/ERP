<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{

    public function index()
    {
        $tenants = Tenant::get();
        return view('central.index', ['tenants' => $tenants]);
    }

    public function create(Request $request)
    {
        return view('central.create');
    }

    public function edit(Request $request)
    {
        $tenant = Tenant::where('id', $request->id)->first();
        return view('central.edit', [
            'tenant' => $tenant,
        ]);
    }

    public function store(Request $request)
    {
        $tenant = Tenant::create([
            'id' => $request->name,
            'name' => $request->name,
            'plan' => $request->plan,
            'registrationdate' => $request->registrationdate
        ]);
        $tenant->domains()->create(['domain' => "$request->name.erp.metashops.com.sa"]);

        return redirect("/")->with('message', 'Saved...');
    }

    public function update(Request $request)
    {

        $tenant = Tenant::where('id', $request->name)->first();
        $tenant->plan = $request->plan;
        $tenant->registrationdate = $request->registrationdate;
        $tenant->save();

        // $tenant->domains()->create(['domain' => "$request->name.metashops.com.sa"]);

        return redirect("/")->with('message', 'Saved...');
    }
    
    public function suspend(Request $request)
    {

        $tenant = Tenant::where('id', $request->id)->first();
        $tenant->putDownForMaintenance();

        return redirect("/")->with('message', 'Saved...');
    }
    
    public function unsuspend(Request $request)
    {

        $tenant = Tenant::where('id', $request->id)->first();
        $tenant->update(['maintenance_mode' => null]);

        return redirect("/")->with('message', 'Saved...');
    }

    public function delete(Request $request)
    {
        $tenant = Tenant::where('id', $request->id)->first();
        $tenant->delete();

        return redirect("/")->with('message', 'Saved...');
    }

    public function login(Request $request)
    {
        return view('central.login');
    }

    public function authenticate(Request $request)
    {
        $formFields = $request->validate([
            // 'email' => ['required', 'email'],
            'email' => ['required'],
            'password' => 'required'
        ]);
        if (auth('user')->attempt($formFields)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('message', 'You are now logged in!');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out!');
    }
}
