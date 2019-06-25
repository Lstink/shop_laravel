<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    <title>三级分销</title>
    <link rel="shortcut icon" href="{{asset('shop/images/favicon.ico')}" />
    
    <!-- Bootstrap -->
    <link href="{{asset('shop/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/response.css')}}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js')}} for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js')}} doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js')}}"></script>
      <script src="http://cdn.bootcss.com/respond.{{asset('shop/js/1.4.2/respond.min.js')}}"></script>
    <![endif]-->
  </head>
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>分销申请</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     <div class="fenxiao">
      <div class="fen-text1">欢迎加入<span class="f60">三级分销</span>，请填写申请信息！</div>
      <div class="fen-text1">邀请人：<span class="f60">王大力</span>(请核对)</div>
      <form action="#" method="get" class="fenxiaoinput">
       <input type="text" placeholder="请填写真实姓名，用于佣金结算" />
       <input type="text" placeholder="请填写手机号码方便联系" />
       <input type="submit" value="申请成为分销商" class="fensub" />
      </form>
      <div class="fen-text1">分销商特权</div>
      <div class="fen-list">
       <dl>
        <dt><img src="{{asset('shop/images/fen1.jpg')}}" /></dt>
        <dd>
         <h3>独立微店</h3>
         <p>拥有自己的微店及推广二维码；</p>
        </dd>
        <div class="clearfix"></div>
       </dl>
       <dl>
        <dt><img src="{{asset('shop/images/fen2.jpg')}}" /></dt>
        <dd>
         <h3>销售拿佣金</h3>
         <p>微店卖出商品，您可以获得佣金；</p>
        </dd>
        <div class="clearfix"></div>
       </dl>
      </div><!--fen-list/-->
     </div><!--fenxiao/-->
     
     <div class="height1"></div>
     <div class="footNav">
      <dl>
       <a href="index.html">
        <dt><span class="glyphicon glyphicon-home"></span></dt>
        <dd>微店</dd>
       </a>
      </dl>
      <dl>
       <a href="prolist.html">
        <dt><span class="glyphicon glyphicon-th"></span></dt>
        <dd>所有商品</dd>
       </a>
      </dl>
      <dl>
       <a href="car.html">
        <dt><span class="glyphicon glyphicon-shopping-cart"></span></dt>
        <dd>购物车 </dd>
       </a>
      </dl>
      <dl>
       <a href="user.html">
        <dt><span class="glyphicon glyphicon-user"></span></dt>
        <dd>我的</dd>
       </a>
      </dl>
      <div class="clearfix"></div>
     </div><!--footNav/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('shop/js/jquery.min.js')}}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('shop/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('shop/js/style.js')}}"></script>
    <!--焦点轮换-->
    <script src="{{asset('shop/js/jquery.excoloSlider.js')}}"></script>
    <script>
		$(function () {
		 $("#sliderA").excoloSlider();
		});
	</script>
  </body>
</html>