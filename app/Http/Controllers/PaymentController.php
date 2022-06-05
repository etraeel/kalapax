<?php

namespace App\Http\Controllers;

use App\Address;
use App\Discount;
use App\Helpers\Cart\Cart;
use App\Helpers\Message\Message;
use App\Helpers\sms\sms;
use App\Payment;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PaymentController extends Controller
{
    public function payment(Request $request)
    {

        $cartItems = Cart::all();
        $address = Address::find($request->address_selected);
        $orderDetails = 'مقصد : ' . ' استان : ' . $address->province->name . '/ شهر : ' . $address->city->name . '/ نشانی : ' . $address->description . '/ کد پستی : ' . $address->postal_code . '/ شماره تماس : ' . $address->home_number . '/  ' . Auth::user()->name;

        if ($cartItems->count()) {

            $price = $this->checkPriceTotal(session()->get('discount'));


            $orderItems = $cartItems->mapWithKeys(function ($cart) {
                return [$cart['price']->id => ['quantity' => $cart['quantity']]];
            });

            $order = auth()->user()->orders()->create([
                'status' => 'unpaid',
                'price' => $price,
                'description' => $orderDetails
            ]);

            $order->prices()->attach($orderItems);

            $token = config('services.payping.token');
            $res_number = Str::random();
            $args = [
                //  "amount" => $price ,
                "amount" => $price,
                "payerName" => auth()->user()->email,
                "returnUrl" => route('payment.callback'),
                "clientRefId" => $res_number
            ];

            $payment = new \PayPing\Payment($token);

            try {
                $payment->pay($args);
            } catch (\Exception $e) {
                throw $e;
            }
            //echo $payment->getPayUrl();
            $order->payments()->create([
                'resnumber' => $res_number,
                'price' => $price
            ]);


            return redirect($payment->getPayUrl());
        }

        // alert()->error();
        return back();
    }

    public function callback(Request $request)
    {
        $payment = Payment::where('resnumber', $request->clientrefid)->firstOrFail();

        $token = config('services.payping.token');

        $payping = new \PayPing\Payment($token);

        try {
            // $payment->order->price
            if ($payping->verify($request->refid, $payment->order->price)) {
                $payment->update([
                    'status' => 1
                ]);

                $payment->order()->update([
                    'status' => 'paid'
                ]);

                if(Setting::where('name', 'site_get_sms_new_order')->first()->value == 1){
                    sms::send(Setting::where('name', 'site_mobile_number')->first()->value , " با سلام \n شما یک سفارش جدید به شماره ". $payment->order->id ." دارید \n لطفا به سایت مراجعه کنید \n " . Setting::where('name', 'site_name')->first()->value);
                }

                sms::send(Auth::user()->mobile , "با سلام \n سفارش شما به شماره ". $payment->order->id ." با موفقیت ثبت شد \n درخواست شما در حال بررسی میباشد  \n " . Setting::where('name', 'site_name')->first()->value);

                foreach (Cart::all() as $cart) {
                    $cart['price']->update([
                        'inventory' => $cart['price']->inventory - $cart['quantity'],
                    ]);
                    if (Setting::where('name', 'site_get_sms_alert_inventory')->first()->value == 1) {
                        if ($cart['price']->inventory <= Setting::where('name', 'site_alert_inventory_number')->first()->value) {
                            sms::send(Setting::where('name', 'site_mobile_number')->first()->value , " با سلام \n موجودی محصول ". $cart['price']->product->name ." در حال تمام شدن است \n لطفا نسبت به تهیه محصول اقدام نمایید " . Setting::where('name', 'site_name')->first()->value);
                        }
                    }
                }


                if (session()->get('discount') != null) {
                    if (session()->get('totalDiscountUsed') != null) {
                        $discount = Discount::where('code', session()->get('discount'))->first();
                        $number_discount = $discount->number_discount;
                        $discount->forceFill([
                            'number_discount' => $number_discount + (int)session()->get('totalDiscountUsed')
                        ])->save();
                    }
                }
                session()->forget('discount');
                session()->forget('totalDiscountUsed');
                Cart::flush();

                alert()->success('پرداخت شما موفق بود');
                return redirect(route('profile.orders'));
            } else {
                alert()->error('پرداخت شما تایید نشد');
                return redirect(route('profile.orders'));
            }
        } catch (\Exception $e) {
            $errors = collect(json_decode($e->getMessage(), true));

            alert()->error($errors->first());
            return redirect(route('profile.orders'));
        }
    }

    public function checkPriceTotal($discountCode)
    {
        $cartItems = Cart::all();
        $priceTotal = 0;
        $discount = Discount::where('code', $discountCode)->first();
        $user = Auth::user()->id;

        if ($discount) {

            $totalDiscountUsed = 0;
            $number_discount = $discount->number_discount_fixed - $discount->number_discount;

            if ($discount->users()->count()) {
                if (!in_array($user, $discount->users->pluck('id')->toArray())) {
                    return $this->checkPriceWithoutDiscount();
                }
            }

            if ($discount->expired_at < now()) {
                return $this->checkPriceWithoutDiscount();
            }

            foreach ($cartItems as $cart) {
                $quantity = $cart['quantity'];
//                dd($cart);
                $product = $cart['price'];

                if ($discount->products()->count()) {
                    if (!in_array($product->id, $discount->products->pluck('id')->toArray())) {
                        $priceTotal += $quantity * $product->off_price;
                        break;
                    }
                }

                if ($discount->categories()->count()) {
                    if (!in_array($product->id, $discount->categories->pluck('id')->toArray())) {
                        $priceTotal += $quantity * $product->off_price;
                        break;
                    }
                }

                $priceWithDiscount = $product->off_price * ((100 - $discount->percent) / 100);
                $margin = $product->off_price * (($discount->percent) / 100);
                $max_discount_price = $discount->max_discount_price;
                if ($max_discount_price != 0) {
                    if ($max_discount_price > $margin) {
                        do {
                            if ($number_discount != 0) {
                                $priceTotal += $priceWithDiscount;
                                $number_discount--;
                                $totalDiscountUsed++;
                            } else {
                                $priceTotal += $product->off_price;
                            }
                            $quantity--;
                        } while ($quantity);

                    } else {
                        do {
                            if ($number_discount != 0) {
                                $priceTotal += $product->off_price - $max_discount_price;
                                $number_discount--;
                                $totalDiscountUsed++;
                            } else {
                                $priceTotal += $product->off_price;
                            }
                            $quantity--;
                        } while ($quantity);
                    }
                } else {
                    do {
                        if ($number_discount != 0) {
                            $priceTotal += $priceWithDiscount;
                            $number_discount--;
                            $totalDiscountUsed++;
                        } else {
                            $priceTotal += $product->off_price;
                        }
                        $quantity--;
                    } while ($quantity);
                }
            }

            session()->put('discount', $discountCode);
            session()->put('totalDiscountUsed', $totalDiscountUsed);
            return $priceTotal;

        } else {
            return $this->checkPriceWithoutDiscount();

        }

    }

    public function checkPriceWithoutDiscount()
    {
        $cartItems = Cart::all();
        $priceTotal = $cartItems->sum(function ($cart) {
            return $cart['price']->off_price * $cart['quantity'];
        });
        return $priceTotal;
    }
}
