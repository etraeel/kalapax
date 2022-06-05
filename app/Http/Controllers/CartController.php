<?php

namespace App\Http\Controllers;

use App\Helpers\Cart\Cart;
use App\Price;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index()
    {

        session()->put('discount' , 'nullDiscount');


        return view('singlePage.cart');
    }

    public function addToCart(Request $request){

        $price = Price::find($request->priceId);
        if(! Cart::has($price)){
            Cart::put([
                'quantity'=> 1
            ],$price);
        }
        return redirect()->back();
    }

    public function update(Request $request)
    {

        if(Cart::has($request->id)){
           if($request->quantity != null){
               Cart::update($request->id , $request->quantity , 'quantity');
           }

            if($request->price != null){
                Cart::update($request->id , $request->price , 'price');
            }

        }

    }

    public function delete(Request $request)
    {

        if(Cart::has($request->id)){
            Cart::delete($request->id);
        }
    }

    public function selectAddress()
    {
        $addresses = Auth::user()->addresses;
//        session()->put('discount' , $request->discount);
        return view('singlePage.cartSelectAddress' , compact('addresses'));
    }
}
