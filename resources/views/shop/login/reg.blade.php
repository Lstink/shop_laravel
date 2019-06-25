@extends('layouts.shop')

@section('content')

     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员注册</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     <form action="/doReg" method="post" class="reg-login">
       @csrf
      <h3>已经有账号了？点此<a class="orange" href="/login">登陆</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" name="u_email" placeholder="输入邮箱或手机号注册" /></div>
       <div class="lrList2"><input type="text" name="code" placeholder="输入验证码" /> <button id="num">获取验证码</button></div>
       <div class="lrList"><input type="password" name="u_pwd" placeholder="设置密码（6-18位数字或字母）" /></div>
       <div class="lrList"><input type="password" name="u_repwd" placeholder="再次输入密码" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
        
        @if(session('msg'))
          <font color="red" id='msg'>{{session('msg')}}</font>
          <script>
            setTimeout(() => {
              $('#msg').empty();
            }, 3000);
          </script>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
            <font color="red" id="msg">{{ $error }}</font>
            @endforeach
            <script>
            setTimeout(() => {
              $('#msg').empty();
            }, 3000);
          </script>
        @else
        <font color="red" id="msg"></font>
        <script>
            setTimeout(() => {
              $('#msg').empty();
            }, 3000);
          </script>
        @endif
       <input type="submit" value="立即注册" />
      </div>
     </form><!--reg-login/-->
     <div class="height1"></div>

<script>
  $(function(){
    
    //获取验证码的点击事件
    $('#num').click(function(){
      //获取邮箱
      var email = $('input[name=u_email]').val();
				if (email == '') {
					$('#msg').html('邮箱或手机号不能为空');
				}else{
          //检测邮箱或手机号的唯一性
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $.ajax({
            url: "/ajax/checkEmail",
            type: "post",
            data: "u_email="+email,
            dataType: 'json',
            async: false,
            success: function(data){
              if (data == 1) {
                $('#msg').html('验证码发送成功');
                times();
              }else if(data == 3){
                return $('#msg').html('该账号已经被注册');
              }else{
                return $('#msg').html('验证码发送失败，请重试');
              }
            }
          });
				}
      return false;
    });

    //倒计时 定时器
    function times()
    {
      $('#num').css('pointerEvents','none');
      $('#num').text('60'+'s');
      timeLess = setInterval(function(){
        var tms = parseInt($('#num').text());
        // console.log(tms);
        tms-=1;
        $('#num').text(tms+'s');
        //删除定时器
        if (tms <= 0) {
          clearInterval(timeLess);
          $('#num').text('获取验证码');
          $('#num').css('pointerEvents','auto');
        }
      }, 1000);
    }

  })
</script>
@endsection

