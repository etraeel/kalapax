<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Message\Message;
use App\Http\Controllers\Controller;
use App\User;
use App\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class receivedMessagesController extends Controller
{
    public function index()
    {
        $messages  = UserMessage::query();
        if($keyword = request('search')){
            $messages->where('status', 3)->where('title' , 'LIKE',"%{$keyword}%")->orWhere('text' , 'LIKE',"%{$keyword}%");
        }
        $messages = $messages->where('status', 3)->latest()->paginate(15);
        return view('admin.message.receivedMessagesIndex', compact('messages'));
    }

    public function replay(Request $request)
    {
        $user_id  = Auth::user()->id;
        $userMessage =  UserMessage::find($request->id);
        $userMessage->update([
            'status' => 1
        ]);

        Message::send($user_id , $userMessage->sender , 'پاسخ' ,$request->replay);

        alert()->success('پیام با موفقیت ارسال شد ');
        return redirect(route('admin.receivedMessages'));

    }

    public function delete($id)
    {
        $message =  UserMessage::find($id);
        $message->delete();
        alert()->success('پیام با موفقیت حذف شد ');
        return redirect(route('admin.receivedMessages'));
    }
}
