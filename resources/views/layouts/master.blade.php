<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontiran.css')}}">
    <link rel="stylesheet" href="{{asset('css/vazir.css')}}">
    <link rel="stylesheet" href="{{asset('css/yekan.css')}}">
    <link rel="stylesheet" href="{{asset('css/normalize.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/magnify.css')}}">
    <link rel="stylesheet" href="{{asset('css/star-rating.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/vue-flash-message.css')}}">
    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/flash.min.css')}}">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">


    @yield('header')
    <title>shoping</title>

</head>
<body>

@include("layouts.header")


<div class="home_body">
    @yield('content')

</div>

@include("layouts.footer")


<script src="{{asset('js/app.js')}}"></script>


<script>


    menu = $('.menu');
    menu.hide();
    $('#menu-icon').click(function () {
        menu.toggle(500);
    });

    products_list = $('#products_items');
    products_list.hide();
    $('#show_products').click(function () {
        products_list.toggle(500);
    })

    menu_products_items = $('.menu_products_items');
    menu_products_items.hide();
    $('.menu_products').hover(function () {
            menu_products_items.toggle(1);
    });


    function farsi(number) {
        var arabicNumbers = ['??', '??', '??', '??', '??', '??', '??', '??', '??', '??'];

        var dotNumber = number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        var chars = dotNumber.toString().split('');
        for (var i = 0; i < chars.length; i++) {
            if (/\d/.test(chars[i])) {
                chars[i] = arabicNumbers[chars[i]];
            }
        }
        return chars.join('');
    }

    function farsiText(number) {
        var arabicNumbers = ['??', '??', '??', '??', '??', '??', '??', '??', '??', '??'];

        var chars = number.toString().split('');
        for (var i = 0; i < chars.length; i++) {
            if (/\d/.test(chars[i])) {
                chars[i] = arabicNumbers[chars[i]];
            }
        }
        return chars.join('');
    }

</script>
@include('sweet::alert')
@yield('script')
</body>
</html>
