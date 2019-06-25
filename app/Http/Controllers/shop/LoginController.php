<?php

namespace App\Http\Controllers\shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\shop\User;
use App\Http\Requests\checkEmail;
use Mail;
// use Symfony\Component\HttpFoundation\Cookie;

class LoginController extends Controller
{
    public function login()
    {
        return view('shop.login.login');
    }
    public function reg()
    {
        return view('shop.login.reg');
    }
    public function checkEmail(\App\Http\Requests\CheckEmail $request)
    {
        $u_email = $request -> u_email;
        //检查账号是否存在
        $res = User::where('u_email',$u_email) ->orWhere('u_phone',$u_email) -> count();
        if ($res) {
            echo 3;
        }else{
            //判断是手机号还是邮箱
            //生成验证码
            $code = rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9);
            if (preg_match("/^\d{11}$/", $u_email)) {
                //手机号
                $host = "http://dingxin.market.alicloudapi.com";
                $path = "/dx/sendSms";
                $method = "POST";
                $appcode = "f6496824839f44b2bba9bafe8b154d95";
                $headers = array();
                array_push($headers, "Authorization:APPCODE " . $appcode);
                $querys = "mobile=".$u_email."&param=code%3A".$code."&tpl_id=TP1711063";
                $bodys = "";
                $url = $host . $path . "?" . $querys;

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_FAILONERROR, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                if (1 == strpos("$".$host, "https://"))
                {
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                }
                $resPhone = curl_exec($curl);
                // dump($resPhone);
                $resPhone = json_decode($resPhone);
                // dd($resPhone);
                if ($resPhone -> return_code == '00000') {
                    //发送成功 存session
                    $request -> session() -> forget('code');
                    $code = [
                        'code'=>$code,
                        'u_email'=>$u_email,
                    ];
                    session(['code'=>$code]);
                    echo 1;
                }else{
                    echo 0;
                }
            }else{
                //不是手机号 发送邮件
                Mail::send(
                    'shop.email.view',
                    ['code' => $code],
                    function($message)use($u_email,$code){
                        $res = $message -> subject('验证码') -> to($u_email);
                        if ($res) {
                            //发送成功 存session
                            request() -> session() -> forget('code');
                            $code = [
                                'code'=>$code,
                                'u_email'=>$u_email,
                            ];
                            session(['code'=>$code]);
                            echo 1;
                        }else{
                            echo 0;
                        }
                    }
                );
            }
        }
        
    }

    //注册
    public function doReg(Request $request)
    {
        // dd(session('code'));
        $validatedData = $request->validate([
            'u_email' => 'required',
            'u_pwd' => 'required',
            'code' => 'required',
        ],[
            'u_email.required' => '请填写邮箱或手机号',
            'u_pwd.required' => '请填写密码',
            'code.required' => '请填写验证码',
        ]);
        $data = $request -> except(['_token','code','u_repwd']);
        $code = session('code');
        // dd($data);
        if ($request -> code != $code['code']) {
            return redirect('/reg') -> with(['msg'=>'验证码错误']);
        }
        if ($request -> u_email != $code['u_email']) {
            return redirect('/reg') -> with(['msg'=>'注册账号和接收验证码账号不一致']);
        }
        if ($request -> u_repwd != $request -> u_pwd) {
            return redirect('/reg') -> with(['msg'=>'两次密码不一致']);
        }
        //判断是手机号还是邮箱
        $userInfo = [
            'u_email' => $data['u_email']
        ];
        if (preg_match("/^\d{11}$/", $data['u_email'])) {
            //手机号
            $data['u_phone'] = $data['u_email'];
            unset($data['u_email']);
            
        }
        //给密码加密
        $data['u_pwd'] = md5($data['u_pwd']);
        $data['create_time'] = time();
        $res = User::insertGetId($data);
        $userInfo['u_id'] = $res;
        if ($res) {
            session(['userInfo'=>$userInfo]);
            $request -> session() -> forget('code');
            return redirect('/index') -> with(['msg'=>'注册成功']);
        }else{
            return back() -> with(['msg'=>'注册失败']);
        }
    }
    /**
     * 登录方法
     */
    public function doLogin(Request $request)
    {
        $data = $request -> except('_token');
        // dump($data);
        $validatedData = $request->validate([
            'account' => 'required',
            'u_pwd' => 'required',
        ],[
            'account.required' => '请填写邮箱或者手机号',
            'u_pwd.required' => '请填写密码',
        ]);
        $pwd = md5($data['u_pwd']);
        if (preg_match("/^\d{11}$/", $data['account'])) {
            //手机号
            $where = [
                ['u_phone','=',$data['account']],
                ['u_pwd','=',$pwd],
            ];
        }else{
            //邮箱
            $where = [
                ['u_email','=',$data['account']],
                ['u_pwd','=',$pwd],
            ];
        }
        $res = User::where($where) -> first();
        if ($res) {
            //账号密码正确
            $userInfo = [
                'u_id' => $res -> u_id,
                'u_email' => $data['account'],
            ];
            session(['userInfo'=>$userInfo]);
            return redirect('/index');
        }else{
            //不正确
            return redirect('/login') -> with(['msg'=>'账号密码不正确']);
        }
    }

    public function unLogin(){
        request() -> session() -> forget('userInfo');
        return redirect('/index');
    }

    /**
     * 测试方法
     */
    // public function test()
    // {
    //     echo 'session code';
    //     dump(session('code'));
    //     echo '<hr />';
    //     echo 'session userInfo';
    //     dump(session('userInfo'));
    //     echo '<hr />';
    //     echo 'cookie buyCar';
        
    //     dump(request() -> cookie('buyCar'));
    //     echo '<hr />';
    // }
    // public function coo()
    // {
    //     response('ddd') -> cookie('buyCar','dd',60);
    // }
}
