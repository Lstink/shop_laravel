@extends('layouts.shop')

@section('content')
     <div class="userName">
      <dl class="names">
         @if(isset(session('userInfo')['headimgurl']))
         <dt><img src="{{session('userInfo')['headimgurl']}}" /></dt>
         @else
         <dt><img src="{{asset('shop/images/user01.png')}}" /></dt>
         @endif
       <dd>
        <h3>{{ $userName }}</h3>
       </dd>
       <div class="clearfix"></div>
      </dl>
      <div class="shouyi">
       <dl>
        <dt>我的余额</dt>
        <dd>0.00元</dd>
       </dl>
       <dl>
        <dt>我的积分</dt>
        <dd>0</dd>
       </dl>
       <div class="clearfix"></div>
      </div><!--shouyi/-->
     </div><!--userName/-->
     
     <ul class="userNav">
      <li><span class="glyphicon glyphicon-list-alt"></span><a href="{{ route('order',['type'=>1]) }}">我的订单</a></li>
      <div class="height2"></div>
      <div class="state">
         <dl>
          <dt><a href="{{ route('order',['type'=>1]) }}"><img src="{{asset('shop/images/user1.png')}}" /></a></dt>
          <dd><a href="{{ route('order',['type'=>1]) }}">待支付</a></dd>
         </dl>
         <dl>
          <dt><a href="{{ route('order',['type'=>2]) }}"><img src="{{asset('shop/images/user2.png')}}" /></a></dt>
          <dd><a href="{{ route('order',['type'=>2]) }}">代发货</a></dd>
         </dl>
         <dl>
          <dt><a href="{{ route('order',['type'=>3]) }}"><img src="{{asset('shop/images/user3.png')}}" /></a></dt>
          <dd><a href="{{ route('order',['type'=>3]) }}">待收货</a></dd>
         </dl>
         <dl>
          <dt><a href="{{ route('order',['type'=>1]) }}"><img src="{{asset('shop/images/user4.png')}}" /></a></dt>
          <dd><a href="{{ route('order',['type'=>1]) }}">全部订单</a></dd>
         </dl>
         <div class="clearfix"></div>
      </div><!--state/-->
      <li><span class="glyphicon glyphicon-usd"></span><a href="{{ route('ticket') }}">我的优惠券</a></li>
      <li><span class="glyphicon glyphicon-map-marker"></span><a href="{{ route('addressInfo') }}">收货地址管理</a></li>
      <li><span class="glyphicon glyphicon-star-empty"></span><a href="{{ route('collects') }}">我的收藏</a></li>
      <li><span class="glyphicon glyphicon-heart"></span><a href="shoucang.html">我的浏览记录</a></li>
      <li><span class="glyphicon glyphicon-usd"></span><a href="tixian.html">余额提现</a></li>
	 </ul><!--userNav/-->
     
     <div class="lrSub">
       <a href="{{ route('unLogin') }}" class="unLogin">退出登录</a>
     </div>
     
     <div class="height1"></div>

    <!--jq加减-->
    <script src="{{asset('shop/js/jquery.spinner.js')}}"></script>
   <script>
      $(function(){
         $('.spinnerExample').spinner({});
      });
   </script>
@endsection