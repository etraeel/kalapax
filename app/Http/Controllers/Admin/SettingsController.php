<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:settings');
    }
    public function settings_show(){
        return view('admin.settings.index');
    }

    public function settings_update(Request $request){

        $data = $request->validate([
            'site_name' => 'required',
            'site_logo' => 'required',
            'site_email' => 'required',
            'site_phone_number' => 'required',
            'site_mobile_number' => 'required',
            'site_about_us' => 'required',
            'site_contact_us' => 'required',
            'site_instagram' => 'required',
            'site_telegram' => 'required',
            'site_twitter' => 'required',
            'site_linkedin' => 'required',
        ]);
        if($data){
            $site_name = Setting::where('name' , 'site_name')->first();
            $site_name->value = $request->site_name;
            $site_name->save();

            $site_alert_inventory_number = Setting::where('name' , 'site_alert_inventory_number')->first();
            if($request->site_alert_inventory_number == null){$site_alert_inventory_number->value = 3;}
            else{$site_alert_inventory_number->value = $request->site_alert_inventory_number;}
            $site_alert_inventory_number->save();

            $site_get_sms_new_order = Setting::where('name' , 'site_get_sms_new_order')->first();
            if($request->site_get_sms_new_order == null){$site_get_sms_new_order->value = 0;}
            if($request->site_get_sms_new_order == 'on'){$site_get_sms_new_order->value = 1;}
            $site_get_sms_new_order->save();


            $site_get_sms_new_message = Setting::where('name' , 'site_get_sms_new_message')->first();
            if($request->site_get_sms_new_message == null){$site_get_sms_new_message->value = 0;}
            if($request->site_get_sms_new_message == 'on'){$site_get_sms_new_message->value = 1;}
            $site_get_sms_new_message->save();

            $site_get_sms_alert_inventory = Setting::where('name' , 'site_get_sms_alert_inventory')->first();
            if($request->site_get_sms_alert_inventory == null){$site_get_sms_alert_inventory->value = 0;}
            if($request->site_get_sms_alert_inventory == 'on'){$site_get_sms_alert_inventory->value = 1;}
            $site_get_sms_alert_inventory->save();

            $site_mobile_number = Setting::where('name' , 'site_mobile_number')->first();
            $site_mobile_number->value = $request->site_mobile_number;
            $site_mobile_number->save();

            $site_name = Setting::where('name' , 'site_instagram')->first();
            $site_name->value = $request->site_instagram;
            $site_name->save();

            $site_name = Setting::where('name' , 'site_telegram')->first();
            $site_name->value = $request->site_telegram;
            $site_name->save();

            $site_name = Setting::where('name' , 'site_twitter')->first();
            $site_name->value = $request->site_twitter;
            $site_name->save();

            $site_name = Setting::where('name' , 'site_linkedin')->first();
            $site_name->value = $request->site_linkedin;
            $site_name->save();

            $site_email = Setting::where('name' , 'site_email')->first();
            $site_email->value = $request->site_email;
            $site_email->save();

            $site_phone_number = Setting::where('name' , 'site_phone_number')->first();
            $site_phone_number->value = $request->site_phone_number;
            $site_phone_number->save();

            $site_logo = Setting::where('name' , 'site_logo')->first();
            $site_logo->value = $request->site_logo;
            $site_logo->save();

            $site_about_us = Setting::where('name' , 'site_about_us')->first();
            $site_about_us->value = $request->site_about_us;
            $site_about_us->save();

            $site_contact_us = Setting::where('name' , 'site_contact_us')->first();
            $site_contact_us->value = $request->site_contact_us;
            $site_contact_us->save();

            alert()->success('ویرایش انجام شد ');
            return back();
        }
        if(! $data){
            alert()->success('لطفا مقادیر را چک کنید !!');
            return back();
        }

    }


    public function questions_show(){
        return view('admin.settings.questions');

    }

    public function questions_update(Request $request){
        $data = $request->validate([
            'text' => 'required'
        ]);
        if($data){
            $question = Setting::where('name' , 'question')->first();
            $question->value = $request->text;
            $question->save();

            alert()->success('ویرایش انجام شد ');
            return back();
        }
    }
}
