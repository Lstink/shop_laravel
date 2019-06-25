<!doctype html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>绑定商城账号</title>
        <meta name="renderer" content="webkit|ie-comp|ie-stand">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('./css/font.css') }}">
        <link rel="stylesheet" href="{{ asset('./css/xadmin.css') }}">
        <link rel="stylesheet" href="{{ asset('./css/theme80.min.css') }}">
        <link rel="stylesheet" href="{{ asset('./css/app.css') }}">
        <!-- <link rel="stylesheet" href="./css/theme5.css"> -->
        <script src="{{ asset('./js/jquery-3.3.1.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('./lib/layui/layui.js') }}" charset="utf-8"></script>
        <script type="text/javascript" src="{{ asset('./js/xadmin.js') }}"></script>
        <script type="text/javascript" src="{{ asset('./js/app.js') }}"></script>
        <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
        <!--[if lt IE 9]>
          <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
          <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script>
            // 是否开启刷新记忆tab功能
            // var is_remember = false;
        </script>
    </head>
    <body class="index">

<form class="layui-form layui-form-pane" method="post" enctype="multipart/form-data" style="margin-top: 10px">
@csrf    
    @if (session('msg'))
    <div class="alert alert-danger">
    {{ session('msg') }}
    </div>
    @endif
<div class="main_content">
    <div class="layui-form-item">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" name="account" autocomplete="off" placeholder="请输入手机号或者邮箱" lay-verify="required" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" name="code" autocomplete="off" placeholder="请输入验证码" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <span class="layui-btn btn" >发送验证码</span>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即绑定</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                没有账号？点击<a href="http://www.yeyunyang.xyz/reg" style="color:blue!important;">注册</a>
            </div>
        </div>
    </div>
    
</div>

</form>

<script>
    $(function(){
        layui.use(['layer','form'], function(){
            var $ = layui.jquery,
            form = layui.form,
            layer = layui.layer;
            
            
            //提交按钮的监听事件
            form.on('submit(demo1)', function(data){
                var account = $('input[name=account]').val();
                var code = $('input[name=code]').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // var val = $('input[type=text]').val();
                $.post(
                    "{{ route('doBinding') }}",
                    {account:account,code:code},
                    function(res){
                        if (res.code == 3) {
                            location.href = res.url;
                        }else{
                            layer.msg(res.msg,{icon:res.code});
                        }
                    },'json'
                );
                return false;
            });

            //提交按钮的监听事件
            $('.btn').click(function(){
                var account = $('input[name=account]').val();
                if (account == '') {
                    layer.msg('请输入邮箱或者手机号',{icon:2});
                }else{
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post(
                    "{{ route('sendCode') }}",
                    {account:account},
                    function(res){
                        if (res.code == 1) {
                            layer.msg(res.msg,{icon:res.code,time:1000},function(){
                                xadmin.close();
                            });
                        }else{
                            layer.msg(res.msg,{icon:res.code});
                        }
                    },'json'
                );
                }
                console.log(account);
                
            });
           
        });
    });
</script>
@include('public.footer')