<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Twilio\Rest\Client;

class SmsController extends Controller
{
    public function sendSms()
    {
        $basic  = new \Vonage\Client\Credentials\Basic("35bef7e7", "W0CmeO6pTSFFw66M");
        $client = new \Vonage\Client($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS("33652656065", "Uber_s231", 'A text message sent using the Nexmo SMS API') //contenu du message
        );
        
        $message = $response->current();
        
        if ($message->getStatus() == 0) {
            dd("The message was sent successfully\n");
        } else {
            dd("The message failed with status: " . $message->getStatus() . "\n");
        }
    }
}
