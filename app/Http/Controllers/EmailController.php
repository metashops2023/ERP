<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function emailSettings(Request $request)
    {
        return view('communication.email.settings.index');
    }

    public function emailSettingsStore(Request $request)
    {
        $MAIL_FROM_NAME = str_replace('"', '', $request->get('MAIL_FROM_NAME'));
        $MAIL_FROM_ADDRESS = str_replace('"', '', $request->get('MAIL_FROM_ADDRESS'));
        $MAIL_ENCRYPTION = str_replace('"', '', $request->get('MAIL_ENCRYPTION'));
        $MAIL_PASSWORD = str_replace('"', '', $request->get('MAIL_PASSWORD'));
        $MAIL_USERNAME = str_replace('"', '', $request->get('MAIL_USERNAME'));
        $MAIL_PORT = str_replace('"', '', $request->get('MAIL_PORT'));
        $MAIL_HOST = str_replace('"', '', $request->get('MAIL_HOST'));
        $MAIL_MAILER = str_replace('"', '', $request->get('MAIL_MAILER'));
        $MAIL_ACTIVE = isset($request->MAIL_ACTIVE) ? 'true' : 'false';

        Artisan::call("env:set MAIL_MAILER='" . $MAIL_MAILER . "'");
        Artisan::call("env:set MAIL_HOST='" . $MAIL_HOST . "'");
        Artisan::call("env:set MAIL_PORT='" . $MAIL_PORT . "'");
        Artisan::call("env:set MAIL_USERNAME='" . $MAIL_USERNAME . "'");
        Artisan::call("env:set MAIL_PASSWORD='" . $MAIL_PASSWORD . "'");
        Artisan::call("env:set MAIL_ENCRYPTION='" . $MAIL_ENCRYPTION  . "'");
        Artisan::call("env:set MAIL_FROM_ADDRESS='" . $MAIL_FROM_ADDRESS . "'");
        Artisan::call("env:set MAIL_FROM_NAME='" . $MAIL_FROM_NAME . "'");
        Artisan::call("env:set MAIL_ACTIVE='" . $MAIL_ACTIVE . "'");


            return response()->json(__('Email settings updated successfully'));

    }
}
