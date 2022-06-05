<?php


namespace App\Notifications\channels;

use Ghasedak\GhasedakApi;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;


class ghasedakChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->message;
        $receptor = $notification->receptor;

        $data_to_send = [
            "message" => $message,
            "receptor" => $receptor,
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



