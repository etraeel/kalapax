<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model
{
   protected $fillable = ['sender' ,'user_id' , 'title' , 'text' ,'status'];

    public function receiver()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function userSender()
    {
        return $this->belongsTo(User::class , 'sender');
    }

}
