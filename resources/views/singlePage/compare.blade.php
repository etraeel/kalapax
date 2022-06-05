@extends('layouts.master')
@section('header')

@endsection
@section('content')

    <div class="compare">
        <div class="head">
            <h4>مقایسه محصولات</h4>
        </div>

        <div class="body">
            <div class="items">
                @foreach($products as $product)
                    <div class="item">
                        <img src="{{asset($product->image)}}" alt="">
                        <a target="_blank" href="{{route('product.single' , $product->slug)}}">
                            <span class="name">{{$product->name}}</span>
                        </a>
                        <form action="{{route('cart.add')}}" method="post" id="add_to_cart">
                            @csrf
                            <input type="hidden" name="priceId" value="{{$product->price->id}}">
                        </form>
                        <span class="description">{{$product->description}}</span>
                        <span class="compare_price"> {{ e2f($product->price->off_price) ?? 0}} تومان</span>
                        <span onclick="document.getElementById('add_to_cart').submit()" class="add_to_cart btnn">افزودن به سبد خرید</span>
                    </div>
                @endforeach
            </div>

            @php
                $attributes = collect([]);
               foreach ($products as $product){
                   foreach ($product->attributes as $attr){
                       if(! $attributes->has($attr->name)){
                           $attributes->put($attr->name , []);
                   }
               }
              }

                 foreach ($attributes->keys() as $attribute){
                    foreach ($products as $product){
                        if( $product->attributes->contains('name' , $attribute)){
                            $attrValue = '';
                            for($i = 0; $i < count($product->attributes); $i++ ) {
                                if ($product->attributes[$i]->name == $attribute) {
                                        $index =  $i;
                                        if($attrValue != ''){
                                            $attrValue = $attrValue . " / " . $product->attributes[$index]->pivot->value->value;
                                        }else{
                                            $attrValue =  $product->attributes[$index]->pivot->value->value;
                                        }
                                    }
                                }
                            $attributes[$attribute] = array_merge($attributes[$attribute],[$attrValue]);
                        }
                        else{
                             $attributes[$attribute] = array_merge($attributes[$attribute],['---']);
                        }
                    }
                 }
            @endphp


            <div class="attributes">

                @foreach($attributes as $attr =>$values)
                    <div class="attributes_item">
                        <div class="title">
                            <span>{{$attr}}</span>
                        </div>
                        <div class="values">
                            @foreach($values as $value)
                                <span class="value">{{$value}}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

@endsection
@section('script')

    <script>

    </script>
@endsection

