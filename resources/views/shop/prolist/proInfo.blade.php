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
    <link href="{{asset('shop/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/response.css')}}" rel="stylesheet">
    <script src="{{asset('shop/js/jquery.min.js')}}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('shop/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('shop/js/style.js')}}"></script>
    <!--焦点轮换-->
    <script src="{{asset('shop/js/jquery.excoloSlider.js')}}"></script>
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.jsdoesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>产品详情</h1>
      </div>
     </header>
     <div id="sliderA" class="slider">
     @foreach($data as $val)
            <a href="{{route('proInfo',['goods_id'=>$goodsInfo -> goods_id])}}">
            <img src="{{ asset('storage').'/uploads'.'/'.$goodsInfo -> goods_img }}" /></a>
      @endforeach
     </div><!--sliderA/-->
     <table class="jia-len">
      <tr>
       <th><strong class="orange">{{ $goodsInfo -> shop_price }}</strong></th>
       <td>
        <input type="text" class="spinnerExample" />
       </td>
      </tr>
      <tr>
       <td>
        <strong>{{ $goodsInfo -> goods_name }}</strong>
        <p class="hui">{{ $goodsInfo -> description }}</p>
       </td>
       <td align="right">
        @if($res == 1)
          <a href="javascript:;" class="shoucang" style="color: #da1b1b"><span class="glyphicon glyphicon-star-empty"></span></a>
        @else
          <a href="javascript:;" class="shoucang"><span class="glyphicon glyphicon-star-empty"></span></a>
        @endif
       </td>
      </tr>
     </table>
     <div class="height2"></div>
     <h3 class="proTitle">商品规格</h3>
     <ul class="guige">
      <li class="guigeCur"><a href="javascript:;">50ML</a></li>
      <li><a href="javascript:;">100ML</a></li>
      <li><a href="javascript:;">150ML</a></li>
      <li><a href="javascript:;">200ML</a></li>
      <li><a href="javascript:;">300ML</a></li>
      <div class="clearfix"></div>
     </ul><!--guige/-->
     <div class="height2"></div>
     <div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <a href="javascript:;">商品参数</a>
      <a href="javascript:;" style="background:none;">订购列表</a>
      <div class="clearfix"></div>
     </div><!--zhaieq/-->
     <div class="proinfoList">
      <img src="{{ asset('storage').'/uploads'.'/'.$goodsInfo -> goods_img }}" width="636" height="822" />
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息....
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息......
     </div><!--proinfoList/-->

     @if(session('msg'))
          <font color="red">{{session('msg')}}</font>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
            <font color="red">{{ $error }}</font>
            @endforeach
        @else
        <span id="msg"></span>
        @endif

     <table class="jrgwc">
      <tr>
       <th>
        <a href="index.html"><span class="glyphicon glyphicon-shopping-cart"></span></a>
       </th>
       <td><a href="javascript:;" id="addBuy">加入购物车</a></td>
      </tr>
     </table>
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script>
		$(function () {
		 $("#sliderA").excoloSlider();
		});
	</script>
     <!--jq加减-->
    <script src="{{asset('shop/js/jquery.spinner.js')}}"></script>
<script>
  //获取库存 passive
  var goods_number = {{ $goodsInfo -> goods_number }};
  $('.spinnerExample').spinner({ max:goods_number });
  
</script>
<script>
  $(function(){

    //加号的点击事件
    $('.increase').click(function(){
      //获取库存 passive
      var goods_number = {{ $goodsInfo -> goods_number }};
      //获取值
      var value = $('.spinnerExample').val();
      if (value >= goods_number) {
        $('.spinnerExample').addClass('passive');
      }
    });

    //加入购物车的点击事件
    $('#addBuy').click(function(){
        //获取文本框内的值
        var val = $('.spinnerExample').val();
        //获取商品的id
        var goods_id = {{ $goodsInfo -> goods_id }};
        $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post(
            "{{route('addBuy')}}",
            {buy_number:val,goods_id:goods_id},
            function(res){
                if (res.code == 1) {
                  $('#msg').html("<font color='green'>"+res.msg+"</font>");
                }else if(res.code == 3){
                  //强制登陆
                  $('#msg').html("<font color='red'>"+res.msg+"</font>");
                  setTimeout(() => {
                    location.href='/login';
                  }, 2000);
                }else{
                  $('#msg').html("<font color='red'>"+res.msg+"</font>");
                }
            },'json'
        );
    });

    //收藏的点击事件
    $('.shoucang').click(function(){
      //获取当前商品的id
      var goods_id = {{ $goodsInfo -> goods_id }};
      //获取css属性
      var color = $(this).css('color');
      //切换颜色
      if (color == '#999') {
        $(this).css('color','#da1b1b');
      }else{
        $(this).css('color','#999');
      }
      //发送ajax添加收藏
      $.ajaxSetup({
          headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.post(
          "{{route('collect')}}",
          {goods_id:goods_id},
          function(res){
              if (res == 1) {
                $('.shoucang').css('color','#da1b1b');
              }else{
                $('.shoucang').css('color','#999');
              }
          },'json'
      );
    });
  });
</script>

  </body>
</html>