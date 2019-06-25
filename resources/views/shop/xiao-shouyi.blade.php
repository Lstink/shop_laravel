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
  <body style="background:#efefef;">
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>分销中心</h1>
      </div>
     </header>
     <div class="Distr-shouyi1" id="shouyi1s">
      <h4>预计收益（元）<a href="xiao-info.html" class="mingx">查看明细 <span class="glyphicon glyphicon-menu-right"></span></a></h4>
      <h5><em>0.00</em> </h5>
     </div><!--Distr-shouyi1-->
     <div class="fenxiaoyongjin-list">
	  <dl>
	   <dt>一级佣金</dt>
	   <dd class="red">¥0.00</dd>
	  </dl>
	  <dl>
	   <dt>二级佣金</dt>
	   <dd class="f60">¥0.00</dd>
	  </dl>
	  <dl style="border:0;">
	   <dt>三级佣金</dt>
	   <dd class="blue">¥0.00</dd>
	  </dl>
	  <div class="clearfix"></div>
	 </div><!---->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('shop/js/jquery.min.js')}}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('shop/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('shop/js/style.js')}}"></script>
  </body>
</html>