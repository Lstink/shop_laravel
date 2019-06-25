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
    <!--jq加减-->
    <script src="{{asset('shop/js/jquery.spinner.js')}}"></script>
    <!-- HTML5 shim and Respond.jsfor IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
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
       <h1>购物车</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->

     <div class="dingdanlist">
      <table>
        @if(session('msg'))
          <font color="red" id="msg">{{session('msg')}}</font>
          <script>
            clearError();
            //清楚错误信息的方法
            function clearError()
            {
              setTimeout(() => {
                $('#msg').empty();
              }, 2000);
            }  
          </script>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
            <font color="red">{{ $error }}</font>
            @endforeach
        @else
        <font color="red" id="msg"></font>
        <script>
            clearError();
            //清楚错误信息的方法
            function clearError()
            {
              setTimeout(() => {
                $('#msg').empty();
              }, 2000);
            }  
          </script>
        @endif
        
        @if(!empty($addressInfo))
        <tr>
          <td colspan="2">
          <h3>{{ $addressInfo -> address_name }} {{ $addressInfo -> address_tel }}</h3>
          <time>{{ $addressInfo -> province }}{{ $addressInfo -> city }}{{ $addressInfo -> area }}{{ $addressInfo -> address_detail }}</time>
          </td>
          <td align="right"><a href="address.html" class="hui"><span class="glyphicon glyphicon-check"></span> 修改信息</a></td>
        </tr>
        @endif
       <tr Onclick="window.location.href='/address?goods_id={{$goods_id}}'">
        <td class="dingimg" width="75%" colspan="2">新增收货地址</td>
        <td align="right"><img src="{{ asset('shop/images/jian-new.png') }}" /></td>
       </tr>
       <!-- <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">选择收货时间</td>
        <td align="right"><img src="{{ asset('shop/images/jian-new.png') }}" /></td>
       </tr> -->
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">支付方式</td>
        <td align="right"><span class="hui">支付宝</span></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <!-- <tr>
        <td class="dingimg" width="75%" colspan="2">优惠券</td>
        <td align="right"><span class="hui">无</span></td>
       </tr> -->
       <!-- <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr> -->
       <!-- <tr>
        <td class="dingimg" width="75%" colspan="2">是否需要开发票</td>
        <td align="right"><a href="javascript:;" class="orange">是</a> &nbsp; <a href="javascript:;">否</a></td>
       </tr> -->
       <!-- <tr>
        <td class="dingimg" width="75%" colspan="2">发票抬头</td>
        <td align="right"><span class="hui">个人</span></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">发票内容</td>
        <td align="right"><a href="javascript:;" class="hui">请选择发票内容</a></td>
       </tr> -->
       <tr><td colspan="3" style="height:10px; background:#fff;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="3">商品清单</td>
       </tr>
        @foreach($goodsInfo as $val)
        <tr>
          <td class="dingimg" width="15%"><img src="{{ asset('storage').'/uploads'.'/'.$val -> goods_img }}" /></td>
          <td width="50%">
          <h3>{{ $val -> goods_name }}</h3>
          <time>下单时间：{{ $val -> create_time }}</time>
          </td>
          <td align="right"><span class="qingdan">X {{ $val -> buy_number }}</span></td>
        </tr>
        <tr>
          <th colspan="3"><strong class="orange">¥{{ $val -> countPrice }}</strong></th>
        </tr>
        @endforeach
       
       <tr>
        <td class="dingimg" width="75%" colspan="2">商品金额</td>
        <td align="right"><strong class="orange">¥{{ $sumAllPrice }}</strong></td>
       </tr>
       <!-- <tr>
        <td class="dingimg" width="75%" colspan="2">折扣优惠</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">抵扣金额</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">运费</td>
        <td align="right"><strong class="orange">¥10.00</strong></td>
       </tr> -->
       
      </table>
     </div><!--dingdanlist/-->
     
     
    </div><!--content/-->
    
    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange">¥{{ $sumAllPrice }}</strong></td>
       <td width="40%"><a href="javascript:;" class="jiesuan">提交订单</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

   <script>
   $(function(){
     $('.spinnerExample').spinner({});

     //结算的点击事件
     $('.jiesuan').click(function(){
       //获取商品的id
       var goods_id = '{{ $goods_id }}';
      //  alert(goods_id);
      $.ajaxSetup({
        headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.post(
        "/ajax/subOrder",
        {goods_id:goods_id},
        function(res){
          if (res.code == 1) {
            //存在默认的收货地址
            location.href="/success/"+res.msg;
          }else if(res.code == 0){
            $('#msg').text('请添加一个默认的收货地址');
          }else{
            $('#msg').text('未知错误，请重试');
          }
        },'json'
      );
      
     });
   });
	

  
	</script>
  </body>
</html>

