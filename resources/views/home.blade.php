@extends('layouts.master')
@section('content')

   @if(count($offProducts) > 0)
    <div class="best_offer">
        <div class="title">
            <span>همیشه تخفیف</span>
            <div></div>
        </div>
        <div class="body owl-carousel">

            @foreach($offProducts as $item)
                <div class="item">
                    <img src="{{$item->product->image}}" alt="">
                    <a class="item_title" target="tab" href="{{route('product.single', $item->product->slug) }}"><span>{{$item->product->name}}</span></a>
                    <a class="item_description" target="tab" href="{{route('product.single', $item->product->slug) }}"><span>{{$item->product->description}}</a></span>
                    <div class="price">

                        <div class="price_no_off">
                            <span>{{e2f($item->price) ?? 0}}</span>
                            <div class="percent">
                                <span>{{e2f( ((int)($item->price - (int)$item->off_price)/(int)$item->price)*100 )}}</span>
                                <span>%</span>
                            </div>

                        </div>

                        <div class="price_off">
                            <span class="number">{{e2f($item->off_price) ?? 0}}</span>
                            <span class="unit">تومان</span>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>
    </div>
   @endif
    <div class="banners">

{{--        <div class="banner_group2">--}}
{{--            <img class="banner2" src="img/banner6.jpg" alt="">--}}
{{--            <img class="banner2" src="img/banner9.jpg" alt="">--}}
{{--        </div>--}}
        <div class="banner_group1">
            @foreach($banners as $banner)
                <a target="_blank" href="{{ $banner->link == null ? '' :  (\Illuminate\Support\Str::contains($banner->link ,["http://" , "https://"]) ? '' : 'http://') . $banner->link}}">
                    <img class="banner1" src="{{asset($banner->url)}}" alt="{{$banner->link}}">
                </a>
            @endforeach
        </div>

    </div>
    <div class="best_offer">
        <div class="title">
            <span>جدیدترین محصولات</span>
            <div></div>
        </div>
        <div class="body owl-carousel">

            @foreach($newProducts as $product)
                <div class="item">
                    <img src="{{$product->image}}" alt="">
                    <a class="item_title" target="tab" href="{{route('product.single', $product->slug) }}"><span>{{$product->name}}</span></a>
                    <a class="item_description" target="tab" href="{{route('product.single', $product->slug) }}"><span>{{$product->description}}</a></span>
                   @if($product->price != null && $product->price->price != 0 && $product->price->inventory > 0)
                    <div class="price">
                        @if($product->price->price != $product->price->off_price)
                            <div class="price_no_off">
                            <span>{{ e2f($product->price->price ?? 0)}}</span>
                            <div class="percent">
                                <span>{{e2f( ((int)($item->price - (int)$item->off_price)/(int)$item->price)*100 )}}</span>
                                <span>%</span>
                            </div>
                        </div>
                        @endif
                        <div class="price_off">
                            <span class="number">{{e2f($product->price->off_price)}}</span>
                            <span class="unit">تومان</span>
                        </div>
                    </div>
                    @else
                        <div class="price_off">
                            <span class="number">ناموجود</span>
                        </div>
                   @endif
                </div>
            @endforeach

        </div>
    </div>

@endsection
@section('script')
    <script>

        $(document).ready(function () {
            $(".owl-carousel").owlCarousel({
                loop: true ,
                rtl: true,
                padding: 10,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1.5,
                        center: true
                    },
                    600: {
                        items: 2,

                    },
                    780: {
                        items: 2.5,
                        center: true
                    },
                    1000: {
                        items: 3,
                    },
                    1180: {
                        items: 4,
                    },
                    1800: {
                        items: 5,
                    }
                }
            });


        });
    </script>

@endsection
