@extends('layouts.master')

@section('content')

    <div class="articles">
        <div class="head">
            <h1>مقالات</h1>
        </div>

        <div class="body">
            <ul class="article_list">
                @foreach($articles as $article)
                    <li class="box">

                        <div class="image_box">
                            <img src="{{$article->image}}" alt="">
                        </div>
                        <div class="body_box">
                            <a href="{{route('article.show' ,  $article)}}"><h2 class="box_title translate">{{$article->title}}</h2></a>
                            <p class="box_text">{!! $article->description !!}</p>
                            <div class="box_keys">
                                <span >{{$article->key_words}}</span>
                            </div>
                            <div class="box_footer">
                                <span class="like translate">{{count($article->likes()->get())}}</span>
                                <i class="likeheart fal fa-heart"></i>

                                <span class="counter translate" style="margin-right: 15px">{{$article->counter}}</span>
                                <i class="fad fa-eye"></i>

                            </div>
                        </div>

                    </li>
                @endforeach
            </ul>
        </div>

        {{ $articles->links() }}
    </div>
@endsection
