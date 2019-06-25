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
  <body style="background:#EFEFEF;">
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>分销中心</h1>
      </div>
     </header>
     <ul class="yong-ti">
      <li class="yongcurs">佣金</li>
      <li style="border-right:0;">提现</li>
     </ul><!--yong-ti/-->
     <div class="yongti-list" style="display:block;">
      <div class="alignCenter" style="color:#999;"><img src="{{asset('shop/images/tishiss.jpg')}}" /><br />亲，您暂无收入信息！</div>
     </div>
     <div class="yongti-list">
      <div class="alignCenter" style="color:#999;"><img src="{{asset('shop/images/tishiss.jpg')}}" /><br />亲，您暂无提现信息！</div>
     </div>
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('shop/js/jquery.min.js')}}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('shop/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('shop/js/style.js')}}"></script>
    <script>
	 $(".yong-ti li").click(function(){
		 $(this).addClass("yongcurs").siblings("li").removeClass("yongcurs");
		 var index=$(this).index();
		 $(".yongti-list").eq(index).fadeIn().siblings(".yongti-list").hide();
		 })
	</script>
  </body>
</html>