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
    <script src="{{asset('shop/js/jquery.spinners.js')}}"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
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
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{ $num }}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url({{asset('shop/images/xian.jpg')}}) left center no-repeat;">
        <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
       </td>
      </tr>
     </table>
     
     
     <div class="dingdanlist">
      <table>
      @if(!empty($carInfo -> toArray()))
       <tr>
        <td width="100%" colspan="4"><span><input type="checkbox" id="checkAll" /> 全选</span></td>
       </tr>
       @endif
       @foreach($carInfo as $val)
       <tr goods_num="{{ $val -> goods_number }}" goods_id="{{ $val -> goods_id }}" buy_number="{{ $val -> buy_number }}">
        <td width="4%"><input type="checkbox" class="ck" /></td>
        <td class="dingimg" width="15%"><img src="{{ asset('storage').'/uploads'.'/'.$val -> goods_img }}" /></td>
        <td width="50%">
         <h3>{{ $val -> goods_name }}</h3>
         <time>下单时间：{{ $val -> create_time }}</time>
        </td>
        <td colspan="4"><strong class="orange">¥{{ $val -> shop_price }}</strong></td>
        <td align="right"><input type="text" class="spinnerExample" /></td>
       </tr>
       @endforeach

       @if(session('msg'))
          <font color="red">{{session('msg')}}</font>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
            <font color="red">{{ $error }}</font>
            @endforeach
        @else
        <font color="red" id="msg"></font>
        @endif
      </table>
     </div><!--dingdanlist/-->
     
     
     <div class="height1"></div>
     <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange" id='sumPrice'>¥0</strong></td>
       <td width="40%"><a href="javascript:;" class="jiesuan">去结算</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    
   <script>
     $(function(){
      $('.spinnerExample').spinner({});
      
      //遍历所有文本框
      $('.spinnerExample').each(function(){
        var buy_number = $(this).parents('tr').attr('buy_number');
        $(this).val(buy_number);
      });
      
      

        //全选 全不选
      $("#checkAll").click(function(){
        //获取当前复选框的checked属性
        var ck = $("#checkAll").prop('checked');
        if (ck == true) {
          $('.ck').prop('checked',true);
        }else{
          $('.ck').prop('checked',false);
        }
        //获取总价
        sumAll();
      });

      //购买数量 + 的点击事件
      $(".increase").click(function(){
        var _this = $(this);
        //获取商品的库存
        var goods_number = _this.parents("tr").attr('goods_num');
        //获取当前文本框内的值
        var text = parseInt(_this.prev().val());
        //选中当前商品的复选框
        _this.parents("tr").find("input[class='ck']").prop('checked',true);
        //判断当前的数量是否大于等于库存
        if (text >= goods_number) {
          //让当前 + 事件失效
          _this.prop('disabled',true);
        }
        changeNum(_this);
        //获取总价
        sumAll();
      });

      //购买数量 - 的点击事件
      $(".decrease").click(function(){
        //选中当前商品的复选框
        var _this = $(this);
        //获取当前文本框内的值
        var text = parseInt(_this.next().val());
        //选中当前商品的复选框
        _this.parents("tr").find("input[class='ck']").prop('checked',true);
        //判断当前的数量是否小于等于1
        if (text < 1) {
          //让当前 - 事件失效
          _this.prop('disabled',true);
        }else{
          changeNum(_this);
          //获取总价
          sumAll();
        }
        
      });

      //复选框的点击事件
      $('.ck').click(function(){
        sumAll();
      });

      //发送ajax的函数改变数据库数量
      function changeNum(_this)
      {
        //获取商品的id
        var goods_id = _this.parents("tr").attr('goods_id');
        //获取商品的库存
        var goods_number = _this.parents("tr").attr('goods_num');
        //获取文本框内的值
        var text = parseInt(_this.parent().find('.spinnerExample').val());
        //发送ajax修改数据
        $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
          type: "POST",
          async: false,
          url: "/ajax/changeNum",
          data: "goods_id="+goods_id+"&buy_number="+text,
          success: function(res){
            _this.parents('tr').find('.orange').text('￥'+res);
          }
        });
      }

      //统计商品总价
      function sumAll()
      {
        //获取已经被选择的复选框
        var ids = [];
        //遍历所有的复选框
        $('.ck').each(function(index){
          //拿出被选中的复选框中的goods_id
          var checked = $(this).prop('checked');
          if (checked == true) {
            //将id放入数组中
            ids[index] = $(this).parents('tr').attr('goods_id');
          }
        });
        $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post(
          "/ajax/sumAll",
          {ids:ids},
          function(res){
            $('#sumPrice').text('￥'+res);
          }
        );
      }

      //确认结算
      $('.jiesuan').click(function(){
        //遍历所有的复选框
        var ids = [];
        $('.ck').each(function(index){
          //拿出被选中的复选框中的goods_id
          var checked = $(this).prop('checked');
          if (checked == true) {
            //将id放入数组中
            ids.push($(this).parents('tr').attr('goods_id'));
          }
        });
        var id = ids.join(',');
        if (ids == '') {
          $('#msg').html('请选择一件商品进行结算');
          clearError();
        }else{
          $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $.post(
            "/ajax/subBuy",
            function(res){
              if (res.code == 2) {
                //用户未登录 跳转
                $('#msg').html('请登录后结算');
                setTimeout(() => {
                  location.href="{{route('login')}}";
                }, 2000);
              }else if(res.code == 1) {
                //用户登陆 跳转
                location.href="/pay/"+ids;
              }
            },'json'
          );
        }
      });

      //清楚错误信息的方法
      function clearError()
      {
        setTimeout(() => {
          $('#msg').empty();
        }, 2000);
      }  
    
    });
  
  
	</script>
  </body>
</html>
