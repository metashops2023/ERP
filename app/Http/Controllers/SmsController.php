<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function smsSettings(Request $request)
    {
        return view('communication.sms.settings.index');
    }

    public function smsSettingsStore(Request $request)
    {
        $SMS_URL = str_replace('"', '', $request->get('SMS_URL'));
        $API_KEY = str_replace('"', '', $request->get('API_KEY'));
        $SENDER_ID = str_replace('"', '', $request->get('SENDER_ID'));
        $SMS_ACTIVE = isset($request->SMS_ACTIVE) ? 'true' : 'false';

        Artisan::call("env:set SMS_URL='" . $SMS_URL . "'");
        Artisan::call("env:set API_KEY='" . $API_KEY . "'");
        Artisan::call("env:set SENDER_ID='" . $SENDER_ID . "'");
        Artisan::call("env:set SMS_ACTIVE='" . $SMS_ACTIVE . "'");

        return response()->json('SMS settings updated successfully');
    }
}
