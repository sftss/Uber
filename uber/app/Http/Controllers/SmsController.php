<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Twilio\Rest\Client;

class SmsController extends Controller
{
    public function sendSms()
    {
        $sid    = "AC11bcb595e9cae985f970d5330cd0379e";
        $token  = "0603be6bae7610fdcffde0d5b0ca0775";
        $twilio = new Client($sid, $token);
        $message = $twilio->messages
        ->create("+330768489374", // le numéro à qui on envoie le message
            array(
            "messagingServiceSid" => "MG2d2bf1ec4afb557eec718e40ab276780",
            "body" => "Ahoy 👋",
            "from" => "+12186568392"
            )
        );

        dd("le message a bien été envoyé");
    }
}
