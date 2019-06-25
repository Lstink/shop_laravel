@extends('layouts.shop')

@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>我的订单</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     
     <div class="zhaieq oredereq">
      <a href="javascript:;" class="type zhaiCur" type="1">待付款</a>
      <a href="javascript:;" class="type" type="2">待发货</a>
      <a href="javascript:;" class="type" type="3">待收货</a>
      <a href="javascript:;" class="type" type="4">已取消</a>
      <div class="clearfix"></div>
     </div><!--oredereq/-->
     
     <div class="dingdanlist">
     
     @foreach($orderInfo as $val)
      <table order_id="{{ $val['order_id'] }}">
       <tr>
        <td colspan="2" width="65%">订单号：<strong>{{ $val['order_no'] }}</strong></td>
        <td width="35%" align="right">
        @if($val['pay_status'] == 1 && $val['is_del'] == 1)
          <div class="qingqu pay"><a href="{{ route('success',['order_id'=>$val['order_id']]) }}" class="orange">支付订单</a></div>
          <div class="qingqu del"><a href="javascript:;" class="orange">取消订单</a></div>
        @elseif($val['is_del'] == 2)
          <div class="qingqu"><a href="javascript:;" class="orange">已取消</a></div>
        @endif
        </td>
       </tr>
      @foreach($val['goodsInfo'] as $v)
       <tr onClick="window.location.href='{{ route('proInfo',['goods_id'=> $v['goods_id'] ]) }}'">
        <td class="dingimg" width="15%"><img src="{{ asset('storage').'/uploads'.'/'.$v['goods_img'] }}" /></td>
        <td width="50%">
         <h3>{{ $v['goods_name'] }}</h3>
         <time>下单时间：{{ date('Y-m-d H:i:s',$v['create_time']) }}</time>
        </td>
        <td align="right"><img src="{{asset('shop/images/jian-new.png')}}" /></td>
       </tr>
      @endforeach
       <tr>
        <th colspan="3"><strong class="orange">¥{{ $val['order_amount'] }}</strong></th>
       </tr>
      </table>
    @endforeach

     </div><!--dingdanlist/-->
     
     
     <div class="height1"></div>
<script>
$(function(){

  //获取type
  var type = {{ $type }};
  switch (type) {
    case 1:
      $('.oredereq').find('a:eq(0)').addClass('zhaiCur').siblings().removeClass('zhaiCur');
      break;
    case 2:
      $('.oredereq').find('a:eq(1)').addClass('zhaiCur').siblings().removeClass('zhaiCur');
      break;
    case 3:
      $('.oredereq').find('a:eq(2)').addClass('zhaiCur').siblings().removeClass('zhaiCur');
      break;
    case 5:
      $('.oredereq').find('a:eq(3)').addClass('zhaiCur').siblings().removeClass('zhaiCur');
      break;
    default:
      break;
  }
  //取消订单的点击事件
  $(document).on('click','.del',function(){
    //获取订单的id
    var order_id = $(this).parents('table').attr('order_id');
    var obj = $(this);
    //发送ajax
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.post(
      "/ajax/delOrder",
      {order_id:order_id},
      function(res){
        if (res == 1) {
          obj.parents('table').remove();
        }
      }
    );
  });

  //订单类型的点击事件
  $('.type').click(function(){
    //获取当前点击的类型
    var type = $(this).attr('type');
    var obj = $(this);
    //发送ajax
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.post(
      "/ajax/getOrderType",
      {type:type,ajax:1},
      function(res){
        $('.dingdanlist').html(res);
      }
    );
  });

});

</script>
@endsection