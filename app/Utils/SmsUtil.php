<?php

namespace App\Utils;

class SmsUtil
{
  public function singleSms($sale)
  {
    $msg = 'Mr. ' . $sale->customer->name . ' Your purchase amount is ' . $sale->total_payable_amount . ', total paid ' . $sale->paid . ', total due ' . $sale->paid;
    $url = env('SMS_URL');
    $data = [
      "api_key" => env('API_KEY'),
      "type" => env('SMS_TYPE'),
      "contacts" => $sale->customer->phone,
      "senderid" => env('SENDER_ID'),
      "msg" => $msg,
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
  }
}
