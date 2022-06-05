<nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{route('home')}}" class="nav-link">صفحه اصلی سایت</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{route('admin.admin')}}" class="nav-link">داشبورد</a>
        </li>
    </ul>

    <!-- SEARCH FORM -->
{{--    <form class="form-inline ml-3">--}}
{{--        <div class="input-group input-group-sm">--}}
{{--            <input class="form-control form-control-navbar" type="search" placeholder="جستجو" aria-label="Search">--}}
{{--            <div class="input-group-append">--}}
{{--                <button class="btn btn-navbar" type="submit">--}}
{{--                    <i class="fa fa-search"></i>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </form>--}}
    <!-- Right navbar links -->
    <ul class="navbar-nav mr-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-comments"></i>
                <span class="badge badge-danger navbar-badge">{{\App\UserMessage::where('status', 3)->count()}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">

                @foreach(\App\UserMessage::where('status', 3)->take(3)->get() as $message)
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="{{$message->userSender->pic_logo}}" alt="User Avatar" class="img-size-50 ml-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                {{$message->userSender->name}}
                                <span class="float-left text-sm text-danger"><i class="fa fa-star"></i></span>
                            </h3>
                            <p class="text-sm">{{$message->title}}</p>
                            <p class="text-sm text-muted"><i class="fa fa-clock mr-1"></i>{{jdate($message->updated_at)->ago() }}</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                @endforeach
                <a href="{{route('admin.receivedMessages')}}" class="dropdown-item dropdown-footer">مشاهده همه پیام‌ها</a>
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-bell"></i>
                <span class="badge badge-warning navbar-badge">{{\App\UserMessage::where('status', 3)->count() + \App\Order::where('status', 'paid')->count()}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
                <span class="dropdown-item dropdown-header">{{\App\UserMessage::where('status', 3)->count() + \App\Order::where('status', 'paid')->count()}} نوتیفیکیشن</span>
                <div class="dropdown-divider"></div>
                <a href="{{route('admin.receivedMessages')}}" class="dropdown-item">
                    <i class="fa fa-envelope ml-2"></i> {{\App\UserMessage::where('status', 3)->count()}} پیام جدید
                    @if(\App\UserMessage::where('status', 3)->count() != 0)
                    <span class="float-left text-muted text-sm">{{jdate(\App\UserMessage::where('status', 3)->latest('created_at')->first()->created_at)->ago()  }}</span>
                     @endif
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.orders.index' , ['type' => 'paid']) }}" class="dropdown-item">
                    <i class="fab fa-product-hunt"></i> {{\App\Order::where('status', 'paid')->count()}} سفارش جدید
                    @if(\App\Order::where('status', 'paid')->count() != 0)
                    <span class="float-left text-muted text-sm">{{jdate(\App\Order::where('status', 'paid')->latest('created_at')->first()->created_at )->ago() }}</span>
                    @endif
                </a>
                <div class="dropdown-divider"></div>
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <i class="fa fa-file ml-2"></i> 3 گزارش جدید--}}
{{--                    <span class="float-left text-muted text-sm">2 روز</span>--}}
{{--                </a>--}}

            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
                    class="fa fa-th-large"></i></a>
        </li>
    </ul>
</nav>
