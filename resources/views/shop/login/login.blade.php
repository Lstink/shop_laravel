@extends('layouts.shop')

@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员登录</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     <form action="/doLogin" method="post" class="reg-login">
     @csrf
      <h3>还没有三级分销账号？点此<a class="orange" href="reg">注册</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" name="account" placeholder="输入邮箱或手机号登录" /></div>
       <div class="lrList"><input type="password" name="u_pwd" placeholder="输入密码" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
          @if(session('msg'))
               <font color="red">{{session('msg')}}</font>
          @endif

          @if ($errors->any())
               @foreach ($errors->all() as $error)
                    <font color="red">{{ $error }}</font>
               @endforeach
          @else
               <font color="red" id="msg"></font>
          @endif
       <input type="submit" value="立即登录" />
      </div>
     </form><!--reg-login/-->
     <div class="height1"></div>

<script>
$(function(){
     
});

</script>
@endsection