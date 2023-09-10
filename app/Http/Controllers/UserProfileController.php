<?php

namespace App\Http\Controllers;

use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Intervention\Image\Facades\Image;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Profile index view
    public function index()
    {
        return view('users.profile');
    }

    // Update user profile
    public function update(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'email' => 'required|unique:admin_and_users,email,'.auth()->user()->id,
        ]);

        //return $request->all();
        $updateProfile =  AdminAndUser::where('id', auth()->user()->id)->first();
        $updateProfile->prefix = $request->prefix;
        $updateProfile->name = $request->first_name;
        $updateProfile->last_name = $request->last_name;
        $updateProfile->email = $request->email;
        $updateProfile->date_of_birth = $request->date_of_birth;
        $updateProfile->gender = $request->gender;
        $updateProfile->marital_status = $request->marital_status;
        $updateProfile->blood_group = $request->blood_group;
        $updateProfile->phone = $request->phone;
        $updateProfile->facebook_link = $request->facebook_link;
        $updateProfile->twitter_link = $request->twitter_link;
        $updateProfile->instagram_link = $request->instagram_link;
        $updateProfile->guardian_name = $request->guardian_name;
        $updateProfile->id_proof_name = $request->id_proof_name;
        $updateProfile->permanent_address = $request->permanent_address;
        $updateProfile->current_address = $request->current_address;
        $updateProfile->bank_ac_holder_name = $request->bank_ac_holder_name;
        $updateProfile->bank_ac_no = $request->bank_ac_no;
        $updateProfile->bank_name = $request->bank_name;
        $updateProfile->bank_identifier_code = $request->bank_identifier_code;
        $updateProfile->bank_branch = $request->bank_branch;
        $updateProfile->tax_payer_id = $request->tax_payer_id;
        $updateProfile->language = $request->language;
        $updateProfile->save();
        session(['lang' => $updateProfile->language]);

            return response()->json(__('Successfully user profile is updated'));
        

    }

    // View logged in user profile
    public function view($id)
    {
        $user = AdminAndUser::with(['role', 'department', 'designation'])->where('id', $id)->firstOrFail();
        // $firstName = str_split($user->name)[0];
        // $lastName = $user->last_name ? str_split($user->last_name)[0] : '';
        // $namePrefix = $firstName.' '.$lastName;
        return view('users.view_profile', compact('user'));
    }
}
