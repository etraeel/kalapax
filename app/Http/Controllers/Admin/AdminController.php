<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Order;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function index()
    {
        $all7Days = [];
        for ($i = 7 ; $i > 0 ; $i--){
            $sell = Payment::where('status' , 1)->whereBetween('updated_at', [Carbon::today()->subDays($i),Carbon::today()->subDays($i-1) ])->get();
            $sellTotal = 0;
            if($sell->first != null) {
                foreach ($sell as $payment) {
                    $sellTotal += (int)$payment->order['price'];
                }
            }
            $all7Days[today()->subDays($i)->format('Y-m-d').''] = $sellTotal;
        }

        $all7Days = collect($all7Days);

        $ghasedakCredit =  $this->getCredit();
        return view('admin.index' , compact('ghasedakCredit' , 'all7Days'));
    }

    public function getCredit()
    {

        $send_sms = Http::withHeaders([
            "apikey" => env('GHASEDAK_APP_KEY'),
        ])->asForm()->post("http://api.ghasedaksms.com/v2/credit" );

        return $send_sms->json()['credit'];
    }
}
