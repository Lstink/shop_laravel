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
      <script src="http://cdn.bootcss.com/respond.1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="background:#EFEFEF;">
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>分销中心</h1>
      </div>
     </header>
     <ul class="xiao-oreq">
      <li class="roecur">所有订单</li>
      <li>已付款</li>
      <li>代付款</li>
      <li>已完成</li>
     </ul><!--xiao-oreq/-->
     <div class="xiao-oreq-list" style="display:block;">
      <div class="alignCenter" style="color:#999;padding:15px;"><img src="{{asset('shop/images/tishiss.jpg')}}" /><br />亲，您暂无分销订单信息！</div>
     </div><!--xiao-oreq-list/-->
     <div class="xiao-oreq-list">
      <div class="alignCenter" style="color:#999;padding:15px;"><img src="{{asset('shop/images/tishiss.jpg')}}" /><br />亲，您暂无分销订单信息！</div>
     </div><!--xiao-oreq-list/-->
     <div class="xiao-oreq-list">
      <div class="alignCenter" style="color:#999;padding:15px;"><img src="{{asset('shop/images/tishiss.jpg')}}" /><br />亲，您暂无分销订单信息！</div>
     </div><!--xiao-oreq-list/-->
     <div class="xiao-oreq-list">
      <div class="alignCenter" style="color:#999;padding:15px;"><img src="{{asset('shop/images/tishiss.jpg')}}" /><br />亲，您暂无分销订单信息！</div>
     </div><!--xiao-oreq-list/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('shop/js/jquery.min.js')}}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('shop/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('shop/js/style.js')}}"></script>
    <script>
	 $(".xiao-oreq li").click(function(){
		 $(this).addClass("roecur").siblings("li").removeClass("roecur");
		 var index=$(this).index();
		 $(".xiao-oreq-list").eq(index).fadeIn().siblings(".xiao-oreq-list").hide();
		 })
	</script>
  </body>
</html>