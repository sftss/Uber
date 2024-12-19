<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Twilio\Rest\Client;

class SmsController extends Controller
{
    public function sendSms()
    {
        $sid    = "AC11bcb595e9cae985f970d5330cd0379e";
        $token  = "3e6afece6b01c11fd2c350dbb92e460c";
        $twilio = new Client($sid, $token);
        $message = $twilio->messages
        ->create("+33768489374", // to
            array(
            "messagingServiceSid" => "MG2d2bf1ec4afb557eec718e40ab276780",
            "body" => "Ahoy 👋",
                "from" => "+12186558491"
            )
      );

        dd("le message a bien été envoyé");
    }
}
