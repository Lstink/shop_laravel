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
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.{{asset('shop/js/1.4.2/respond.min.js')}}"></script>
    <![endif]-->
  </head>
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>分销中心</h1>
      </div>
     </header>
     <dl class="Distr-touxiang">
      <dt><img src="http://2.webqin.cn/fenxiao/Public/Home/{{asset('shop/images/touxiang.jpg')}}" /></dt>
      <dd>
       <h3>我叫什么其实我也忘了</h3>
       <p>2015年11月7日16:25:48</p>
      </dd>
      <div class="clearfix"></div>
     </dl><!--Distr-touxiang/-->
     <div class="Distr-shouyi1">
      <h3>累计佣金：<em>0.00</em>元 <span class="glyphicon glyphicon-menu-right"></span></h3>
      <h4><a href="xiao-yongjin.html">可提佣金（元）</a></h4>
      <h5><em>0.00</em> <a href="#" class="tix">提现</a></h5>
     </div><!--Distr-shouyi1-->
     <div class="DistrBox">
      <div class="Distr-list">
       <dl>
        <a href="xiao-shouyi.html">
         <dt><img src="{{asset('shop/images/d1.png" /></dt>
         <dd class="distr-title">收益统计</dd>
         <dd class="distr-text"><em>0.00</em>元</dd>
        </a>
       </dl>
      </div><!--Distr-list/--> 
      <div class="Distr-list">
       <dl>
        <a href="xiao-team.html">
         <dt><img src="{{asset('shop/images/d2.png" /></dt>
         <dd class="distr-title">我们的团队</dd>
         <dd class="distr-text"><em>0</em>个伙伴</dd>
        </a>
       </dl>
      </div><!--Distr-list/--> 
      <div class="Distr-list" style="border-right:0">
       <dl>
        <a href="xiao-order.html">
         <dt><img src="{{asset('shop/images/d3.png" /></dt>
         <dd class="distr-title">分销订单</dd>
         <dd class="distr-text"><em>0</em>个订单</dd>
        </a>
       </dl>
      </div><!--Distr-list/--> 
      <div class="Distr-list">
       <dl>
        <a href="">
         <dt><img src="{{asset('shop/images/d4.png" /></dt>
         <dd class="distr-title">二维码</dd>
         <dd class="distr-text">推广二维码</dd>
        </a>
       </dl>
      </div><!--Distr-list/--> 
      <div class="Distr-list">
       <dl>
        <a href="xiao-mess.html">
         <dt><img src="{{asset('shop/images/d5.png" /></dt>
         <dd class="distr-title">我的通知</dd>
         <dd class="distr-text"><em>0</em>条通知</dd>
        </a>
       </dl>
      </div><!--Distr-list/--> 
      <div class="clearfix"></div>
     </div><!--DistrBox/-->
     <div style="height:10px; background:#ddd;"></div>
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('shop/js/jquery.min.js')}}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('shop/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('shop/js/style.js')}}"></script>
  </body>
</html>