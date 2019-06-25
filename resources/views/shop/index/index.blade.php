@extends('layouts.shop')

@section('content')
      <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
      <dl>
      @if(session('userInfo'))
            @if(isset(session('userInfo')['headimgurl']))
            <dt><a href="{{ route('user') }}"><img src="{{session('userInfo')['headimgurl']}}" /></a></dt>
            @else
            <dt><a href="{{ route('user') }}"><img src="{{asset('shop/images/touxiang.jpg')}}" /></a></dt>
            @endif
      @endif
       <dd>
        <h1 class="username">{{session('userInfo')['u_email']}}</h1>
        <ul>
         <li><a href="{{route('prolist')}}"><strong>{{$sum}}</strong><p>全部商品</p></a></li>
         <li><a href="javascript:;"><span class="glyphicon glyphicon-star-empty"></span><p>收藏本店</p></a></li>
         <li style="background:none;"><a href="javascript:;"><span class="glyphicon glyphicon-picture"></span><p>二维码</p></a></li>
         <div class="clearfix"></div>
        </ul>
       </dd>
       <div class="clearfix"></div>
      </dl>
     </div><!--head-top/-->
     <form action="{{route('prolist')}}" method="get" class="search">
      <input type="text" name="goods_name" class="seaText fl" />
      <input type="submit" value="搜索" class="seaSub fr" />
     </form><!--search/-->
     @if(!session('userInfo'))
     <ul class="reg-login-click">
      <li><a href="/login">登录</a></li>
      <li><a href="/reg" class="rlbg">注册</a></li>
      <div class="clearfix"></div>
     </ul><!--reg-login-click/-->
     @endif
      
     <div id="sliderA" class="slider">
      <!-- 轮播图 -->
      @foreach($max as $val)
            <a href="{{route('proInfo',['goods_id'=>$val->goods_id])}}"><img src="{{ asset('storage').'/uploads'.'/'.$val -> goods_img }}" /></a>
      @endforeach

     </div><!--sliderA/-->

     <ul class="pronav">
      <!-- 商品名称 -->
      @foreach($top as $val)
            <li><a href="{{route('prolist',['cate_id'=>$val->cate_id])}}">{{ $val -> cate_name }}</a></li>
      @endforeach

      <div class="clearfix"></div>
     </ul><!--pronav/-->
     <div class="index-pro1">
      <!-- 最热 -->
      @foreach($hot as $val)
      <div class="index-pro1-list">
       <dl>
        <dt><a href="{{route('proInfo',['goods_id'=>$val->goods_id])}}"><img src="{{ asset('storage').'/uploads'.'/'.$val -> goods_img }}" style="width:293px;height:299px" /></a></dt>
        <dd class="ip-text"><a href="proinfo.html">{{ $val -> goods_name }}</a><span>库存：{{ $val -> goods_number }}</span></dd>
        <dd class="ip-price"><strong>¥{{ $val -> shop_price }}</strong> <span>¥{{ $val -> market_price }}</span></dd>
       </dl>
      </div>
      @endforeach

      <div class="clearfix"></div>
     </div><!--index-pro1/-->
     <div class="prolist">

     @foreach($data as $val)
      <dl>
       <dt><a href="{{route('proInfo',['goods_id'=>$val->goods_id])}}"><img src="{{ asset('storage').'/uploads'.'/'.$val -> goods_img }}" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="proinfo.html">{{ $val -> goods_name }}</a></h3>
        <div class="prolist-price"><strong>¥{{ $val -> shop_price }}</strong> <span>¥{{ $val -> market_price }}</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>库存：{{ $val -> goods_number }}</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
      @endforeach

     </div><!--prolist/-->
     <div class="joins"><a href="fenxiao.html"><img src="{{asset('shop/images/jrwm.jpg')}}" /></a></div>
     <div class="copyright">Copyright &copy; <span class="blue">这是就是三级分销底部信息</span></div>
     
     <div class="height1"></div>
@endsection