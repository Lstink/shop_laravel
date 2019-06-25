<?php

namespace App\Http\Controllers\register;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\shop\User;
use Mail;

class RegisterController extends Controller
{
    public function reg()
    {
        return view('reg.reg');
    }
    public function email(Request $request)
    {
        $u_email = $request -> u_email;
        //检查账号是否存在
        $res = User::where('u_email',$u_email) -> count();
        if ($res) {
            echo 3;
        }else{
            //生成验证码
            $code = rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9);
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
                            'time'=>time()
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
    //注册
    public function doReg(Request $request)
    {
        // dd(session('code'));
        $validatedData = $request->validate([
            'u_email' => 'required|e-mail|unique:tp_user',
            'u_pwd' => 'required',
            'code' => 'required|numeric',
            'u_repwd'=> 'required'
        ],[
            'u_email.required' => '请填写邮箱',
            'u_email.e-mail' => '请填写正确邮箱格式',
            'u_email.unique' => '该邮箱已经被注册',
            'u_pwd.required' => '请填写密码',
            'code.required' => '请填写验证码',
            'code.numeric' => '验证码必须是数字',
            'u_repwd.required' => '确认密码必填',
        ]);
        $data = $request -> except(['_token','u_repwd']);
        $code = session('code');
        // dd($data);
        if ($code['time']-time()>120) {
            return redirect('/reg/register') -> with(['msg'=>'验证码超过两分钟']);
        }
        if ($request -> code != $code['code']) {
            return redirect('/reg/register') -> with(['msg'=>'验证码错误']);
        }
        if ($request -> u_email != $code['u_email']) {
            return redirect('/reg/register') -> with(['msg'=>'注册账号和接收验证码账号不一致']);
        }
        if ($request -> u_pwd != $request -> u_repwd) {
            return redirect('/reg/register') -> with(['msg'=>'两次密码不一致']);
        }
        //给密码加密
        $data['u_pwd'] = md5($data['u_pwd']);
        $data['create_time'] = time();
        $res = User::insertGetId($data);
        if ($res) {
            $request -> session() -> forget('code');
            return redirect('/reg/logins') -> with(['msg'=>'注册成功']);
        }else{
            return back() -> with(['msg'=>'注册失败']);
        }
    }
    public function logins()
    {
        return view('reg/logins');
    }
    public function doLogin(Request $request)
    {
        $data = $request -> except('_token');
        // dump($data);
        $validatedData = $request->validate([
            'u_email' => 'required',
            'u_pwd' => 'required',
        ],[
            'u_email.required' => '请填写邮箱',
            'u_pwd.required' => '请填写密码',
        ]);
        $data['u_pwd'] = md5($data['u_pwd']);
        $aa = User::where('u_email',$data['u_email']) -> count();
        if (!$aa) {
            return redirect('/reg/logins') -> with(['msg'=>'账号或密码错误']);
        }
        //邮箱
        $res = User::where('u_email',$data['u_email']) -> first();
        // dd($res);
        if ($res -> u_pwd == $data['u_pwd']) {
            //账号密码正确
            $time = 60-ceil((time()-$res -> error_last_time)/60);
            if ($time>0) {
                return redirect('/reg/logins') -> with(['msg'=>'您的账户已锁定，请于'.$time.'分钟后登录']);
            }
            $userInfo = [
                'u_id' => $res -> u_id,
                'u_email' => $data['u_email'],
            ];
            session(['u_id'=>$res -> u_id]);
            //清空错误次数和最后一次错误时间
            $where = [
                'error_count' => 0,
                'error_last_time' => null
            ];
            User::where('u_id',$res -> u_id) -> update($where);
            //存缓存
            cache(['u_id_'.$res -> u_id=>$res],60*24);
            return redirect('/reg/index');
        }else{
            //密码错误
            if ($res -> error_count >= 4) {
                //剩余解锁时间  分钟数 
                $time = 60-ceil((time()-$res -> error_last_time)/60);
                //密码错误次数大于5次 且上次错误时间大于 一小时
                if ($time<=0) {
                    User::where('u_id',$res -> u_id) -> update(['error_count'=>1]);
                    return redirect('/reg/logins') -> with(['msg'=>'密码错误，您还有4次机会']);
                }else{
                    //密码错误次数大于5次 且上次错误时间小于 一小时
                    return redirect('/reg/logins') -> with(['msg'=>'您的账户已锁定，请于'.$time.'分钟后登录']);
                }
            }else{
                //密码错误次数小于5次
                $count = $res -> error_count ??'0';
                $where = [
                    'error_count' => $count + 1,
                    'error_last_time' => time()
                ];
                User::where('u_id',$res -> u_id) -> update($where);
                //剩余登录错误次数
                $counts = 4-$count;
                if ($count == 4) {
                    return redirect('/reg/logins') -> with(['msg'=>'您的账户已锁定，请于1小时后登录']);
                }else{
                    return redirect('/reg/logins') -> with(['msg'=>'密码错误,您还有'.$counts.'次机会']);
                }
            }
        }
    }
    public function index()
    {
        return view('reg.index');
    }
}
