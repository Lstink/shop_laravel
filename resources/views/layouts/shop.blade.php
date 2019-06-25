<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>三级分销</title>
    <link rel="shortcut icon" href="{{asset('shop/images/favicon.ico')}}" />
    
    <!-- Bootstrap -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/response.css')}}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('shop/js/jquery.min.js')}}"></script>
    <!-- <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script> -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('shop/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('shop/js/style.js')}}"></script>
    <!--焦点轮换-->
    <script src="{{asset('shop/js/jquery.excoloSlider.js')}}"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
    <script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    debug: true,
    appId: '{{ $sign['appId'] }}',
    timestamp: '{{ $sign['timestamp'] }}',
    nonceStr: '{{ $sign['nonceStr'] }}',
    signature: '{{ $sign['signature'] }}',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
      'onMenuShareTimeline','updateAppMessageShareData',
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
    wx.onMenuShareTimeline({
          title: 'This is a demo', // 分享标题
          link: 'http://www.yeyunyang.xyz', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
          imgUrl: 'http://mmbiz.qpic.cn/mmbiz_jpg/cwzGpibB3ibPMlw3y4nGgf7Fvy8yN1OgboIoKIx6ynlQ2MBtAYeIibSqeoQ7YzXaqsABXA1dQBCrECk5vCyn6C84g/0?wx_fmt=jpeg', // 分享图标
          success: function () {
          // 用户点击了分享后执行的回调函数
      },
    })
    
    wx.updateAppMessageShareData({ 
        title: 'This is a demo', // 分享标题
        desc: '这是什么', // 分享描述
        link: 'http://www.yeyunyang.xyz', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: 'http://mmbiz.qpic.cn/mmbiz_jpg/cwzGpibB3ibPMlw3y4nGgf7Fvy8yN1OgboIoKIx6ynlQ2MBtAYeIibSqeoQ7YzXaqsABXA1dQBCrECk5vCyn6C84g/0?wx_fmt=jpeg', // 分享图标
        success: function () {
          // 设置成功
        }
    })

  });
</script>
  </head>
  <body>
    <div class="maincont">

     @yield('content')

     <div class="footNav">
      <dl>
       <a href="/index">
        <dt><span class="glyphicon glyphicon-home"></span></dt>
        <dd>微店</dd>
       </a>
      </dl>
      <dl>
       <a href="/prolist">
        <dt><span class="glyphicon glyphicon-th"></span></dt>
        <dd>所有商品</dd>
       </a>
      </dl>
      <dl>
       <a href="/car">
        <dt><span class="glyphicon glyphicon-shopping-cart"></span></dt>
        <dd>购物车 </dd>
       </a>
      </dl>
      <dl>
       <a href="/user">
        <dt><span class="glyphicon glyphicon-user"></span></dt>
        <dd>我的</dd>
       </a>
      </dl>
      <div class="clearfix"></div>
     </div><!--footNav/-->
    </div><!--maincont-->
    
    <script>
		$(function () {
         $("#sliderA").excoloSlider();
         
		});
	</script>
  </body>
</html>