@extends('layouts.master')
@section('header')
    <link rel="stylesheet" href="{{asset('./css/magnify.css')}}">
@endsection
@section('content')
    <div id="app" class="product">

        <div class="product_header">
            <div class="product_title">
                <h1>{{$product->name}}</h1>

                <input id="product_id" type="hidden" value="{{$product->id}}">
                <input id="product_rate" type="hidden" value="{{$product->rate == null ? 0 : $product->rate->avg('rate')}}">
            </div>
            <div class="product_details">
                <div class="product_details_1">
                    <div class="product_star">
                        {{--                        <i class="fal fa-star"></i>--}}
                        <div class="stars">
                            <form action="">
                                <input class="star star-5" :checked="rate == 5" id="star-5" type="radio" name="star"/>
                                <label @click="submitRate(5)" class="star star-5" for="star-5"></label>
                                <input class="star star-4" :checked="4 <= rate && rate < 5" id="star-4" type="radio"
                                       name="star"/>
                                <label @click="submitRate(4)" class="star star-4" for="star-4"></label>
                                <input class="star star-3" :checked="3 <= rate && rate < 4" id="star-3" type="radio"
                                       name="star"/>
                                <label @click="submitRate(3)" class="star star-3" for="star-3"></label>
                                <input class="star star-2" :checked="2 <= rate && rate < 3" id="star-2" type="radio"
                                       name="star"/>
                                <label @click="submitRate(2)" class="star star-2" for="star-2"></label>
                                <input class="star star-1" :checked="0 < rate && rate < 2" id="star-1" type="radio"
                                       name="star"/>
                                <label @click="submitRate(1)" class="star star-1" for="star-1"></label>
                            </form>
                        </div>

                        <span class="product_rate">@{{farsi(Math.round(rate * 10)/10)}}</span>
                        @if(count($product->rate) != 0)
                            <span class="product_rate_counter">( {{e2f(count($product->rate))}} نفر )</span>
                        @endif
                    </div>
                    <div class="product_comments">
                        <a href=""><span class="translate">{{count($product->comments)}} دیدگاه</span></a>
                    </div>

                </div>
                <div class="product_details_2">
                    {{--                    <div class="product_brand">--}}
                    {{--                        <span>برند : اپل</span>--}}
                    {{--                    </div>--}}
                    <div class="product_category">
                        <span>دسته بندی : </span>
                        @if( $product->categories)
                            @foreach( $product->categories as $cate)
                                <a href="{{route('products' , $cate)}}">{{ $cate->name }} / </a>
                            @endforeach
                        @endif

                    </div>


                </div>
            </div>
        </div>
        <div class="product_body">
            <div class="product_description">
                <div class="product_description_title">
                    <span>ویژگی های محصول</span>
                </div>
                <div class="product_description_body">

                    @if(! is_null($product->attributes) )
                        @foreach( attributeAnalysis($product->id) as $attr => $values)
                            <div class="product_description_body_item">
                                <span> {{$attr}} : </span>
                                <span>{{implode(' / ', $values)}}</span>
                            </div>
                        @endforeach
                    @else

                        <div class="product_description_body_item">
                           <span>
                                برای این محصول ویژگی ثبت نشده است.
                           </span>
                        </div>
                    @endif


                </div>
            </div>
            <div class="product_images">
                <div class="master_img">
                    <img id="master_image"
                         src="{{count($product->gallery) >0 ? url($product->gallery->first()->image) : asset('/img/no-image.png')}}"
                         class="zoom"
                         data-magnify-src="{{count($product->gallery) >0 ? url($product->gallery->first()->image) : asset('/img/no-image.png')}}">

                </div>
                <div class="other_img">
                    @if(count($product->gallery) >0)
                        @foreach($product->gallery as $image)
                            <img src="{{url($image->image)}}" alt="{{$image->alt}}" class="other_img_item">
                        @endforeach
                    @else
                        <img src="{{asset('/img/no-image.png')}}" alt="no-image" class="other_img_item">
                    @endif
                </div>
            </div>
            <div class="product_price_details">
                <div hidden style="position: absolute;width: 1px; height: 1px" class="prices" data-prices="{{ json_encode($product->prices)}}"></div>
                <div>
                    @if(count($product->prices) > 1)
                        <div class="attribute">
                            <label for="product_attribute">{{\App\Attribute::find($product->price->attribute)->name}} :</label>
                            <select name="" id="product_attribute">
                                @foreach($product->prices as $item)
                                    @if($item->inventory > 0)
                                        <option value="{{$item->value}}" {{$item->status == 1 ? 'selected' : ''}}>{{\App\AttributeValue::find($item->value)->value}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endif
                        <div class="product_status">
                            <span class="status">وضعیت :</span>
                            @if($product->inventory()>0)
                                <span class="status_ok">موجود</span>
                            @else
                                <span class="status_notok">ناموجود</span>
                            @endif
                        </div>
                    <div class="price_section">
                        @if($product->price != null )
                        @if($product->price->price != $product->price->off_price)
                            <div class="price">
                                <span class="price_number">{{e2f($product->price->price ?? 0)}}</span>
                                <span class="unit">تومان</span>
                            </div>
                        @endif
                        @endif
                        <div class="off_price">
                            <span class="off_price_number">{{e2f($product->price->off_price ?? 0)}}</span>
                            <span class="unit">تومان</span>
                        </div>
                    </div>

                </div>

                @if($product->inventory()>0)
                    <div class="add_to_basket">
                        <form action="{{route('cart.add')}}" method="post" id="add_to_cart">
                            <input type="hidden" name="priceId" value="{{$product->price->id}}">
                            @csrf
                        </form>
                        <span onclick="document.getElementById('add_to_cart').submit()">افزودن به سبد خرید</span>
                    </div>
                @else
                    <div style="background-color: #626262; cursor: default" class="add_to_basket">
                        <span>موجود نیست</span>
                    </div>
                @endif

            </div>
        </div>

        @if(! $product->pro_details == null )
        <div class="product_pro">
            <div class="product_pro_header">
                <h2>بررسی تخصصی</h2>
            </div>

            <div class="product_pro_body">
                <p>{!!$product->pro_details!!}</p>
            </div>
        </div>
        @endif

    </div>

    @if(count($similarProducts->where('id' , '!=' , $product->id)) > 0)
    <div class="best_offer">
        <div class="title">
            <span>محصولات مشابه</span>
            <div></div>
        </div>
        <div class="body owl-carousel">


            @foreach($similarProducts->where('id' , '!=' , $product->id ) as $p)
                <div class="item">
                    <img src="{{$p->image}}" alt="">
                    <a class="item_title" target="tab" href="{{route('product.single', $p->slug) }}"><span>{{$p->name}}</span></a>
                    <a class="item_description" target="tab" href="{{route('product.single', $p->slug) }}"><span>{{$p->description}}</a></span>
                    @if($p->price != null && $p->price->price != 0 && $p->price->inventory > 0 )
                        <div class="price">
                            @if($p->price->price != $p->price->off_price)
                                <div class="price_no_off">
                                    <span>{{ e2f($p->price->price ?? 0)}}</span>
                                    <div class="percent">
                                        <span>{{e2f(((int)($p->price->price - (int)$p->price->off_price)/(int)$p->price->price)*100 )}}</span>
                                        <span>%</span>
                                    </div>
                                </div>
                            @endif
                            <div class="price_off">
                                <span class="number">{{ e2f($p->price->off_price ?? 0)}}</span>
                                <span class="unit">تومان</span>
                            </div>
                        </div>
                    @else
                        <div class="price_no_off">
                            <span class="number">ناموجود</span>
                        </div>
                    @endif
                </div>
            @endforeach

        </div>
    </div>
    @endif
    @guest
        <div class="should_login">
            <span>برای ثبت نظر ابتدا باید وارد <a href="{{route('login')}}">سایت</a> شوید !</span>
        </div>
    @endguest
    <div class="users_comments">
        <div class="title">
            <span>نظرات کاربران</span>
            <div></div>
            @auth
                <span id="new_comment_btn" data-id="0" class="new_comment"
                      data-toggle="new_comment_modal">ثبت نظر جدید</span>
            @endauth
        </div>
        <div class="body">

            @include('layouts.comments' , ['comments' => $product->comments()->where('parent' , 0)->where('approved' , 1)->get()])

        </div>
    </div>

    <div id="new_comment_modal" class="modal">

        <div class="modal-content">
            <div class="header">
                <span class="close">&times;</span>
                <span>ارسال دیدگاه :</span>
            </div>
            <div class="new_comment_body">
                <form action="{{route('send.comment')}}" method="post">
                    @csrf
                    <textarea name="comment"></textarea>
                    <input type="hidden" name="commentable_id" value="{{$product->id}}">
                    <input type="hidden" name="commentable_type" value="{{get_class($product)}}">
                    <input type="hidden" name="parent" value="">
                    <button type="submit" class="submit_comment">
                        ارسال دیدگاه
                    </button>
                </form>
            </div>

        </div>

    </div>

@endsection
@section('script')

    <script>


        /*////////////////////////////////SUBMIT_RATE/////////////////////////////////*/

        let app = new Vue({
            el: '#app',
            data: {
                product_id: $('#product_id').val(),
                rate: $('#product_rate').val(),

            },
            methods: {
                farsi(number) {
                    var arabicNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

                    var dotNumber = number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                    var chars = dotNumber.toString().split('');
                    for (var i = 0; i < chars.length; i++) {
                        if (/\d/.test(chars[i])) {
                            chars[i] = arabicNumbers[chars[i]];
                        }
                    }
                    return chars.join('');
                },
                submitRate(rate) {
                    $data = {
                        product_id: this.product_id,
                        rate: rate
                    }

                    axios.post('/product/rate', $data)
                        .then(response => console.log(response.data))
                        .catch(function (error) {
                            console.log(error);
                        })
                }
            },
            mounted() {
                $('#product_attribute').change(function () {

                    let prices = $(this).parents('.product_price_details').find('.prices').data('prices');

                    priceSelected = searchObjectsInArray(prices, 'value', $(this).val());

                    $(this).parents('.product_price_details').find('.price_number').text(numberWithCommas(priceSelected['price']));
                    $(this).parents('.product_price_details').find('.off_price_number').text(numberWithCommas(priceSelected['off_price']));
                    $(this).parents('.product_price_details').find('input[name="priceId"]').val(priceSelected['id']);

        })
                let searchObjectsInArray = (arraylist, checkWithValue, inputValue) => {
                    let priceObject = {}
                    arraylist.map(function (item) {
                        Object.entries(item).forEach(([key, value]) => {
                            if (key == checkWithValue && value == inputValue) {
                                priceObject = item;
                            }
                        });
                    })
                    return priceObject;
                }
                function numberWithCommas(x) {
                    var parts = x.toString().split(".");
                    parts[0]=parts[0].replace(/\B(?=(\d{3})+(?!\d))/g,",");
                    return parts.join(",");
                }

            }

        });

        /*////////////////////////////////Like_And_Dislike/////////////////////////////////*/

        let like_dislikes = document.getElementsByClassName('like_dislikes');
        Array.from(like_dislikes).forEach(function (like_dislike) {
            var comment_id = like_dislike.querySelector('input[name = comment_id]').value

            var comment_like_icon = like_dislike.getElementsByClassName('comment_like_icon')[0]
            var comment_like_number = like_dislike.getElementsByClassName('comment_like_number')[0]

            var comment_dislike_icon = like_dislike.getElementsByClassName('comment_dislike_icon')[0]
            var comment_dislike_number = like_dislike.getElementsByClassName('comment_dislike_number')[0]

            comment_like_icon.addEventListener('click', function (event) {
                event.preventDefault();

                $data = {
                    comment_id: comment_id,
                    like_or_dislike: 'like'
                }

                axios.post('/comment/like', $data)
                    .then(function (response) {
                        comment_like_number.innerHTML = farsi(response.data.like);
                        comment_dislike_number.innerHTML = farsi(response.data.dislike);
                        // console.log(response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            });
            comment_dislike_icon.addEventListener('click', function (event) {
                event.preventDefault();

                $data = {
                    comment_id: comment_id,
                    like_or_dislike: 'dislike'
                }

                axios.post('/comment/like', $data)
                    .then(function (response) {
                        comment_like_number.innerHTML = farsi(response.data.like);
                        comment_dislike_number.innerHTML = farsi(response.data.dislike);
                        // console.log(response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            });
        })

        /*////////////////////////////////send_comment/////////////////////////////////*/
        var new_comment_modal = document.getElementById("new_comment_modal");
        var new_comment = document.getElementsByClassName("new_comment");
        var replay_comment = document.getElementsByClassName("replay_comment");


        Array.from(new_comment).forEach(function (element) {
            element.addEventListener('click', function () {
                new_comment_modal.style.display = "block";
                var parent = element.getAttribute('data-id');
                new_comment_modal.querySelector('input[name = parent]').value = parent;
            });
        });

        Array.from(replay_comment).forEach(function (element) {
            element.addEventListener('click', function () {
                new_comment_modal.style.display = "block";
                var parent = element.getAttribute('data-id');
                new_comment_modal.querySelector('input[name = parent]').value = parent;
            });
        });

        window.onclick = function (event) {
            if (event.target == new_comment_modal) {
                new_comment_modal.style.display = "none";
            }
        }

        var close_modal = document.getElementsByClassName("close");
        var i = 0;
        for (i = 0; i < close_modal.length; i++) {
            close_modal[i].onclick = function () {
                new_comment_modal.style.display = "none";
            }
        }

        /*///////////////////////////////////////////////////////////////////////*/

        $('.other_img_item').click(function () {
            $('.other_img_item').removeClass('border_img_class');
            $(this).addClass('border_img_class');
        })

        master_imgage = $('#master_image');
        $('.other_img_item').click(function () {
            new_src = $(this).attr('src');
            master_imgage.attr("src", new_src);
            master_imgage.attr("data-magnify-src", new_src);
            $(document).ready(function () {
                $('.zoom').magnify();
            });
        });

        $(document).ready(function () {
            $('.zoom').magnify({
                speed: 1,
            });
        });


        $(document).ready(function () {
            $(".owl-carousel").owlCarousel({
                loop: false,
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

        /*////////////////////////////////STAR_RATE/////////////////////////////////*/


    </script>
@endsection

