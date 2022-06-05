<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Helpers\Cart\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Str;

class DiscountController extends Controller
{

    public function checkCode(Request $request)
    {
        $priceTotal = 0;
        $discount = Discount::where('code', $request->code)->first();
        session()->put('discount' , $request->code);
        $allCart = Cart::all();

        if (Auth::user()){
            $user = Auth::user()->id;
        }else{
            return response([
                'price_pay' => null,
                'status' => 'برای اعمال کد تخفیف لطفا وارد حساب کاربری خود شوید !'
            ]);
        }

        if ($discount) {

            $number_discount = $discount->number_discount_fixed - $discount->number_discount;

            if($discount->number_discount_fixed == $discount->number_discount){
                return response([
                    'price_pay' => null,
                    'status' => 'ظرفیت کد تخفیف تکمیل شده است !'
                ]);
            }
            if ($discount->users()->count()) {
                if (!in_array($user, $discount->users->pluck('id')->toArray())) {
                    return response([
                        'price_pay' => null,
                        'status' => 'شما قادر به استفاده از این کد تخفیف نیستید'
                    ]);
                }
            }

            if ($discount->expired_at < now()) {
                return response([
                    'price_pay' => null,
                    'status' => 'مهلت استفاده از این کد به پایان رسیده است'
                ]);

            }
            foreach ($allCart as $cart) {
                $quantity = $cart['quantity'];
                $price = $cart['price'];
                $product = $cart['price']->product;

                if ($discount->products->count()) {
                    if (!in_array($product->id, $discount->products->pluck('id')->toArray())) {
                        $priceTotal += $quantity * $price->off_price;
                        break;
                    }
                }

                if ($discount->categories()->count()) {
                    if (!in_array($product->id, $discount->categories->pluck('id')->toArray())) {
                        $priceTotal += $quantity * $price->off_price;
                        break;
                    }
                }

                $priceWithDiscount = $price->off_price * ((100 - $discount->percent) / 100);
                $margin = $price->off_price * (($discount->percent) / 100);
                $max_discount_price = $discount->max_discount_price;
                if ($max_discount_price != 0) {
                    if ($max_discount_price > $margin) {
                        do {
                            if ($number_discount != 0) {
                                $priceTotal += $priceWithDiscount;
                                $number_discount--;
                            } else {
                                $priceTotal += $price->off_price;
                            }
                            $quantity--;
                        } while ($quantity);

                    } else {
                        do {
                            if ($number_discount != 0) {
                                $priceTotal += $price->off_price - $max_discount_price;
                                $number_discount--;
                            } else {
                                $priceTotal += $price->off_price;
                            }
                            $quantity--;
                        } while ($quantity);
                    }
                } else {
                    do {
                        if ($number_discount != 0) {
                            $priceTotal += $priceWithDiscount;
                            $number_discount--;
                        } else {
                            $priceTotal += $price->off_price;
                        }
                        $quantity--;
                    } while ($quantity);
                }
            }

            return response([
                'price_pay' => round($priceTotal),
                'status' => 'کد تخفیف با موفقیت اعمال شد '
            ]);
        }

        return response([
            'price_pay' => null,
            'status' => 'کد تخفیف نامعتبر میباشد !'
        ]);
    }

}
