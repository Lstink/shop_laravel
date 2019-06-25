<?php

namespace App\Http\Controllers\weChat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * @content 登陆页面
     */
    public function login()
    {
        return view('admin.login');
    }
}
