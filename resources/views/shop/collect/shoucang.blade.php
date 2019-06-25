@extends('layouts.shop')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>我的收藏</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">收藏栏共有：<strong class="orange">{{ $count }}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url({{asset('shop/images/xian.jpg')}}) left center no-repeat;"><a href="javascript:;" class="orange delAll">全部删除</a></td>
      </tr>
     </table>
     
     <div class="dingdanlist">
     @foreach($collectInfo as $v)
      <table goods_id="{{ $v -> goods_id }}">
       <tr>
        <td colspan="2" width="65%"></td>
        <td width="35%" align="right"><div class="qingqu"><a href="javascript:;" class="orange del">取消收藏</a></div></td>
       </tr>
       <tr onClick="window.location.href='{{ route('proInfo',['goods_id'=>$v -> goods_id]) }}'">
        <td class="dingimg" width="15%"><img src="{{ asset('storage').'/uploads'.'/'.$val -> goods_img }}" /></td>
        <td width="50%">
         <h3>{{ $v -> goods_name }}</h3>
        </td>
        <td align="right"><strong class="orange">¥{{ $v -> shop_price }}</strong></td>
       </tr>
      </table>
      @endforeach
     </div><!--dingdanlist/-->
     
     <div class="height1"></div>

<script>
  $(function(){

    //取消收藏的点击事件
    $('.del').click(function(){
      //获取订单的id
      var goods_id = $(this).parents('table').attr('goods_id');
      var obj = $(this);
      //发送ajax
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.post(
        "/ajax/delCollect",
        {goods_id:goods_id},
        function(res){
          if (res == 1) {
            obj.parents('table').remove();
          }
        }
      );
    });

    //全部删除的点击事件
    $('.delAll').click(function(){
      //发送ajax
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.post(
        "/ajax/delCollect",
        function(res){
          if (res == 1) {
            $('.dingdanlist').remove();
          }
        }
      );
    });
  });
</script>
@endsection('content')