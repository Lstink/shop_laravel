<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class DemoController extends Controller
{
    function redis()
    {
        // cache(['a'=>'b'],2);
        // $a = cache('a');
        // dd($a);
        // dd(Redis::get('a'));
        Redis::set('name','颠三倒四');
        // Redis::del('name');
        dd(Redis::get('name'));
    }
}
