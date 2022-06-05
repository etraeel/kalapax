<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:comments');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $comments  = Comment::query();
        if($keyword = request('search')){
            $comments->where('comment' , 'LIKE',"%{$keyword}%");
        }
        $comments = $comments->where('approved' , 0)->latest()->paginate(15);

        return view('admin.comments.all',compact('comments'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Comment $comment)
    {
            $comment->forceFill([
                'approved' => 1,
            ])->save();
        alert()->success('دیدگاه با موفقیت تایید شد ');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        alert()->success('دیدگاه با موفقیت حذف شد ');
        return back();
    }
}
