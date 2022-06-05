<?php


namespace App\Helpers\sms;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use phpDocumentor\Reflection\DocBlock\StandardTagFactory;
use phpDocumentor\Reflection\Types\Static_;



class sms extends Facade
{

    protected static function getFacadeAccessor()
    {
       return 'sms';
    }
}
