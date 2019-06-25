<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>注册页面</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
</head>
<body>
    <form action="/reg/doRegister" method="post">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif
    @if (session('msg'))
        <div class="alert alert-success">
        {{ session('msg') }}
        </div>
    @endif
    @csrf
        <table>
            <tr>
                <td>账号</td>
                <td><input type="text" name="u_email"><span></span></td>
            </tr>
            <tr>
                <td>验证码</td>
                <td><input type="text" name="code" style="width: 50%"><a href="javascript:;" id="send">获取</a><span></span></td>
            </tr>
            <tr>
                <td>密码</td>
                <td><input type="password" name="u_pwd"><span></span></td>
            </tr>
            <tr>
                <td>确认密码</td>
                <td><input type="password" name="u_repwd"><span></span></td>
            </tr>
            <tr>
                <td><button>注册</button></td>
                <td><input type="reset" value="重置"></td>
            </tr>
        </table>
    </form>
</body>
</html>
<script>
$(function(){

    //获取验证码的点击事件
    $('#send').click(function(){
        //获取邮箱
        var email = $('input[name=u_email]').val();
        if (email == '') {
            $('input[name=u_email]').next().html('<font color=red>邮箱不能为空</font>');
        }else{
            //检测邮箱的唯一性
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('email') }}",
                type: "post",
                data: "u_email="+email,
                async: false,
                success: function(data){
                    if (data == 1) {
                        alert('验证码发送成功');
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

});
    
</script>