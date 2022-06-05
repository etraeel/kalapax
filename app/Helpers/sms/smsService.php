<?php


namespace App\Helpers\sms;

use Highlight\Mode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class smsService
{

    public function send($receiver, $message)
    {
        $data_to_send = [
            "message" => $message,
            "receptor" => $receiver,
            "sender" => env('GHASEDAK_SENDER'),
        ];

        // Sending SMS Using Laravel Http Client
        $send_sms = Http::withHeaders([
            "apikey" => env('GHASEDAK_APP_KEY'),
        ])->asForm()->post("http://api.ghasedaksms.com/v2/sms/send/simple", $data_to_send);

        // Return Json Response
        // To Know More About All Possible Responses, Please Visit : https://ghasedak.io/docs
        return $send_sms->json();

    }

}
