@foreach($comments as $comment)
    <div class="item">
        <div class="item_header">
            <div class="right">
                <img src="{{$comment->user->pic_logo != null ? asset($comment->user->pic_logo)  : asset('img/avatar.png')}}" alt="">
                <div class="details">
                    <div class="detail_name">
                        <span>{{$comment->user->name}}</span>
                    </div>
                    <div class="detail_date">
                        <span class="translate">{{jdate($comment->created_at)->ago()}}</span>
                    </div>
                </div>
            </div>

            <div class="left">
                @auth
                    <span id="new_comment_btn" data-id="{{$comment->id}}" class="replay_comment " data-toggle="new_comment_modal">پاسخ به نظر</span>
                @endauth
                <div class="like_dislikes">
                    <input name="comment_id" type="hidden" value="{{$comment->id}}">
                    <div class="comment_like">
                        <i class="fad fa-thumbs-up comment_like_icon"></i>
                        <span class="comment_like_number translate">{{$comment->like}}</span>

                    </div>
                    <div class="comment_dislike">
                        <i class="fad fa-thumbs-down comment_dislike_icon"></i>
                        <span class="comment_dislike_number translate">{{$comment->dislike}}</span>
                    </div>
                </div>

            </div>
        </div>
        <div class="item_body">
            <text>{{$comment->comment}}</text>
        </div>

        @include('layouts.comments' , ['comments' => $comment->child->where('approved' , 1)])

    </div>
@endforeach
