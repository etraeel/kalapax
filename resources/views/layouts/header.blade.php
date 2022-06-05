<div class="home_header">
    <div class="home_header_top">
        <img src="{{asset(\App\Setting::where('name' , 'site_logo')->first()->value)}}" alt="{{\App\Setting::where('name' , 'site_name')->first()->value}}">
        <div class="home_header_menu">
            @if(count(\Cart::all()) > 0 )
                <a class="mini_basket" href="{{route('cart.index')}}" >
                    <i style="color: #39d67d" class="fal fa-shopping-basket"></i>
                    <span >{{e2f(count(\Cart::all()))}}</span>
                </a>
            @else
                <a class="mini_basket" href="{{route('cart.index')}}" >
                    <i class="fal fa-shopping-basket"></i>
                    <span>{{e2f(count(\Cart::all()))}}</span>
                </a>
            @endif
            <i id="menu-icon" class="fad fa-bars"></i>
            <form id="form_exit" method="POST" action="{{ route('logout') }}">
                @csrf
            </form>

{{--                ////////////Mobile Menu /////////////////--}}
            <div class="menu" hidden>
                <ul>
                    <li>
                        <form id="search_form" action="{{route('search')}}" method="get">
                            <input type="text" name="key" placeholder=" به دنبال چه محصولی هستید ؟ ">
                            <i onclick="document.getElementById('search_form').submit()" class="far fa-search"></i>
                        </form>
                    </li>
                    <li><a href="{{route('home')}}">صفحه اصلی</a></li>
                    <li id="show_products"><a href="#">محصولات</a>
                        <ul id="products_items" >
                            @foreach(\App\Category::where('parent' , 0)->get() as $category)
                                <li>
                                    <a href="{{route('products' , $category->id)}}">{{$category->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @guest()
                    <li><a href="{{route('login')}}">ورود</a></li>
                    <li><a href="{{route('register')}}">عضویت</a></li>
                    @endguest
                    @auth
                        <li><a href="{{route('profile.index')}}">پروفایل</a></li>
                    @endauth
                    <li><a href="{{route('articles')}}">مقالات</a></li>
                    <li><a href="{{route('contactUs')}}">ارتباط با ما</a></li>
                    <li><a href="{{route('aboutUs')}}">درباره ما</a></li>
                    @auth
                        <li>
                            <a href="#" onclick="document.getElementById('form_exit').submit()">خروج</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
        <div class="search" style="display: none">
            <form action="{{route('search')}}" method="get">
                <input type="text" name="key" placeholder=" به دنبال چه محصولی هستید ؟ ">
            </form>
        </div>

        <div class="user_login" style="display: none">

            @if(auth()->check())
                <img src="{{auth()->user()->pic_logo != null ? auth()->user()->pic_logo : asset('/img/avatar.png')}}" alt="">
{{--                <i class="far fa-user"></i>--}}
                <a  href="{{route('profile.index')}}"><span>  پنل کاربری  {{ auth()->user()->name }}</span></a>
                /
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button><span>خروج</span></button>
                </form>

            @else
                <i class="far fa-user"></i>
                <a href="{{route('login')}}"><span>ورود</span></a>
                /
                <a href="{{route('register')}}"><span style="color: #e8505b">عضویت</span></a>
            @endif

        </div>
        @if(count(\Cart::all()) > 0 )
            <a href="{{route('cart.index')}}" style="display: none">
                <i style="color: #39d67d" class="fal fa-shopping-basket"></i>
                <span class="product_exist">{{ e2f(count(\Cart::all()))}}</span>
            </a>
        @else
            <a href="{{route('cart.index')}}" style="display: none">
                <i class="fal fa-shopping-basket"></i>
                <span>{{e2f(count(\Cart::all()))}}</span>
            </a>
        @endif

    </div>

{{--    ////////////Desktop Menu////////////--}}
    <div class="home_header_bottom" style="display: none">
        <div class="menu_products">
            <i class="fad fa-bars menu_products_items_button"></i>
            <span class="menu_products_items_button">لیست محصولات</span>
            <div class="menu_products_items">
                <ul>
                    @foreach(\App\Category::where('parent' , 0)->get() as $category)
{{--                         <li>--}}
{{--                             <a href="{{route('products' , $category->id)}}">{{$category->name}}</a>--}}
{{--                         </li>--}}

                        <li>
                            <a href="{{route('products' , $category->id)}}">
                                {{$category->name}}
                                <i style="padding: 5px" class="fas fa-chevron-left"></i>
                                @foreach($category->child as $child)
                                    <a href="{{route('products' , $child->id)}}">{{$child->name}}</a>
                                @endforeach

                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="nav_menu">
            <div class="nav_menu_item">
                <i class="far fa-home-alt"></i>
                <a href="{{route('home')}}">
                    <span>صفحه اصلی</span>
                </a>
            </div>
            <div class="nav_menu_item">
                <i class="fal fa-newspaper"></i>
                <a href="{{route('articles')}}">
                    <span>مقالات</span>
                </a>
            </div>
            <div class="nav_menu_item">
                <i class="fal fa-question"></i>
                <a href="{{route('questions')}}">
                    <span>سوالات متداول</span>
                </a>
            </div>
            <div class="nav_menu_item">
                <i class="fal fa-user-headset"></i>
                <a href="{{route('contactUs')}}">
                    <span>ارتباط با ما</span>
                </a>
            </div>
            <div class="nav_menu_item">
                <a href="{{route('aboutUs')}}">
                    <span>درباره ما</span>
                </a>
            </div>
        </div>
    </div>

</div>





