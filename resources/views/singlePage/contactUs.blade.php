@extends('layouts.master-color')
@section('header')

@endsection
@section('content')

   <div class="questions">
       <div class="head">
           <h1>ارتباط با ما</h1>
       </div>


       <div class="body">
           <span>{!!  $contactUs_text->value !!}</span>
       </div>

   </div>

@endsection
@section('script')

    <script>
        $('.body').find('p span').each(function (index , item) {
            item.innerHTML = farsiText(item.innerHTML);
        });

    </script>
@endsection

