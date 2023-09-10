<?php

namespace App\Http\Controllers\Auth;

use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function resetCurrentPassword(Request $request)
    {
        return response()->json('Feature is disabled in this demo');
        $this->validate($request,
        [
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        $adminUserHashtedPassword = auth()->user()->password;
        $checkHashtedPasswordWithOldPassword = Hash::check($request->current_password, $adminUserHashtedPassword);
        if ($checkHashtedPasswordWithOldPassword) {
            if (!Hash::check($request->password, $adminUserHashtedPassword)) {
                $user = AdminAndUser::find(Auth::user()->id);
                $user->password = Hash::make($request->password);
                $user->save();
                Auth::logout();
                return response()->json(['successMsg' => 'Successfully password has been changed.']);
            }else{
                return response()->json(['errorMsg' => 'Current password and new password is same.
                If you want to change your current password please enter a new password']);
            }
        } else {
            return response()->json(['errorMsg' => 'Current password does not matched']);
        }
    }
}

